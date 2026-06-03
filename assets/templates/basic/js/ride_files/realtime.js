const { key, cluster, authEndPoint } = window.appConfig.pusher;

window.pusher = new Pusher(key, {
    cluster,
    forceTLS: true,
    authEndPoint,
    auth: {
        headers: {
            'X-CSRF-TOKEN': window.appConfig.csrfToken
        }
    }
});