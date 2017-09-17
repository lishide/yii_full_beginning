var vueApp = null;

function _userBillInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
            getTradeType: function (type) {
                var types = {
                    1: '转账',
                    2: '佣金',
                    3: '提现',
                    4: '消费',
                    5: '退款'
                };
                return types[type];
            },
            getWithdrawStatus: function (status) {
                var allStatus = {
                    1: '待处理',
                    2: '成功',
                    3: '失败'
                };
                return allStatus[status];
            },
            withdraw: function () {
                $('#modal-withdraw-confirm').modal({
                    onConfirm: function (e) {
                        var alipay = e.data[0];
                        var realname = e.data[1];
                        var amount = e.data[2];

                        loadingStart();
                        _postAPI('user', 'withdrawapply', {
                            token: data.user.token,
                            alipay: alipay,
                            realname: realname,
                            amount: amount
                        }, function (res) {
                            if (res.state == 1) {
                                location.reload();
                            } else {
                                alert(res.error_msg);
                            }
                            loadingEnd();
                        });
                    }
                });
            },
            transfer: function () {
                $('#modal-transfer-confirm').modal({
                    onConfirm: function (e) {
                        var phone = e.data[0];
                        var amount = e.data[1];

                        loadingStart();
                        _postAPI('user', 'transfer', {
                            token: data.user.token,
                            phone: phone,
                            amount: amount
                        }, function (res) {
                            if (res.state == 1) {
                                location.reload();
                            } else {
                                alert(res.error_msg);
                            }
                            loadingEnd();
                        });
                    }
                });
            }
        }
    });

    loadingStart();
    _postAPI('user', 'moneylog', {token: data.user.token}, function (res) {
        if (res.state == 1 || res.code == 98) {
            data.logs = res.data;
            _postAPI('user', 'withdrawhistory', {token: data.user.token}, function (res) {
                if (res.state == 1 || res.code == 98) {
                    data.withdrawlog = res.data;
                    _postAPI('user', 'profile', {token: data.user.token}, function (res) {
                        if (res.state == 1) {
                            data.profile = res.data;
                        } else {
                            alert(res.error_msg);
                        }
                        loadingEnd();
                    });
                } else {
                    alert(res.error_msg);
                    loadingEnd();
                }
            });
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