<div class="nav">
    <ul class="cc">
        <li class="current">
            <?php echo CHtml::link('编辑关于页面', array($this->id. '/aboutupdate'));?>
        </li>
    </ul>
</div>

<?php $form=$this->beginWidget('CActiveForm',array(
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>false,
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
            <th>标题</th>
            <td>
                关于我们
            </td>
        </tr>

        <tr>
            <th><?php echo $form->labelEx($model,'value'); ?></th>
            <td>
                <?php
                $this->widget('ext.baiduUeditor.UeditorWidget',
                    array(
                        'id'=>'value',//容器的id 唯一的[必须配置]
                        'name'=>'Configs[value]',//post到后台接收的name [必须配置]
                        'content'=> $model->value,//初始化内容 [可选的]
                        'height' => '400px',
                        'width' => '800px',

                        //配置选项，[可选的]
                        //将ueditor的配置项以数组键值对的方式传入,具体查看ueditor.config.js
                        //不要配置serverUrl(即使配置也会被覆盖)程序会自动处理后端url
                        'config'=>array(
                            'wordCount' => false,
                            'elementPathEnabled' => false,
                            'autoHeightEnabled' => true,
                            'toolbars' => array(array('fullscreen', 'source', 'drafts', '|', 'undo', 'redo', '|',
                                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript',
                                'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote',
                                'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist',
                                'insertunorderedlist', 'selectall', 'cleardoc', '|', 'rowspacingtop',
                                'rowspacingbottom', 'lineheight', 'emotion',
                                'fontfamily', 'fontsize', '|', 'indent', '|', 'justifyleft', 'justifycenter',
                                'justifyright', 'justifyjustify', '|', 'link', 'unlink', 'anchor', 'map', '|','imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                                'simpleupload', 'inserttable', 'deletetable', 'insertparagraphbeforetable',
                                'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright',
                                'mergedown', 'splittocells', 'splittorows', 'splittocols'
                            )),
                            'lang'=>'zh-cn'
                        )
                    )
                );
                ?>

                <?php
                ?>
            </td>
            <td></td>
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