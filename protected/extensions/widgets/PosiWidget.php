<?php
class PosiWidget extends CWidget
{
    public $mark = null;
    public $titleLen = 10;
    public $descLen = 30;
    
    public $class = '';
    public $wpElement = 'ul';
    public $itemTemplate = '<li><a href="{link}" title="{title}"> {title}</a></li>';

    public $timeFormat = 'Y-m-d H:i:s';

    private $ids = array();
    private $table = null;
    private $limit = 0;

    public function init() {
        if (!$this->mark){
            echo CHtml::tag('div', array('id' => $this->getId()), '未指定要显示的推荐位');
            return;
        }

        $position = Position::model()->find('mark=:mark', array(':mark' => $this->mark));

        if (! $position) {
            echo CHtml::tag('div', array('id' => $this->getId()), '指定的推荐位不存在');
            return;
        }

        if (! $this->limit) {
            $this->limit = $position->nums;
        }

        $this->table = $position->tbname;

        $cmd = Yii::app()->db->createCommand();
        $cmd->text = 'select artid from posidata where status=1 and posid=:pid';
        $this->ids = $cmd->queryColumn(array(':pid' => $position->id));

    }

    public function run() {
        if (! count($this->ids)) {
            echo CHtml::tag($this->wpElement, array('class' => $this->class), null, true);
            return;
        }

        $criteria = new CDbCriteria();
        $criteria->condition = 'status = 1';
        $criteria->select = 'id, title, description, keywords, createddate';
        $criteria->addInCondition('id', $this->ids);
        $criteria->limit = $this->limit;
        $criteria->order = 'sort desc, id desc';
        $list = CActiveRecord::model(ucfirst($this->table))->findAll($criteria);
        
        if (!$list) {
            echo CHtml::tag($this->wpElement, array('class' => $this->class), null, true);
        } else {
            $li = '';
            foreach($list as $item) {
                $link = $this->controller->createUrl($this->table.'/show', array('id' => $item->id));

                //$style = $item->color ? ' style="color:'.$item->color.';"' : '';
                $title = cutstr($item->title, $this->titleLen);
                $desc = cutstr($item->description, $this->descLen);
                $keywords=$item->keywords;
                $createddate = date($this->timeFormat, strtotime($item->createddate));

                $li .= str_replace(
                            array('{link}',  '{title}', '{desc}', '{keywords}', '{posttime}'), 
                            array($link, $title, $desc, $keywords, $createddate),
                            $this->itemTemplate
                        );
            }
            echo CHtml::tag($this->wpElement, array('class' => $this->class), $li);
        }
    }
}