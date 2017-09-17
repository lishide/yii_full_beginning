<div class="nav">
    <ul class="cc">
        <li class="current">
            <?php echo CHtml::link('用户列表', array($this->id . '/list')); ?>
        </li>
    </ul>
</div>

<div class="h_a">搜索 (共<span><?php echo $totalItemCount = $model->search()->getTotalItemCount(); ?></span> 条结果)</div>
<div class="search_type cc mb10">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'htmlOptions' => array(
            'class' => 'search_form',
        ),
    )); ?>

    <?php

    echo $form->labelEx($model, 'phone');
    echo $form->textField($model, 'phone', array('class' => 'input length_2 mr10'));
    
    echo $form->labelEx($model, 'point');
    echo $form->textField($model, 'point', array('class' => 'input length_2 mr10'));

    echo $form->labelEx($model, 'contact');
    echo $form->textField($model, 'contact', array('class' => 'input length_1 mr10'));

    echo $form->labelEx($model, 'tel');
    echo $form->textField($model, 'tel', array('class' => 'input length_2 mr10'));

    echo '开店';
    echo $form->dropDownList($model, 'shopowner', App()->params['boolean'], array(
        'empty' => '不限', 'class' => 'mr10'));

    echo $form->labelEx($model, 'shopname');
    echo $form->textField($model, 'shopname', array('class' => 'input length_2 mr10'));

    echo "<br/><br/>";

    echo $form->labelEx($model, 'ordersCountDoneLastMonth');
    echo $form->numberField($model, 'ordersCountDoneLastMonthMin', array('class' => 'input length_1 mr10'));
    echo '-&nbsp&nbsp';
    echo $form->numberField($model, 'ordersCountDoneLastMonthMax', array('class' => 'input length_1 mr10'));

    echo $form->labelEx($model, 'ordersCountDone');
    echo $form->numberField($model, 'ordersCountDoneMin', array('class' => 'input length_1 mr10'));
    echo '-&nbsp&nbsp';
    echo $form->numberField($model, 'ordersCountDoneMax', array('class' => 'input length_1 mr10'));

    echo $form->labelEx($model, 'ordersCountReturned');
    echo $form->numberField($model, 'ordersCountReturnedMin', array('class' => 'input length_1 mr10'));
    echo '-&nbsp&nbsp';
    echo $form->numberField($model, 'ordersCountReturnedMax', array('class' => 'input length_1 mr10'));

    ?>

    <?php echo CHtml::submitButton('提交查询', array('id' => 'btnSubmit', 'submit' => array('user/list/'), 'class' => 'btn')); ?>

    <?php echo CHtml::button('导出查询结果', array(
        'submit' => array('user/export/'),
        'confirm' => ($totalItemCount > 1000) ? sprintf("确定导出 %d 条数据吗? \n数据量大时可能无法导出", $totalItemCount) : null,
        'class' => 'btn',)); ?>

    <?php $this->endWidget(); ?>

</div>

<div class="table_list">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $model->search(),
        'enableSorting' => true,
        'ajaxUpdate' => false,

        'id' => 'data_list',
        'template' => '{items}{summary}{pager}',
        'emptyText' => '没有数据',
        'htmlOptions' => array('class' => ''),
        'pager' => array(
            'header' => '',
            'firstPageLabel' => '首页',
            'lastPageLabel' => '末页',
            'prevPageLabel' => '上一页',
            'nextPageLabel' => '下一页',
            'maxButtonCount' => 9,
            'cssFile' => Yii::app()->baseUrl . '/css/admin_pager.css',
        ),
        'columns' => array(
            'id',
            'phone',
            'point',
            array(
                'name' => 'shopid',
                'header' => '开店',
                'value' => '$data->shopid?"是":"否"',
            ),
            array(
                'name' => 'shop.name',
                'header' => '店名',
            ),
            'contact',
            'tel',
            'lastOrdersDoneTime',
            array(
                'name' => 'ordersCountDoneLastMonth',
                'type' => 'html',
                'value' => '$data->ordersCountDoneLastMonth>0?$data->ordersCountDoneLastMonth:("<p class=\"p_red\">".$data->ordersCountDoneLastMonth."</p>")',
            ),
            'ordersCountDone',
            'ordersCountReturned',
            'createddate',
            array(
                'header' => '操作',
                'class' => 'MyButtonColumn',
                'updateDialog' => false,
                'template' => "{view} {update} {delete}",
            ),
        )
    )); ?>
</div>

<?php
$this->cs->registerCss('required', "
    span.required {display: none}
")
?>