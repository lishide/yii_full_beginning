<?php
$this->cs->registerCssFile(App()->baseUrl . '/vendors/font-awesome/css/font-awesome.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/vendors/amazeui/css/amazeui.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/vendors/amazeui/css/amazeui.tree.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/css/custom/web_user_invite.css'._resourceVer());

$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery-3.1.1.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery.md5.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue-resource.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.tree.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.ie8polyfill.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/config.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/common.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/web_user_invite.js'._resourceVer());

?>

<!DOCTYPE html>
<html>
<head lang="ch">
    <meta charset="UTF-8">
    <title><?php echo App()->name ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
</head>
<body>
<div v-cloak id="app">
    <header class="am-topbar am-topbar-fixed-top">
        <div class="am-container">
            <h1 class="am-topbar-brand">
                <a :href="baseUrl">彩虹云</a>
            </h1>

            <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only"
                    data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only">导航切换</span> <span
                        class="am-icon-bars"></span></button>

            <div class="am-collapse am-topbar-collapse" id="collapse-head">
                <ul class="am-nav am-nav-pills am-topbar-nav">
                    <li><a :href="baseUrl">首页</a></li>
                    <li><a :href="baseUrl+'orders/list'">订单</a></li>
                    <li class="am-dropdown" data-am-dropdown>
                        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
                            我的 <span class="am-icon-caret-down"></span>
                        </a>
                        <ul class="am-dropdown-content">
                            <li><a :href="baseUrl+'user/profile'">账户设置</a></li>
                            <li><a :href="baseUrl+'user/bill'">资金流水</a></li>
                            <li><a :href="baseUrl+'user/invite'">邀请注册</a></li>
                        </ul>
                    </li>
                    
                    <li><a :href="baseUrl+'default/aboutus'">关于我们</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="detail">
        <div class="am-g am-container">
            <br/>
            <h3>请选择节点</h3>

            <div v-if="levelNow >= 1" class="am-g row">
                <button type="button" class="am-btn am-round am-btn-default">{{levels[0].phone}}
                </button>
                <p>↓</p>
            </div>

            <div v-if="levelNow >= 2" class="am-g row">
                <button type="button" class="am-btn am-round am-btn-default">{{levels[1].phone}}
                </button>
                <p>↓</p>
            </div>

            <div class="am-g row">
                <button @click="uplevel" type="button" class="am-btn am-round am-btn-default"
                        :class="{'am-btn-primary' : levelNow > 0}">{{father.phone}}
                </button>
                <p>↓</p>
            </div>

            <div class="am-g row">
                <button v-for="child in children" @click="inviteOrDown($index)" type="button" class="am-btn am-round am-btn-default"
                        :class="{'am-btn-secondary' : levelNow < 2, 'am-btn-success' : !child.phone}">
                    {{child.phone ?
                    childrenVal($index):
                    '邀请'}}
                </button>
            </div>
        </div>
    </div>

    <div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default footer">
        <ul class="am-navbar-nav am-cf am-avg-sm-4">
            <li>
                <a class="footer">
                    <p>© 2017 彩虹云</p>
                </a>
            </li>
        </ul>
    </div>

    <div class="am-modal am-modal-prompt" tabindex="-1" id="modal-invite">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">邀请注册</div>
            <div class="am-modal-bd">
                <input type="text" class="am-modal-prompt-input" value="" placeholder="手机号码">
                <input type="text" class="am-modal-prompt-input" value="" placeholder="登录密码默认123456(无需输入，留空即可）">
                <input type="text" class="am-modal-prompt-input" value="" placeholder="收货人">
                <input type="text" class="am-modal-prompt-input" value="" placeholder="收货地址">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>注册</span>
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="modal-loading">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">正在载入...</div>
            <div class="am-modal-bd">
                <span class="am-icon-spinner am-icon-spin"></span>
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-alert" tabindex="-1" id="modal-alert">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">提示</div>
            <div class="am-modal-bd">
                {{alert}}
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn">确定</span>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    Vue.config.debug = true;
    var data = {
        baseUrl: _CONFIG.baseUrl,
        user: _getSessionObj('user'),
        father: _getSessionObj('user'),
        lastFather: _getSessionObj('user'),
        nextFather: null,
        children: [],
        levels: [],
        levelNow: 0,
        alert: ''
    };

    _userInviteInit();

</script>
</html>