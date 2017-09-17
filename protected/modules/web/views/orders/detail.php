<?php
$this->cs->registerCssFile(App()->baseUrl . '/vendors/font-awesome/css/font-awesome.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/vendors/amazeui/css/amazeui.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/css/custom/web_orders_detail.css'._resourceVer());

$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery-3.1.1.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery.md5.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue-resource.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.ie8polyfill.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/config.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/common.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/web_orders_detail.js'._resourceVer());

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
                    <li class="am-active"><a :href="baseUrl+'orders/list'">订单</a></li>
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
                <table id="detail-list" class="am-table am-table-striped am-table-hover">
                    <thead>
                    <tr>
                        <th>订单号</th>
                        <th>{{sn}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>收货人</td>
                        <td>{{order.contact}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>收货电话</td>
                        <td>{{order.tel}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>送货地址</td>
                        <td>{{order.addr}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>商品名</td>
                        <td>{{order.goods[0].name}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>商品单价</td>
                        <td>{{order.goods[0].price}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>商品数量</td>
                        <td>{{order.goods[0].amount}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>订单总价</td>
                        <td>{{order.price}}</td>
                        <td></td>
                    </tr>
                    <tr v-if="order.use_balance != 0">
                        <td>使用余额</td>
                        <td>{{order.use_balance}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>订单备注</td>
                        <td>{{order.goods[0].msg}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>订单状态</td>
                        <td>{{getOrderStatus(order.status)}}</td>
                        <td>
                            <button v-if="order.status == 1 " @click="pay" type="button" class="am-btn am-btn-primary">
                                立即支付
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>下单时间</td>
                        <td>{{order.createtime}}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
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
                可用余额:{{user.balance}}元, 使用:{{pay_balance}}元<br/>还需支付:{{(order.price - pay_balance).toFixed(2)}}元
                <input id="input-balance" type="number" class="am-modal-prompt-input" placeholder="使用余额" step="0.01" min="0">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>支付</span>
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
        sn: '<?php echo $sn?>',
        baseUrl: _CONFIG.baseUrl,
        user: _getSessionObj('user'),
        alert: '',
        order: null,
        pay_balance: 0
    };

    _ordersDetailInit();

</script>
</html>