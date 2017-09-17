<?php
class MyButtonColumn extends CButtonColumn
{
    public $template='{view} {update} {refuse} {delete} {pass} {done} {void}';
    public $updateDialog = false;
    public $viewDialog = false;
    public $deleteMessage = '';
    public $viewButtonOptions = null;

    public $showMenu = false;

    public $passButtonLabel = '审核通过';
    public $passButtonImageUrl;
    public $passButtonUrl = 'Yii::app()->controller->createUrl("pass",array("id"=>$data->primaryKey))';
    public $passButtonOptions=array('class'=>'mr5 J_ajax_del', 'data-msg' => '确定要通过这条收购申请吗？');

    public $doneButtonLabel = '确认成交';
    public $doneButtonImageUrl;
    public $doneButtonUrl = 'Yii::app()->controller->createUrl("done",array("id"=>$data->primaryKey))';
    public $doneButtonOptions=array('class'=>'J_ajax_del', 'data-msg' => '确定该收购成交吗？');

    public $refuseButtonLabel = '拒绝申请';
    public $refuseButtonImageUrl;
    public $refuseButtonUrl = 'Yii::app()->controller->createUrl("refuse",array("id"=>$data->primaryKey))';
    public $refuseButtonOptions = array('class'=>'mr5 J_ajax_del', 'data-msg' => '确定要拒绝这条收购申请吗？');

    public $voidButtonLabel = '收购作废';
    public $voidButtonImageUrl;
    public $voidButtonUrl = 'Yii::app()->controller->createUrl("void",array("id"=>$data->primaryKey))';
    public $voidButtonOptions = array('class'=>'mr5 J_ajax_del', 'data-msg' => '确定要作废该收购吗？');


    protected function initDefaultButtons()
    {
        if($this->viewButtonLabel===null)
            $this->viewButtonLabel= '查看';
        if($this->updateButtonLabel===null)
            $this->updateButtonLabel= '编辑';
        if($this->deleteButtonLabel===null)
            $this->deleteButtonLabel= '删除';


        $this->deleteButtonOptions = array('class' => 'mr5 J_ajax_del', 'data-msg' => $this->deleteMessage);
        $this->updateButtonOptions = array('class' => 'mr5');
        if ($this->updateDialog)
            $this->updateButtonOptions = array('class' => 'mr5');

        if ($this->viewButtonOptions === null)
            $this->viewButtonOptions = array('class' => 'mr5');
        if ($this->viewDialog)
            $this->viewButtonOptions = array('class' => 'mr5 J_dialog');

        foreach(array('view', 'update', 'refuse', 'delete', 'pass', 'done', 'void') as $id)
        {
            $button = array(
                'label'=> $this->showMenu ? $this->{$id.'ButtonLabel'} : '['.$this->{$id.'ButtonLabel'}.']',
                'url'=>$this->{$id.'ButtonUrl'},
                'imageUrl'=>$this->{$id.'ButtonImageUrl'},
                'options'=>$this->{$id.'ButtonOptions'},
            );
            if(isset($this->buttons[$id]))
                $this->buttons[$id]=array_merge($button,$this->buttons[$id]);
            else
                $this->buttons[$id]=$button;
        }

        if(!isset($this->buttons['delete']['click']))
        {
            if(is_string($this->deleteConfirmation))
                $confirmation="if(!confirm(".CJavaScript::encode($this->deleteConfirmation).")) return false;";
            else
                $confirmation='';

            if(Yii::app()->request->enableCsrfValidation)
            {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            }
            else
                $csrf = '';

            if($this->afterDelete===null)
                $this->afterDelete='function(){}';

            $this->buttons['delete']['click']=<<<EOD
function() {
    $confirmation

}
EOD;
        }
    }

    protected function renderDataCellContent($row,$data)
    {
        $tr=array();
        ob_start();
        foreach($this->buttons as $id=>$button)
        {   
            $this->renderButton($id,$button,$row,$data);
            $tr['{'.$id.'}'] = $this->showMenu ? '<li>' . ob_get_contents() . '</li>' : ob_get_contents();
            ob_clean();
        } 
        ob_end_clean();

        // if (strstr($this->template, '$data'))
        //     $this->template = $this->evaluateExpression($this->template, array('data' => $data));
        // echo $this->template;
        echo $this->showMenu ? '<div class="J_menu"><ul>'. strtr($this->template, $tr) . '</ul></div>' : strtr($this->template, $tr);
    }

    protected function renderButton($id,$button,$row,$data)
    {
        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'],array('row'=>$row,'data'=>$data)))
            return;
        $label=isset($button['label']) ? $button['label'] : $id;
        $url=isset($button['url']) ? $this->evaluateExpression($button['url'],array('data'=>$data,'row'=>$row)) : '#';
        $options=isset($button['options']) ? $button['options'] : array();
        if(!isset($options['title']))
            $options['title']=$label;

        if (isset($options['data-msg']) && strstr($options['data-msg'], '$data'))
            $options['data-msg'] = $this->evaluateExpression($options['data-msg'], array('data' => $data));

        if(isset($button['imageUrl']) && is_string($button['imageUrl']))
            echo CHtml::link(CHtml::image($button['imageUrl'],$label),$url,$options);
        else
            echo CHtml::link($label,$url,$options);
    }
}
