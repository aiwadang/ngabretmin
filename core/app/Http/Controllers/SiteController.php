<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\GatewayCurrency;
use App\Models\Language;
use App\Models\Page;
use App\Models\Ride;
use App\Models\Subscriber;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Services\Rider\RideService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{

    protected $rideService;

    public function __construct(RideService $rideService)
    {
        $this->rideService = $rideService;
    }

    public function index()
    {
        $pageTitle = "Home";
        $sections = Page::where("tempname", activeTemplate())
            ->where("slug", "/")
            ->first();

        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath("seo") . "/" . @$seoContents->image, getFileSize("seo")) : null;
        $user = auth()->user();

        $runningRide = Ride::whereNotIn("status", [
            Status::RIDE_CANCELED,
            Status::RIDE_COMPLETED,
        ])
            ->where("user_id", $user?->id)
            ->with([
                "user",
                "driver",
                "driver.vehicle",
                "driver.vehicle.model",
                "driver.vehicle.color",
                "driver.vehicle.year",
                "driver.vehicle.brand",
            ])
            ->first();

        $paymentMethods = GatewayCurrency::whereHas(
            "method",
            fn($query) => $query->active()->automatic(),
        )
            ->with("method")
            ->orderBy("method_code")
            ->get();

        return view("Template::home", compact("pageTitle", "sections", "seoContents", "seoImage", "runningRide", "paymentMethods"));
    }

    public function pages($slug)
    {
        $page = Page::where("tempname", activeTemplate())
            ->where("slug", $slug)
            ->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image
            ? getImage(
                getFilePath("seo") . "/" . @$seoContents->image,
                getFileSize("seo"),
            )
            : null;
        return view(
            "Template::pages",
            compact("pageTitle", "sections", "seoContents", "seoImage"),
        );
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where("tempname", activeTemplate())
            ->where("slug", "contact")
            ->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image
            ? getImage(
                getFilePath("seo") . "/" . @$seoContents->image,
                getFileSize("seo"),
            )
            : null;
        return view(
            "Template::contact",
            compact("pageTitle", "user", "sections", "seoContents", "seoImage"),
        );
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required",
            "subject" => "required|string|max:255",
            "message" => "required",
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ["error", "Invalid captcha provided"];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;

        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = "A new contact message has been submitted";
        $adminNotification->click_url = urlPath(
            "admin.ticket.view",
            $ticket->id,
        );
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ["success", "Ticket created successfully"];

        return to_route("ticket.view", [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where("slug", $slug)
            ->where("data_keys", "policy_pages.element")
            ->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy->seo_content;
        $seoImage = @$seoContents->image
            ? frontendImage(
                "policy_pages",
                $seoContents->image,
                getFileSize("seo"),
                true,
            )
            : null;
        return view(
            "Template::policy",
            compact("policy", "pageTitle", "seoContents", "seoImage"),
        );
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where("code", $lang)->first();
        if (!$language) {
            $lang = "en";
        }
        session()->put("lang", $lang);
        return back();
    }

    public function blog()
    {
        $pageTitle = "Blogs";
        $blogs = Frontend::where("data_keys", "blog.element")
            ->latest("id")
            ->paginate(getPaginate(18));
        $sections = Page::where("tempname", activeTemplate())
            ->where("slug", "blog")
            ->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image
            ? frontendImage(
                "blog",
                $seoContents->image,
                getFileSize("seo"),
                true,
            )
            : null;
        return view(
            "Template::blog",
            compact(
                "pageTitle",
                "blogs",
                "sections",
                "seoContents",
                "seoImage",
            ),
        );
    }

    public function blogDetails($slug)
    {
        $blog = Frontend::where("slug", $slug)
            ->where("data_keys", "blog.element")
            ->firstOrFail();
        $latestBlogs = Frontend::where("slug", "!=", $slug)
            ->where("data_keys", "blog.element")
            ->take(10)
            ->latest("id")
            ->get();
        $pageTitle = "Blog Details";
        $seoContents = $blog->seo_content;
        if ($seoContents) {
            $seoImage = frontendImage(
                "blog",
                $seoContents->image,
                getFileSize("seo"),
                true,
            );
        } else {
            $seoContents = (object) [
                "title" => $blog->data_values->title,
                "social_title" => $blog->data_values->title,
                "description" => strLimit(
                    strip_tags(@$blog->data_values->description),
                    300,
                ),
                "social_description" => strLimit(
                    strip_tags(@$blog->data_values->description),
                    300,
                ),
            ];
            $seoImage = frontendImage("blog", @$blog->data_values->image);
        }
        return view(
            "Template::blog_details",
            compact(
                "blog",
                "pageTitle",
                "seoContents",
                "seoImage",
                "latestBlogs",
            ),
        );
    }

    public function cookieAccept()
    {
        Cookie::queue("gdpr_cookie", gs("site_name"), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where("data_keys", "cookie.data")->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = "Cookie Policy";
        $cookie = Frontend::where("data_keys", "cookie.data")->first();
        return view("Template::cookie", compact("pageTitle", "cookie"));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }



    public function maintenance()
    {
        $pageTitle = "Maintenance Mode";
        if (gs("maintenance_mode") == Status::DISABLE) {
            return to_route("home");
        }
        $maintenance = Frontend::where(
            "data_keys",
            "maintenance.data",
        )->first();
        return view(
            "Template::maintenance",
            compact("pageTitle", "maintenance"),
        );
    }

    public function subscribe(Request $request)
    {
        $validator = validator()->make(
            $request->all(),
            [
                "email" => "required|email|unique:subscribers,email",
            ],
            [
                "email.unique" => "You are already with us.",
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => $validator->errors()->getMessages(),
            ]);
        }

        $subscribe = new Subscriber();
        $subscribe->email = $request->email;
        $subscribe->save();

        return response()->json([
            "success" => true,
            "message" => "Thanks for connecting with us.",
        ]);
    }

    public function howToWork()
    {
        $pageTitle = "How to Work";
        $sections = Page::where("tempname", activeTemplate())
            ->where("slug", "how_it_works")
            ->firstOrFail();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image
            ? getImage(
                getFilePath("seo") . "/" . @$seoContents->image,
                getFileSize("seo"),
            )
            : null;

        return view(
            "Template::how_to_work",
            compact("pageTitle", "sections", "seoContents", "seoImage"),
        );
    }

    public function driverJoin()
    {
        $pageTitle = "Join as a Driver";
        $sections  = Page::where("tempname", activeTemplate())
            ->where("slug", "join-as-a-driver")
            ->firstOrFail();

        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? getImage(getFilePath("seo") . "/" . @$seoContents->image, getFileSize("seo")) : null;

        return view("Template::driver_join", compact("pageTitle", "sections", "seoContents", "seoImage"));
    }

    public function startRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "pickup_location"       => "required|string",
            "pickup_latitude"       => "required|string",
            "pickup_longitude"      => "required|string",
            "destination_location"  => "required|string",
            "destination_latitude"  => "required|string",
            "destination_longitude" => "required|string",
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", 'error', $validator->errors()->all());
        }

        if (session()->has('search_details')) {
            session()->forget('search_details');
        }

        if (auth()->check()) {

            $runningRide = Ride::whereNotIn('status', [Status::RIDE_CANCELED, Status::RIDE_COMPLETED])
                ->where('user_id', auth()->id())
                ->exists();

            if ($runningRide) {
                return apiResponse("ride_started", 'success', ['You already have a running ride. Please complete it first.'], [
                    'redirect_url' => route('user.ride.process.step')
                ]);
            }
        }

        $rideService = $this->rideService;
        $detectZone  = $rideService->detectZones($request->pickup_latitude, $request->pickup_longitude, $request->destination_latitude, $request->destination_longitude);

        if (strtolower($detectZone['status']) == 'error') {
            return apiResponse("error", 'error', [
                "Currently we are not operating in pickup location " . keyToTitle($request->pickup_location) . " Please choose another pickup locations."
            ]);
        }

        if ($detectZone['pickup_zone']->id == $detectZone['destination_zone']->id) {
            $rideType = Status::CITY_RIDE;
        } else {
            $rideType = Status::INTER_CITY_RIDE;
        }

        $request->merge(['ride_type' => $rideType]);

        session()->put("search_details", $request->except(['_token']));

        return apiResponse("ride_started", 'success', ['Location searched successfully.'], [
            'redirect_url' => route('user.ride.process.step')
        ]);
    }
}
