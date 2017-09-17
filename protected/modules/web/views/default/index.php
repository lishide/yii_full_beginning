<?php
$this->cs->registerCssFile(App()->baseUrl . '/vendors/font-awesome/css/font-awesome.min.css' . _resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/vendors/amazeui/css/amazeui.min.css' . _resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/css/custom/web_default_index.css' . _resourceVer());

$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery-3.1.1.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery.md5.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue-resource.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.ie8polyfill.min.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/config.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/common.js' . _resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/web_default_index.js' . _resourceVer());

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
                    <li class="am-active"><a :href="baseUrl">首页</a></li>
                    <li v-if="user"><a :href="baseUrl+'orders/list'">订单</a></li>
                    <li v-if="user" class="am-dropdown" data-am-dropdown>
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

                <div class="am-topbar-right">
                    <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm" @click="toggleLogin"><span
                                class="am-icon-user"></span> {{user?'退出':'登录'}}
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="detail">
        <div class="am-g am-container">
            <h2></h2>
            <div class="am-u-lg-12">
                <div v-for="i in goodsList.length">
                    <div v-if="i % 3 == 0" class="am-g">
                        <div v-for="goods in getGoodsRow(i)" class="am-u-lg-4 am-u-md-4 am-u-sm-12 detail-mb"
                             :class="{'am-u-end' : (i + $index == (goodsList.length - 1))}">
                            <div class="am-u-lg-12 am-u-md-12 am-u-sm-4">
                                <img :src="goods.picpath"
                                     class="am-img-responsive am-img-thumbnail" alt=""/>
                            </div>
                            <div class="am-u-lg-12 am-u-md-12 am-u-sm-8">
                                <div class="am-u-lg-12 am-u-md-12 am-u-sm-8">
                                    <h3 class="title">
                                        {{goods.name}}
                                    </h3>
                                    <p class="price">
                                        ￥{{goods.price}}
                                    </p>
                                    <p class="price">
                                        商品等级：{{goods.level}}
                                    </p>
                                </div>
                                <button v-if="user" @click="buy(goods.id)" type="button"
                                        class="btn-buy am-btn am-btn-warning am-round am-u-sm-4 am-show-sm-only">
                                    购买
                                </button>

                                <div class="detail-p am-u-sm-12">
                                    {{goods.introduction}}
                                </div>
                                <button v-if="user" @click="buy(goods.id)" type="button"
                                        class="btn-buy am-btn am-btn-warning am-round am-u-lg-12 am-u-md-12 am-show-md-up">
                                    购买
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
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

    <div class="am-modal am-modal-prompt" tabindex="-1" id="modal-login">
        <div class="am-modal-dialog">
            <div class="am-modal-hd"><?php echo App()->name ?></div>
            <div class="am-modal-bd">
                <input type="text" class="am-modal-prompt-input" placeholder="用户名">
                <input type="password" class="am-modal-prompt-input" placeholder="密码">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>登录</span>
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-prompt" tabindex="-1" id="modal-buy-confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">购买数量</div>
            <div class="am-modal-bd">
                <input type="number" class="am-modal-prompt-input" value="1" placeholder="购买数量" step="1" min="1">
                <input type="text" class="am-modal-prompt-input" placeholder="订单备注">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>下单</span>
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
        goodsList: null,
        user: _getSessionObj('user'),
        alert: ''
    };

    _defaultIndexInit();

</script>
</html>