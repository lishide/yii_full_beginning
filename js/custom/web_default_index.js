var vueApp = null;

function _defaultIndexInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
            getGoodsRow: function (index) {
                var res = [];
                for (var i = index; i < data.goodsList.length && i < index + 3; i++) {
                    res.push(data.goodsList[i]);
                }
                return res;
            },
            toggleLogin: function () {
                if (data.user) {
                    data.user = null;
                    _saveSession('user', null);
                } else {
                    $('#modal-login').modal({
                        onConfirm: function (e) {
                            loadingStart();
                            _postAPI('user', 'login', {phone: e.data[0], password: $.md5(e.data[1])}, function (res) {
                                if (res.state == 1) {
                                    _saveSession('user', res.data);
                                    location.reload();
                                } else {
                                    alert(res.error_msg);
                                }
                                loadingEnd();
                            });
                        }
                    });
                }
            },
            buy: function (goodsID) {
                _saveSession('buy_goods_id', goodsID);
                $('#modal-buy-confirm').modal({
                    onConfirm: function (e) {
                        var amount = e.data;
                        var postData = {
                            token: data.user.token,
                            amount: e.data[0],
                            msg: e.data[1],
                            goodsid: _getSessionStr('buy_goods_id')
                        };
                        loadingStart();
                        _postAPI('orders', 'submit', postData, function (res) {
                            if (res.state == 1) {
                                location.href = data.baseUrl + 'orders/detail/sn/' + res.data[0].sn;
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
    _postAPI('goods', 'list', {categoryid: 1, start_pos: 0, list_num: 500}, function (res) {
        if (res.state == 1) {
            data.goodsList = res.data;
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