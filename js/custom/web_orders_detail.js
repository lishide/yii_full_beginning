var vueApp = null;

function _ordersDetailInit() {
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
            pay: function () {
                if (data.order.use_balance != 0) {
                    location.href = data.baseUrl + 'orders/pay/sn/' + data.sn + '/token/' + _getSessionObj('user').token;
                } else {
                    $('#input-balance').val('0');
                    $('#modal-pay').modal({
                        onConfirm: function (e) {
                            location.href = data.baseUrl + 'orders/pay/sn/' + data.sn + '/token/' + _getSessionObj('user').token + '/balance/' + data.pay_balance;
                        }
                    });
                }
            }
        }
    });

    loadingStart();
    _postAPI('orders', 'detail', {token: data.user.token, sn: data.sn}, function (res) {
        if (res.state == 1) {
            data.order = res.data[0];
            _postAPI('user', 'profile', {token: data.user.token}, function (res) {
                if (res.state == 1) {
                    data.user.balance = res.data.balance;
                    _saveSession('user', data.user);
                }
            });
        } else {
            alert(res.error_msg);
        }
        loadingEnd();
    });

    $("#input-balance").on('input', function (e) {
        onPayBalanceChange();
    });
}

function onPayBalanceChange() {
    var balance = $('#input-balance').val();
    if (balance == '') {
        balance = 0;
    }
    var max = parseFloat(data.user.balance);
    if (parseFloat(data.order.price) < max) {
        max = parseFloat(data.order.price);
    }

    if (balance < 0) {
        balance = 0;
    } else if (balance > max) {
        balance = max;
    }

    balance = parseFloat(balance);
    balance = parseFloat(balance.toFixed(2));
    data.pay_balance = balance;

    $('#input-balance').val(balance);
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