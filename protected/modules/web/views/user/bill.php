<?php
$this->cs->registerCssFile(App()->baseUrl . '/vendors/font-awesome/css/font-awesome.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/vendors/amazeui/css/amazeui.min.css'._resourceVer());
$this->cs->registerCssFile(App()->baseUrl . '/css/custom/web_user_bill.css'._resourceVer());

$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery-3.1.1.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/jquery/jquery.md5.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/vue/vue-resource.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/vendors/amazeui/js/amazeui.ie8polyfill.min.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/config.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/common.js'._resourceVer());
$this->cs->registerScriptFile(App()->baseUrl . '/js/custom/web_user_bill.js'._resourceVer());

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
            <div class="am-u-sm-centered am-u-lg-uncentered am-u-md-uncentered am-u-sm-12 am-u-md-9 am-u-lg-8">
                <p id="title">账户余额</p>
                <h2>￥{{profile.balance}}</h2>
                <button :class="{'am-disabled' : profile.balance * 1 == 0}" @click="withdraw"
                        type="button" class="am-btn am-btn-success am-round">提现
                </button>
                <button :class="{'am-disabled' : profile.balance * 1 == 0}" @click="transfer"
                        type="button" class="am-btn am-btn-warning am-round">转账
                </button>
                <div data-am-widget="tabs" class="am-tabs am-tabs-d2">
                    <ul class="am-tabs-nav am-cf">
                        <li class="am-active"><a href="[data-tab-panel-0]">余额变动</a></li>
                        <li class=""><a href="[data-tab-panel-1]">提现历史</a></li>
                    </ul>
                    <div class="am-tabs-bd" >
                        <div data-tab-panel-0 class="am-tab-panel am-active">
                            <table class="am-table am-table-striped am-table-hover detail-list">
                                <thead>
                                <tr>
                                    <th>订单</th>
                                    <th>变动</th>
                                    <th>余额</th>
                                    <th>原因</th>
                                    <th>交易对方</th>
                                    <th>时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="log in logs">
                                    <td>{{log.ordersn}}</td>
                                    <td>{{log.moneychange}}</td>
                                    <td>{{log.moneyleft}}</td>
                                    <td>{{getTradeType(log.tradetype)}}</td>
                                    <td>{{log.tradepeer}}</td>
                                    <td>{{log.createtime}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div data-tab-panel-1 class="am-tab-panel ">
                            <table class="am-table am-table-striped am-table-hover detail-list">
                                <thead>
                                <tr>
                                    <th>金额</th>
                                    <th>支付宝</th>
                                    <th>姓名</th>
                                    <th>状态</th>
                                    <th>备注</th>
                                    <th>申请时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="w in withdrawlog">
                                    <td>{{w.amount}}</td>
                                    <td>{{w.alipay}}</td>
                                    <td>{{w.realname}}</td>
                                    <td>{{getWithdrawStatus(w.status)}}</td>
                                    <td>{{w.reason}}</td>
                                    <td>{{w.createtime}}</td>
                                </tr>
                                </tbody>
                            </table>
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

    <div class="am-modal am-modal-prompt" tabindex="-1" id="modal-withdraw-confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">提现申请</div>
            <div class="am-modal-bd">
                费率 <?php echo _getConfigFromDB('withdraw_charge', true)['withdraw_charge']* 100?>%
                <input type="text" class="am-modal-prompt-input" :value="profile.alipay" placeholder="支付宝账号">
                <input type="text" class="am-modal-prompt-input" :value="profile.realname" placeholder="真实姓名">
                <input type="number" class="am-modal-prompt-input" value="" placeholder="提现金额" step="0.01" min="0"
                       :max="profile.balance">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>提交</span>
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-prompt" tabindex="-1" id="modal-transfer-confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">余额转账</div>
            <div class="am-modal-bd">
                <input type="text" class="am-modal-prompt-input" value="" placeholder="对方手机号">
                <input type="number" class="am-modal-prompt-input" value="" placeholder="转账金额" step="0.01" min="0">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>转账</span>
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
        logs: null,
        profile: null,
        withdrawlog:null
    };

    _userBillInit();

</script>
</html>