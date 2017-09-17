<div id="error_tips" style="width:270px; margin-top:100px;">
    <h2><?php echo App()->name; ?> 后台管理登录</h2>

    <div class="pop_cont pop_table">
        <table width="100%">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'adminLogin',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            )); ?>
            <tr>
                <th><?php echo $form->labelEx($model, 'username'); ?></th>
                <td><?php echo $form->textField($model, 'username', array('class' => 'input', 'placeholder' => '请输入管理账号')); ?></td>
            </tr>

            <tr>
                <th><?php echo $form->labelEx($model, 'password'); ?></th>
                <td><?php echo $form->passwordField($model, 'password', array('class' => 'input', 'placeholder' => '请输入管理密码')); ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo $form->error($model, 'username'); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </td>
            </tr>

            <tr>
                <td colspan="2" align="center">
                    <?php echo CHtml::submitButton('立即登录', array('class' => 'btn btn_submit mr10')); ?>
                    <?php echo CHtml::link('返回首页', array('/site/index'), array('class' => 'btn btn_success')); ?>
                </td>
            </tr>
            <?php $this->endWidget(); ?>
        </table>
    </div>
</div>
    
