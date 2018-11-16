<?php
/**
 * Created by PhpStorm.
 * User: alexc
 * Date: 16.10.18
 * Time: 11:23
 */

namespace App\Modules\Json\Controllers;
use App\Modules\Json\Models\SimpleOrm;
use App\Modules\Json\Models\Json;
use T4\Mvc\Controller;
use T4\Fs\Helpers;

class Index
    extends Controller
{

public  function actionInit(){
    Json::init();
}
    public function actionInsert(){
       $table=new SimpleOrm();
       var_dump($table->insert('documents'));die;
    }

    public function actionNew(){

       $this->data->JsonDoc=Json::getContent('new');  }

    public function actionSave(){
        $data=$this->app->request->post->data;
        $this->data=$data;
        //Json::SaveFile($data);

    }
}