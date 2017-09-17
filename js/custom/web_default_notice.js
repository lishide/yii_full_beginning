var vueApp = null;

function _defaultNoticeInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
            
        }
    });

    loadingStart();
    _postAPI('notice', 'list', {token: data.user.token}, function (res) {
        if (res.state == 1 || res.code == 98) {
            data.notices = res.data;
            loadingEnd();
        } else {
            alert(res.error_msg);
            loadingEnd();
        }
    });
}

function loadingStart() {
    $('#modal-loading').modal();
}

function loadingEnd() {
    $('#modal-loading').modal('close');
}

function alert(alert) {
    data.alert = alert;
    $('#modal-alert').modal();
}