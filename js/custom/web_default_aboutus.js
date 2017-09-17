var vueApp = null;

function _defaultAboutusInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
        }
    });

    loadingStart();
    _postAPI('system', 'getconfig', {}, function (res) {
        if (res.state == 1) {
            data.configs = res.data;
        } else {
            alert(res.error_msg);
        }
        loadingEnd();
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