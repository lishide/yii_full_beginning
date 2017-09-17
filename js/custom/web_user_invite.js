var vueApp = null;

function _userInviteInit() {
    vueApp = new Vue({
        el: '#app',
        data: data,
        methods: {
            inviteOrDown: function (index) {
                if (data.user.level < 1) {
                    return;
                }
                if (data.children[index].id != 0) {
                    // 下一级
                    if (data.levelNow >= 2) {
                        return;
                    }

                    data.levelNow++;
                    data.levels[data.levelNow] = data.children[index];

                    // data.nextFather = data.children[index];
                    // data.lastFather = data.father;
                    getChildInfo(data.children[index].id);
                } else {
                    $('#modal-invite').modal({
                        onConfirm: function (e) {
                            loadingStart();
                            _postAPI('user', 'reg', {
                                phone: e.data[0],
                                password: e.data[1],
                                contact: e.data[2],
                                addr: e.data[3],
                                inviteuid: data.father.id,
                                inviteroot: data.user.id
                            }, function (res) {
                                if (res.state == 1) {
                                    data.nextFather = data.father;
                                    getChildInfo(data.father.id);
                                } else {
                                    alert(res.error_msg);
                                }
                                loadingEnd();
                            });
                        }
                    });
                }
            },
            uplevel: function () {
                if (data.levelNow <= 0) {
                    return;
                }
                data.levelNow--;
                // data.levels[data.levelNow] = data.children[index];
                // data.nextFather = data.children[index];
                // data.lastFather = data.father;
                // data.father = data.lastFather;
                getChildInfo(data.levels[data.levelNow].id);
            },
            childrenVal: function (index) {
                return data.children[index].phone +  ' (' + data.children[index].contact + ')';
            }
        }
    });
    data.levels[data.levelNow] = data.user;
    // data.nextFather = data.father;
    // data.lastFather = data.father;
    getChildInfo(data.levels[data.levelNow].id);

    _postAPI('user', 'profile', {token: data.user.token}, function (res) {
        if (res.state == 1) {
            data.user.level = res.data.level;
            _saveSession('user', data.user);
        }
    });
}

function getChildInfo(father_uid) {
    loadingStart();
    _postAPI('user', 'GetChildren', {father_uid: father_uid, token:data.user.token}, function (res) {
        if (res.state == 1) {
            data.children = res.data;
            data.father = data.levels[data.levelNow];
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