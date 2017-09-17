<?php
echo "欢迎来到" . Yii::app()->name . "！";
if (!Yii::app()->user->isGuest) {
    echo CHtml::link('[退出登录]', array('/site/logout'), array('class' => 'mr10'));
}
?>