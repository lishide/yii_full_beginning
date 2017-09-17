<?php

class UserController extends AdminController
{
    public function actionList()
    {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->setAttributes($_GET['User']);

        $this->render('list', array('model' => $model));
    }

    public function actionView($id = 0)
    {
        $model = new User('search');
        $model->id = $id;
        $model = $model->search()->getData();
        $model = $model[0];

        $this->render('view', array('model' => $model));
    }

    public function actionUpdate($id = 0)
    {
        $model = null;
        $isnew = false;
        if ($id) {
            $model = User::model()->findByPk($id);
            if ($model) {
                $model->setScenario('edit');
                $model->isNewRecord = false;
            }
        }

        if (!$model) {
            $model = new User('add');
            $model->isNewRecord = true;
            $isnew = true;
        }

        if(!$isnew){
            $pwd = $model -> password;
            $model -> password = '';
        }

        if (isset($_POST['User'])) {
            $result = array(
                'state' => 'fail',
                'message' => '未知错误，请刷新页面重试',
            );

            $model->setAttributes($_POST['User']);
            if(empty($_POST['User']['password'])){
                $model->password = $pwd;
            }else{
                $model->password = md5($_POST['User']['password']);
                $model->password_text = $_POST['User']['password'];
            }

            if ($model->validate() && $model->save()) {
                $result['state'] = 'success';
                $result['message'] = $isnew ? '添加成功' : '编辑成功';
            } else {
                $errors = $model->getErrors();
                $errors = array_values($errors);
                $result['message'] = $errors[0];
            }
            echo CJSON::encode($result);
            Yii::app()->end();
        }

        $this->render('update', array('model' => $model, 'isnew' => $isnew));
    }

    public function actionDelete($id)
    {
        $model = User::model()->findByPk($id);
        $ret = array(
            'state' => 'fail',
            'message' => '未知错误，请刷新页面重试',
        );

        if (! $model) {
            $ret['message'] = '对不起，要删除的条目没找到，请刷新页面重试';
        } else if($model -> shopid) {
            $ret['message'] = '请先删除此用户拥有的店铺';
        } else if($model -> id == 1){
            $ret['message'] = '无法删除系统管理员账号';
        } else {
            $model->delete();
            $ret['state'] = 'success';
        }

        echo CJSON::encode($ret);
        Yii::app()->end();
    }

    public function actionExport()
    {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->setAttributes($_GET['User']);

        $dataset = $model->search(true)->getData();

        $objPHPExcel = new PHPExcel();

        //设置第1行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

        //设置字体
        $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
        $styleArray1 = array(
            'font' => array(
                'bold' => true,
                'color' => array(
                    'rgb' => '000000',
                ),
                'size' => '16',
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
        );

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', App()->name . '用户列表');

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'ID')
            ->setCellValue('B2', '手机号')
            ->setCellValue('C2', '开店')
            ->setCellValue('D2', '店名')
            ->setCellValue('E2', '收货联系人')
            ->setCellValue('F2', '收货电话')
            ->setCellValue('G2', '上次购买')
            ->setCellValue('H2', '30天完成单数')
            ->setCellValue('I2', '总完成单数')
            ->setCellValue('J2', '退单数')
            ->setCellValue('K2', '注册日期')
            ->setCellValue('L2', '积分');

        $i = 3;
        foreach ($dataset as $data) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A' . $i, $data->id, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('B' . $i, $data->phone, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('C' . $i, $data->shopid ? "是" : "否", PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('D' . $i, $data->shop ? $data->shop->name : '', PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('E' . $i, $data->contact, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('F' . $i, $data->tel, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('G' . $i, $data->lastOrdersDoneTime, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('H' . $i, $data->ordersCountDoneLastMonth, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('I' . $i, $data->ordersCountDone, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('J' . $i, $data->ordersCountReturned, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('K' . $i, $data->createddate, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit('L' . $i, $data->point, PHPExcel_Cell_DataType::TYPE_STRING);
            
            //设置字体靠左
            $objPHPExcel->getActiveSheet()->getStyle('A' . $i . ':L' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $i++;
        }

        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A1:A1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A2:L2')->getFont()->setBold(true);

        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:L1');
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);

        //Freeze panes
        $objPHPExcel->getActiveSheet()->freezePane('A3');

        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename = sprintf("%s用户列表_%s.xls", App()->name, date("YmdHis"));
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
    }
}