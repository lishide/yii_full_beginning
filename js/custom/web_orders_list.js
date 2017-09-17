var vueApp = null;

function _ordersListInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
            getOrderStatus: function (status) {
                var allStatus = {
                    1: '待付款',
                    2: '待发货',
                    3: '待收货',
                    4: '已收货',
                    5: '已取消',
                    6: '已退单'
                };
                return allStatus[status];
            },
            detail: function (sn) {
                location.href = data.baseUrl + 'orders/detail/sn/' + sn;
            }
        }
    });

    loadingStart();
    _postAPI('orders', 'list', {token: data.user.token, list_num: 500, start_pos: 0}, function (res) {
        if (res.state == 1) {
            data.orders = res.data;
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