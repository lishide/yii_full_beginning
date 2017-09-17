<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
    'Error',
);
?>
<div id="error_tips">
    <h2>Error <?php echo $code; ?></h2>
    <div class="error_cont">
        <ul style="padding-top:10px;">
            <li><?php echo CHtml::encode($message); ?></li>
        </ul>
    </div>
</div>
