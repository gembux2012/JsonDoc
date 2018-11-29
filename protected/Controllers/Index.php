<?php

namespace App\Controllers;
use App\Components\Auth\Identity;
use App\Components\Controller;
use App\Models\Document;
use T4\Core\Std;
use T4\Fs\Helpers;
use App\Models;



class Index
    extends Controller
{
    const PAGE_SIZE = 5;

   public function actionDefault(){

    }


    public function actionShablonList(){

    }

        public function actionList($page = 1)
    {
        $documents = [];
        $record = [];
        $data=Document::findAll([
            //'order' => 'published DESC',
            'offset'=> ($page-1)*self::PAGE_SIZE,
            'limit'=> self::PAGE_SIZE]);

        foreach ($data as $key_0 => $value_0) {
            $data[$key_0] = $value_0;
            $documents[$key_0]['createAT']=$data[$key_0]['createAT'];
            $documents[$key_0]['modifyAT']=$data[$key_0]['modifyAT'];
            $documents[$key_0]['guid']=$data[$key_0]['guid'];
            $documents[$key_0]['payload']=$data[$key_0]['payload'];
            $documents[$key_0]['status']=$data[$key_0]['status'];
            $documents[$key_0]['Pk']=$data[$key_0]['Pk'];
         //   foreach ($value_0 as $key => $value) {
       //         if ($key == 'paylod')
//                $documents[$key_0][$key] = $value->toArray();

           //     $documents[$key_0][$key] = $value;

            //}
        }

      //  $documents=$data->toArray();
       // $this->data->documents=$documents;
        $file = file_get_contents(Helpers::getRealPath('/jsondoc/document-list-response.json'));

        $requare=json_decode($file,TRUE);
        $requare["properties"]['pagination']["required"][0]['page']=$page;
        $requare["properties"]['pagination']["required"][0]['perPage']= self::PAGE_SIZE;
        $requare["properties"]['pagination']["required"][0]['total']=Document::countAll();
        $requare["properties"]['document']['items']['document']=$documents;
        $this->data->items=$requare;

    }

    public function actionLogin($login){
        if (null !== $login) {
            try {
                $identity = new Identity();
                $user = $identity->authenticate($login);

            } catch (\T4\Auth\Exception $e) {

                self::$user['err']=$e->getMessage();
                $this->data->user=self::$user;

            }

        }
    }

    public function actionEdit($id = null)
    {
        if (null === $id || 'new' == $id) {
            $this->data->item = new Document();
        } else {
            $this->data->item = Document::findByPK($id);
        }
    }

    public function actionSave()
    {
        date_default_timezone_set("UTC");
        if (!empty($this->app->request->post->id)) {
            $item = Document::findByPK($this->app->request->post->id);
            $item->modifyAt=time()+abs($this->app->request->post->tz);
        } else {
            $item = new Document();
            $item->createat=time()+abs($this->app->request->post->tz);
            $item->guid=Document::getGUID();
        }
        if(!empty($this->app->request->post->published))
            $item->published=true;


        $item->fill($this->app->request->post);
        $item->__user_id=$this->app->user->Pk;
        $item->save();


    }


    public function action403(){
        $this->data->err='403';
    }



}