<?php
class PositionWidget extends CWidget
{
    public $posid = null;
    public $limit = 0;
    public $showCate = false;
    public $titleLen = 0;

    private $list = null;

    public function init()
    {
        if(!$this->posid)
            throw new CHttpException(404,'未指定要显示的推荐位');
        $this->list = ArticlePosition::model()->findAll(array(
            'condition' => 'position_id=:pid',
            'params' => array(':pid' => $this->posid),
            'limit' => $this->limit,
        ));
    }

    public function run()
    {
        $content = '';
        foreach($this->list as $list){
            $title = $list->article->title;
            if($this->titleLen > 0){
                $length = mb_strlen($title,'utf-8');
                $title = mb_substr($title,0,$this->titleLen,'utf-8');
                if($length > $this->titleLen)
                    $title .= '...';
            }
            $cate = '';
            if($this->showCate)
                $cate = CHtml::tag('a',array('href' => $this->controller->createUrl('article/cate',array('id'=>$list->article->cat->id))),'['.$list->article->cat->name.'] ');
            $link = CHtml::tag('a',array('href' => $this->controller->createUrl('article/show',array('id' => $list->article_id))),$title);
            $content .= CHtml::tag('li',array(),$cate.$link);
        }
        echo $content;
    }
}