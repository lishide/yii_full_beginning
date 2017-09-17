var vueApp = null;

function _userProfileInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
            changeProfile: function () {
                var postData = $('#form-profile').serializeObject();
                postData.token = data.user.token;

                loadingStart();
                _postAPI('user', 'editprofile', postData, function (res) {
                    if (res.state == 1) {
                        var token = data.user.token;
                        data.user = res.data;
                        data.user.token = token;

                        _saveSession('user', data.user);

                        location.reload();
                    } else {
                        alert(res.error_msg);
                    }
                    loadingEnd();
                });
            },
            changePwd: function () {
                var postData = $('#form-pwd').serializeObject();
                postData.token = data.user.token;
                postData.oldpwd = $.md5(postData.oldpwd);

                loadingStart();
                _postAPI('user', 'changepwd', postData, function (res) {
                    if (res.state == 1) {
                        _saveSession('user', null);
                        $('#modal-pwd-changed').modal({
                            closeViaDimmer: false
                        });
                    } else {
                        alert(res.error_msg);
                    }
                    loadingEnd();
                });
            },
            redir: function () {
                location.href = data.baseUrl;
            },
            getUserRole: function (role) {
                var allRoles = {
                    1: '会员',
                    2: '直营店',
                    3: '区域代理'
                };

                return allRoles[role];
            },
            payForStore: function () {
                var postData = {
                    token: data.user.token,
                    amount: 1,
                    msg: '',
                    goodsid: -1
                };
                loadingStart();
                _postAPI('orders', 'submit', postData, function (res) {
                    if (res.state == 1) {
                        loadingEnd();
                        data.storeOrder = res.data[0];
                        data.pay_balance = 0;
                        $('#input-balance').val('0');
                        $('#modal-pay').modal({
                            onConfirm: function (e) {
                                location.href = data.baseUrl + 'orders/pay/sn/' + data.storeOrder.sn + '/token/' + data.user.token + '/balance/' + data.pay_balance;
                            }
                        });
                    } else {
                        loadingEnd();
                        alert(res.error_msg);
                    }
                });
            }
        }
    });

    loadingStart();
    _postAPI('user', 'profile', {token: data.user.token}, function (res) {
        if (res.state == 1) {
            data.profile = res.data;
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
    if (parseFloat(data.storeOrder.price) < max) {
        max = parseFloat(data.storeOrder.price);
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