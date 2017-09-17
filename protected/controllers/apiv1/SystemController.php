<?php

/**
 * Created by PhpStorm.
 * User: Hapon
 * Date: 16/8/12
 * Time: 上午10:37
 */
class SystemController extends Controller
{
    public function actionGetconfig()
    {
        $keys = array('service_tel', 'about', 'versioncode', 'versionname', 'apkurl');
        $configs = _getConfigFromDB($keys, true);

        _OK(array($configs));
    }

    public function actionCrashlog()
    {
        $p = _getParams([
            ['log', '请提交Log']
        ]);

        $log = new Crashlog();
        $log->log = $p['log'];

        if($log->save()){
            _OK(['msg'=>'log saved']);
        }

        _error(9);
    }

    public function actionGenthumb()
    {
//        _error(8);
        $p = _getParams();
        $startID = $p['startid'];
        $endID = $p['endid'];

        $startTime = time();

        $picList = Pic::model()->findAll([
            'condition' => sprintf('t.id >= %d and t.id <= %d', $startID, $endID),
            'order' => 't.id asc'
        ]);

        $count = count($picList);
        for ($i = 0; $i < $count; $i++) {
            $pic = $picList[$i];
            $pic->checkFile();
            $pic->save();
        }

        $endTime = time();

        echo "处理从 $startID 到 $endID ";
        echo "耗时" . ($endTime - $startTime) . "秒";

        if ($picList[$count - 1]->id == $endID) {
            $location = sprintf('http://dhb.myshiningstone.com/apiv1/system/genthumb/startid/%d/endid/%d', $endID + 1, $endID + 1 + $endID - $startID);
            exit("<script type='text/javascript'>location.href='$location'</script>");
        }
    }
}