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

        public function actionDefault($page = 1)
    {
        $documents = [];
        $record = [];
        $data=Document::findAll([
            //'order' => 'published DESC',
            'offset'=> ($page-1)*self::PAGE_SIZE,
            'limit'=> self::PAGE_SIZE]);

        foreach ($data as $key_0 => $value_0) {
            $data[$key_0] = $value_0;
            foreach ($value_0 as $key => $value) {
                $documents[$key_0][$key] = $value;
            }
        }

        $file = file_get_contents(Helpers::getRealPath('/jsondoc/document-list-response.json'));

        $requare=json_decode($file,TRUE);
        $requare["properties"]['pagination']["required"][0]['page']=$page;
        $requare["properties"]['pagination']["required"][0]['perPage']= self::PAGE_SIZE;
        $requare["properties"]['pagination']["required"][0]['total']=Document::countAll();
        $requare["properties"]['document']['items']['json']=$documents;
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
            $item->createAt=time()+abs($this->app->request->post->tz);
            $item->guid=Document::getGUID();
        }
        if(!empty($this->app->request->post->published))
            $item->published=true;


        $item->fill($this->app->request->post);
        $item->__user_id=$this->app->user->Pk;
        $item->save();
        $this->redirect('/');

    }


    public function action403(){
        $this->data->err='403';
    }



}