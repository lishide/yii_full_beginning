<div class="nav">
    <ul class="cc">
        <li><?php echo CHtml::link('用户列表', array($this->id. '/list'));?></li>
        <li class="current"><?php echo CHtml::link($model->isNewRecord ? '新增用户' : '编辑用户', array($this->id. '/update/id/'. $model->id));?></li>
    </ul>
</div>

<?php $form=$this->beginWidget('CActiveForm',array(
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'J_ajaxForm')
)); ?>

<div class="h_a"><?php echo $model->isNewRecord ? '新增' : '编辑';?>用户</div>

<div class="table_full">
    <table width="100%">
        <colgroup>
            <col class="th">
            <col width="400">
            <col>
        </colgroup>
        <tbody>

        <tr>
            <th><?php echo $form->labelEx($model, 'username'); ?></th>
            <td>
                <?php echo $form->textField($model, 'username', array('class' => 'input length_6'));?>
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'password'); ?></th>
            <td>
                <?php echo $form->textField($model, 'password', array('class' => 'input length_6'));?> 不修改时请留空
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'phone'); ?></th>
            <td>
                <?php echo $form->textField($model, 'phone', array('class' => 'input length_6'));?>
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'point'); ?></th>
            <td>
                <?php echo $form->textField($model, 'point', array('class' => 'input length_6'));?>
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'contact'); ?></th>
            <td>
                <?php echo $form->textField($model, 'contact', array('class' => 'input length_6'));?>
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'tel'); ?></th>
            <td>
                <?php echo $form->textField($model, 'tel', array('class' => 'input length_6'));?>
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model, 'addr'); ?></th>
            <td>
                <?php echo $form->textField($model, 'addr', array('class' => 'input length_6'));?>
            </td>
        </tr>

        </tbody>
    </table>
</div>


<div class="btn_wrap">
    <div class="btn_wrap_pd">
        <?php echo CHtml::submitButton('提交',array('class' => 'btn btn_submit mr10 J_ajax_submit_btn')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>