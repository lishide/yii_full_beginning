<?php

class ApidocController extends Controller
{
    public function actionListerrorcode()
    {
        $errors = __getErrorMsg(null);
        foreach ($errors as $key => $value) {
            $res[] = array('code' => $key.'', 'text' => $value[0], 'text_ch' => $value[1]);
        }

        $callback = isset($_GET['callback']) ? trim($_GET['callback']) : '';
        $json = CJSON::encode(array('errorcode_app' => $res));
        $return = $callback ? $callback . '(' . $json .')' : $json;

        header('Content-Type: application/json; charset=utf-8');
        exit($return);
    }
}

?>