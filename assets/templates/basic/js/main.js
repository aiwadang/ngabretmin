'use strict';
(function ($) {
  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {
    // ============== Header Hide Click On Body Js Start ========
    $('.header-button').on('click', function () {
      $('.body-overlay').toggleClass('show');
    });
    $('.body-overlay').on('click', function () {
      $('.header-button').trigger('click');
      $(this).removeClass('show');
    });
    // =============== Header Hide Click On Body Js End =========
    // // ========================= Header Sticky Js Start ==============
    $(window).on('scroll', function () {
      if ($(window).scrollTop() >= 300) {
        $('.header').addClass('fixed-header');
      } else {
        $('.header').removeClass('fixed-header');
      }
    });
    // // ========================= Header Sticky Js End===================


    // //============================ Scroll To Top Icon Js Start =========
    var btn = $('.scroll-top');

    $(window).scroll(function () {
      if ($(window).scrollTop() > 300) {
        btn.addClass('show');
      } else {
        btn.removeClass('show');
      }
    });

    btn.on('click', function (e) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, '300');
    });

    // ========================== Header Hide Scroll Bar Js Start =====================
    $('.navbar-toggler.header-button').on('click', function () {
      $('body').toggleClass('scroll-hide-sm');
    });
    $('.body-overlay').on('click', function () {
      $('body').removeClass('scroll-hide-sm');
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $('.dropdown-item').on('click', function () {
      $(this).closest('.dropdown-menu').addClass('d-block');
    });
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

    // ========================== Add Attribute For Bg Image Js Start =====================
    $('.bg-img').css('background', function () {
      var bg = 'url(' + $(this).data('background-image') + ')';
      return bg;
    });
    // ========================== Add Attribute For Bg Image Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================
    function dynamicActiveMenuClass(selector) {
      let fileName = window.location.pathname.split('/').reverse()[0];
      selector.find('li').each(function () {
        let anchor = $(this).find('a');
        if ($(anchor).attr('href') == fileName) {
          $(this).addClass('active');
        }
      });
      // if any li has active element add class
      selector.children('li').each(function () {
        if ($(this).find('.active').length) {
          $(this).addClass('active');
        }
      });
      // if no file name return
      if ('' == fileName) {
        selector.find('li').eq(0).addClass('active');
      }
    }
    if ($('ul.sidebar-menu-list').length) {
      dynamicActiveMenuClass($('ul.sidebar-menu-list'));
    }
    // ========================== add active class to ul>li top Active current page Js End =====================

    // ================== Password Show Hide Js Start ==========
    $('.toggle-password').on('click', function () {
      $(this).toggleClass('fa-eye');

      var input = $($(this).data('target'));

      if (input.attr('type') == 'password') {
        input.attr('type', 'text');
      } else {
        input.attr('type', 'password');
      }
    });

    // =============== Password Show Hide Js End =================



    // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $('.has-dropdown > a').on("click", function () {
      $('.sidebar-submenu').slideUp(200);
      if ($(this).parent().hasClass('active')) {
        $('.has-dropdown').removeClass('active');
        $(this).parent().removeClass('active');
      } else {
        $('.has-dropdown').removeClass('active');
        $(this).next('.sidebar-submenu').slideDown(200);
        $(this).parent().addClass('active');
      }
    });
    // Sidebar Dropdown Menu End
    // Sidebar Icon & Overlay js
    $('.sidebar-trigger').on('click', function () {
      $('.sidebar-menu').addClass('show');
      $('.body-overlay').addClass('show');
    });
    $('.sidebar-menu__close, .body-overlay').on('click', function () {
      $('.sidebar-menu').removeClass('show');
      $('.body-overlay').removeClass('show');
    });
    // Sidebar Icon & Overlay js

    // Sidebar Icon & Overlay js 
    $(".driver__bar-icon").on("click", function () {
      $(".driver-sidebar-menu").addClass('show');
      $(".sidebar-overlay").addClass('show');
    });
    $(".driver-sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".driver-sidebar-menu").removeClass('show');
      $(".sidebar-overlay").removeClass('show');
    });

    // ===================== Sidebar Menu Js End =================

    // ==================== Dashboard User Profile Dropdown Start ==================

    $('.user-info__button').on('click', function (e) {
      e.stopPropagation();
      $(this).next('.user-info-dropdown').toggleClass('show');
    });

    $(document).on('click', function (e) {
      if (!$(e.target).closest('.user-info').length) {
        $('.user-info-dropdown').removeClass('show');
      }
    });

    $('.user-info-dropdown').on('click', function (e) {
      e.stopPropagation();
    });

  });


  document.querySelectorAll('.input-group-custom').forEach(group => {
    const input = group.querySelector('.input-group-custom__input');
    const toggle = group.querySelector('.form-check-icon');

    if (input && toggle) {
      toggle.addEventListener('click', () => {
        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        toggle.textContent = isPassword ? 'hide' : 'show';
      });
    }
  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Custom Language Dropdown Js Start =====================
  $('.custom--dropdown > .custom--dropdown__selected').on('click', function () {
    $(this).parent().toggleClass('open');
  });

  $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function () {
    $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
    $(this).addClass('selected').parent().parent().removeClass('open').children('.custom--dropdown__selected').html($(this).html());
  });

  $(document).on('keyup', function (evt) {
    if ((evt.keyCode || evt.which) === 27) {
      $('.custom--dropdown').removeClass('open');
    }
  });

  $(document).on('click', function (evt) {
    if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
      $('.custom--dropdown').removeClass('open');
    }
  });
  // ========================= Custom Language Dropdown Js End =====================



  // ========================= Note input show hide Js Start =====================
  // $('.note-btn').on('click', function () {
  //   $(this).closest('.input-group__wrapper').find('.note-input').toggleClass('show');
  // });
  // ========================= Note input show hide Js End =====================

  // $(document).on('click', function (e) {
  //   if (!$(e.target).closest('.input-group-custom__wrapper, .search-input-dropdown__wrapper').length) {
  //     $('.search-input-dropdown__wrapper').hide();
  //   }
  // });

  // ============================== BANNER SEARCH DROPDOWN


  // ========================= Preloader Js Start =====================
  $(window).on('load', function () {
    setTimeout(() => {
      $('.preloader').fadeOut();
    }, 500);
  });

  new WOW().init();

  // ========================= Preloader Js End=====================



  $("[data-highlight]").each(function () {
    const $this = $(this);
    let originalText = $this.text().trim().split(" ");
    let textLength = originalText.length;
    const highlight = $this.data("highlight").toString();
    const highlight_class =
      $this.data("highlight-class")?.toString() || "text--base";
    const highlightToArray = highlight.split(",");
    // Loop through each highlight range
    $.each(highlightToArray, function (i, element) {
      const index = element.toString().split("_");
      var startIndex = index[0];
      var endIndex = index.length > 1 ? index[1] : startIndex;
      if (startIndex < 0) {
        startIndex = textLength - Math.abs(startIndex);
      }
      if (endIndex < 0) {
        endIndex = textLength - Math.abs(endIndex);
      }
      const startIndexValue = originalText[startIndex];
      const endIndexValue = originalText[endIndex];
      if (startIndex === endIndex) {
        originalText[
          startIndex
        ] = `<span class="${highlight_class}">${startIndexValue}</span>`;
      } else {
        originalText[
          startIndex
        ] = `<span class="${highlight_class}">${startIndexValue}`;
        originalText[endIndex] = `${endIndexValue}</span>`;
      }
    });
    $this.html(originalText.join(" "));
  });

})(jQuery);


const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
