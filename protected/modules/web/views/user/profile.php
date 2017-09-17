<?php
$this->cs->registerCssFile(App()->baseUrl . '/vendors/font-awesome/css/font-awesome.min.css' . _resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/vendors/amazeui/css/amazeui.min.css' . _resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/css/custom/web_user_profile.css' . _resourceVer());

$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery-3.1.1.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery.md5.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue-resource.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.ie8polyfill.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/config.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/common.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/web_user_profile.js' . _resourceVer());

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
            <div class="am-u-sm-centered am-u-lg-uncentered am-u-md-uncentered am-u-sm-12 am-u-md-8 am-u-lg-6">
                <br/>
                <form id="form-profile" class="am-form">
                    <label>手机号: {{profile.phone}}</label>
                    <br/>
                    <label>等级: {{profile.level}} 级</label>
                    <hr/>
                    <label>角色: {{getUserRole(profile.role)}}</label>
                    <button v-if="profile.role == 1" @click="payForStore" type="button"
                            class="am-btn am-btn-primary am-round"
                            style="float: right">成为直营店
                    </button>
                    <hr/>
                    <label>收货人:</label>
                    <input type="text" name="contact" :value="profile.contact">
                    <br/>

                    <label>收货电话:</label>
                    <input type="text" name="tel" :value="profile.tel">
                    <br/>

                    <label>收货地址:</label>
                    <input type="text" name="addr" :value="profile.addr">
                    <br/>

                    <label>支付宝:</label>
                    <input type="text" name="alipay" :value="profile.alipay">
                    <br/>

                    <label>真实姓名:</label>
                    <input type="text" name="realname" :value="profile.realname">
                    <br/>
                </form>
                <button @click="changeProfile" type="button" class="am-btn am-btn-primary am-round"
                        style="float: right">修改资料
                </button>
                <br/>
                <br/>
                <hr/>
                <form id="form-pwd" class="am-form">
                    <label>当前密码:</label>
                    <input type="password" name="oldpwd" value="">
                    <br/>
                    <label>新密码:</label>
                    <input type="text" name="newpwd" value="">
                    <br>
                </form>
                <button @click="changePwd" type="button" class="am-btn am-btn-warning am-round" style="float: right">
                    修改密码
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

    <div class="am-modal am-modal-prompt" tabindex="-1" id="modal-pay">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">支付确认</div>
            <div class="am-modal-bd">
                可用余额:{{user.balance}}元, 使用:{{pay_balance}}元<br/>还需支付:{{(storeOrder.price - pay_balance).toFixed(2)}}元
                <input id="input-balance" type="number" class="am-modal-prompt-input" placeholder="使用余额" step="0.01"
                       min="0">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>支付</span>
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-alert" tabindex="-1" id="modal-pwd-changed">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">彩虹云</div>
            <div class="am-modal-bd">
                成功修改密码, 请重新登录
            </div>
            <div @click="redir" class="am-modal-footer">
                <span class="am-modal-btn">确定</span>
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
        alert: '',
        profile: null,
        pay_balance: 0,
        storeOrder: null
    };

    _userProfileInit();

</script>
</html>