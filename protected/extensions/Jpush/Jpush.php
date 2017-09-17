<?php

/**
 * Created by PhpStorm.
 * User: Hapon
 * Date: 16/1/10
 * Time: 下午6:27
 */
class Jpush extends CComponent
{
    public function pushData($params = null)
    {
//        var_dump($_POST);
//        var_dump($_GET);
//        exit();

        if(!$params)
        {
            $params = $_POST;
        }

        if($params['model_type'] != 'Usernotice')
        {
            $dataid = $params['dataid'];
            $tag = $params['tag'];
            $alias = isset($params['selectedIds']) ? $params['selectedIds'] : null;
            $model_type = $params['model_type'];
            $toall = false;

            $model = null;
            $model = new User('search');
            $model -> unsetAttributes();
            $model -> setAttributes(json_decode($params['model'], true));

            $modelsearch = $model -> search(true);
            $itemCount = $modelsearch -> getTotalItemCount();
            $model -> unsetAttributes();
            $itemCount2 = $model -> search() -> getTotalItemCount(false);

//        var_dump($itemCount);
//        var_dump($itemCount2);

            if($itemCount == $itemCount2 && empty($alias))
            {
                $toall = true;
            }
            else if(empty($tag))
            {
                if(empty($alias))
                {
                    $modellist = $modelsearch -> getData();
                    $alias = array();
                    foreach($modellist as $row)
                    {
                        $alias[] = $row -> id;
                    }
//                var_dump($alias);
                }
                $count = count($alias);
                $idlist = '';
                for($i = 0; $i < $count; $i++)
                {
                    $idlist .= $alias[$i];
                    if($i < $count -1)
                        $idlist .= ', ';
                }

                $sql = "select jpushalias from user where id in($idlist) and jpushalias != ''";
                $aliaslist = _querySQL($sql);
                $alias = array();
                foreach($aliaslist as $jpushalias)
                    $alias[] = $jpushalias['jpushalias'];
            }

            if($tag != 'teacher' && $tag != 'student')
                $tag = '';
            else
                $toall = false;

            $tableName = strtolower($model_type);
            $sql = "select id, title, description, url from $tableName where id = $dataid";
            $res = _querySQL($sql);
            $res = $res[0];

            $title = $res['title'];
            $alert = $res['description'];
            $extras = array('url' => $res['url']);
        }
        else
        {
            $alias = array();
            $toall = false;
            if(is_array($params['selectedIds']))
            {
                $count = count($params['selectedIds']);
                $idlist = '';
                for($i = 0; $i < $count; $i++)
                {
                    $idlist .= $params['selectedIds'][$i];
                    if($i < $count -1)
                        $idlist .= ', ';
                }

                $sql = "select jpushalias from user where id in($idlist) and jpushalias != ''";
                $aliaslist = _querySQL($sql);

                foreach($aliaslist as $jpushalias)
                    $alias[] = $jpushalias['jpushalias'];
            }
            else
            {
                $toall = true;
            }

            $title = $params['title'];
            $alert = $params['content'];
            $extras = array('url' => $params['url']);
            $tag = '';
        }

        $pushRes = $this -> pushNotification($alias, $tag, $alert, $title, $extras, $toall);

//        var_dump($pushRes);

        $res = array(
            'state' => 'fail',
            'message' => '未知错误，请刷新页面重试',
        );
//var_dump($pushRes);
        if($pushRes)
        {
            $data = json_decode($pushRes, true);
            if(isset($data['error']))
            {
                $res['message'] = sprintf("推送失败:%s 错误码:%s", $data['error']['message'], $data['error']['code']);
            }
            else
            {
                $res['state'] = 'success';
                $res['message'] = '推送成功!';
            }
        }
        else
        {
            $res['message'] = '推送失败，连接推送服务器超时';
        }

        echo CJSON::encode($res);
        Yii::app()->end();
    }

    public function pushNotification($alias, $tag, $alert, $title, $extras, $toall = false)
    {
        $pushContent = array();
        $pushContent['platform'] = 'all';

        if($toall)
            $pushContent['audience'] = "all";
        else if($alias)
            $pushContent['audience'] = array('alias' => $alias);
        else
            $pushContent['audience'] = array('tag' => array($tag));

        $pushContent['notification']['android'] = array('alert' => $alert, 'title' => $title, 'extras' => $extras, "builder_id" =>1);
        $pushContent['notification']['ios']     = array('alert' => $alert, 'title' => $title, 'extras' => $extras);

        //echo json_encode($pushContent);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT,5);				// 超时时间（秒）
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		// 是否返回结果
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	// 不验证HTTPS证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);	// 不验证HTTPS证书

        curl_setopt($ch, CURLOPT_URL, 'https://api.jpush.cn/v3/push');
        curl_setopt($ch, CURLOPT_HTTPHEADER, App()->params["JPushHeader"]);	//权限验证
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($pushContent));

        $data = curl_exec($ch);
        curl_close($ch);
        //var_dump($data);
        //echo $data;
        return $data;
    }
}