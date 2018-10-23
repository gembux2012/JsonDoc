<?php
/**
 * Created by PhpStorm.
 * User: alexc
 * Date: 16.10.18
 * Time: 11:23
 */

namespace App\Modules\Json\Controllers;

use App\Modules\Json\Models\Json;
use T4\Mvc\Controller;

class Index
    extends Controller
{
    public function actionDefault(){}

    public function actionNew(){
       $this->data->JsonDoc=Json::getContent('new');  }

    public function actionSave(){
        $data=$this->app->request->post->data;
        $this->data->JsonDoc=$data;
        //Json::SaveFile($data);

    }
}