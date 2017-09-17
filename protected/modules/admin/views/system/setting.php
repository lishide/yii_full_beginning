<div class="nav">
    <ul class="cc">
        <li class="current">
            <?php echo CHtml::link('系统参数设置', array($this->id. '/setting'));?>
        </li>
    </ul>
</div>

<?php $form=$this->beginWidget('CActiveForm',array(
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
    'htmlOptions' => array('class' => 'J_ajaxForm', 'data-role' => 'setting')
)); ?>

<?php foreach($configs as $c):?>
    <div class="h_a"><?php echo $c['title'];?></div>
    <div class="table_full">
        <table width="100%">
            <colgroup>
                <col class="th">
                <col width="400">
                <col>
            </colgroup>
            <tbody>
                <?php foreach($c['options'] as $key => $item):?>
                <?php if (is_array($item)) :?>
                <tr>
                    <th><?php echo CHtml::label($item['title'], 'Configs_'.$key.'_value'); ?></th>
                    <td>
                        <?php 
                        if ($item['type'] == 'text')
                            echo $form->textArea($models[$key], "[$key]value", array('class' => $item['class'], 'style' => (isset($item['style']) ? $item['style'] : '')));
                        elseif ($item['type'] == 'list')
                            echo $form->dropDownList($models[$key], "[$key]value", $item['data'], array('class' => $item['class']));
                        elseif ($item['type'] == 'number')
                            echo $form->numberField($models[$key], "[$key]value", array('class' => $item['class']));
                        elseif ($item['type'] == 'password')
                            echo $form->passwordField($models[$key], "[$key]value", array('class' => $item['class']));
                        else
                            echo $form->textField($models[$key], "[$key]value", array('class' => $item['class']));
                        ?>
                        <?php if (isset($item['unit'])) echo $item['unit'];?>
                    </td>
                    <td>
                        <?php echo $form->error($models[$key], "[$key]value"); ?>
                        <?php if (isset($item['tips'])):?>
                        <div class="cc"><?php echo $item['tips'];?></div>
                        <?php endif;?>
                    </td>
                </tr>
                <?php else:?>
                <tr>
                    <th><?php echo $form->labelEx($model, 'name'); ?></th>
                    <td>
                        <?php echo $form->textField($model, 'name', array('class' => 'input length_3'));?>
                        <?php echo $form->error($model, 'name'); ?>
                    </td>
                    <td>
                        <?php echo $form->error($model, "[$key]value"); ?>
                        <div class="gray"><?php echo $c['options'][$key]['tips'];?></div>
                    </td>
                </tr>
                <?php endif;?>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
<?php endforeach;?>

    <div class="btn_wrap">
        <div class="btn_wrap_pd">
            <?php echo CHtml::submitButton('提交',array('class' => 'btn btn_submit mr10 J_ajax_submit_btn')); ?>
        </div>
    </div>


<?php $this->endWidget(); ?>