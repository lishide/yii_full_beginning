<div class="nav">
    <ul class="cc">
        <li class="current">
            <?php echo CHtml::link('修改密码', array('/admin/default/repass'));?>
        </li>
    </ul>
</div>
<?php $form=$this->beginWidget('CActiveForm',array(
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'J_ajaxForm','data-role' => 'type')
)); ?>
<div class="h_a">修改密码</div>
<div class="table_full">
    <table width="100%">
        <tbody>
        <tr>
            <th width="100px">账号</th>
            <td><?php echo $model->username;?></td>
        </tr>
        <tr>
            <th><?php echo $form->labelEx($model, 'opassword');?></th>
            <td>
                <?php echo $form->passwordField($model, 'opassword', array('class' => 'input length_3'));?>
            </td>
        </tr>
        <tr>
            <th><?php echo $form->labelEx($model, 'password');?></th>
            <td>
                <?php echo $form->passwordField($model, 'password', array('class' => 'input length_3'));?>
            </td>
        </tr>
        <tr>
            <th><?php echo $form->labelEx($model, 'repassword');?></th>
            <td>
                <?php echo $form->passwordField($model, 'repassword', array('class' => 'input length_3'));?>
            </td>
        </tr>
        </tbody>
    </table>
</div>


<div>
    <?php echo CHtml::submitButton('提交',array('class' => 'btn btn_submit fr mr10 J_ajax_submit_btn')); ?>
</div>
<?php $this->endWidget(); ?>
