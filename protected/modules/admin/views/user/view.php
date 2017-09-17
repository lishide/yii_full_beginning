<div class="nav">
    <ul class="cc">
        <li><?php echo CHtml::link('用户列表', array($this->id . '/list')); ?></li>
        <li class="current"><?php echo CHtml::link('用户详情', array($this->id . '/view/id/' . $model->id)); ?></li>
    </ul>
</div>

<?php $form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => false,
    'enableClientValidation' => false,
    'htmlOptions' => array('class' => 'J_ajaxForm')
)); ?>

<div class="table_full">
    <table width="100%">
        <colgroup>
            <col class="th">
            <col width="400">
            <col>
        </colgroup>
        <tbody>

        <tr>
            <th><?php echo $form->labelEx($model, 'id'); ?></th>
            <td><?php echo $model->id; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'phone'); ?></th>
            <td><?php echo $model->phone; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'point'); ?></th>
            <td><?php echo $model->point; ?></td>
        </tr>

        <tr>
            <th>开店</th>
            <td><?php echo $model->shopid ? "是" : "否"; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'shop.name'); ?></th>
            <td><?php echo $model->shop ? $model->shop->name : ''; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'password_text'); ?></th>
            <td><?php echo $model->password_text; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'avatar'); ?></th>
            <td><img src=" <?php echo $url = _getPicURLByModel($model->avatarModel); ?>" style="width: 50pt"/></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'contact'); ?></th>
            <td><?php echo $model->contact; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'tel'); ?></th>
            <td><?php echo $model->tel; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'addr'); ?></th>
            <td><?php echo $model->addr; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'lastOrdersDoneTime'); ?></th>
            <td><?php echo $model->lastOrdersDoneTime; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'ordersCountDoneLastMonth'); ?></th>
            <td><?php echo $model->ordersCountDoneLastMonth; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'ordersCountDone'); ?></th>
            <td><?php echo $model->ordersCountDone; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'ordersCountReturned'); ?></th>
            <td><?php echo $model->ordersCountReturned; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'lastlogin'); ?></th>
            <td><?php echo $model->lastlogin; ?></td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'createddate'); ?></th>
            <td><?php echo $model->createddate; ?></td>
        </tr>

        </tbody>
    </table>
</div>

<?php $this->endWidget(); ?>

<?php
$this->cs->registerCss('required', "span.required {display: none}");
?>
