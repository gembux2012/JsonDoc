<?php

namespace App\Controllers;
use App\Components\Auth\Identity;
use App\Components\Controller;
use App\Models\Document;
use T4\Fs\Helpers;
use App\Models;



class Index
    extends Controller
{
    const PAGE_SIZE = 20;

        public function actionDefault($page = 1)
    {
        $file = file_get_contents(Helpers::getRealPath('/jsondoc/document-list-response.json'));
        $requare=json_decode($file,TRUE);
        $requare["properties"]['pagination']["required"][0]['page']=$page;
        $requare["properties"]['pagination']["required"][0]['perPage']= self::PAGE_SIZE;
        $requare["properties"]['pagination']["required"][0]['total']=Document::countAll();
        $requare["properties"]['document']['items']['json'][]=Document::findAll([
            //'order' => 'published DESC',
            'offset'=> ($page-1)*self::PAGE_SIZE,
            'limit'=> self::PAGE_SIZE]);
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
        if (!empty($this->app->request->post->id)) {
            $item = Document::findByPK($this->app->request->post->id);
        } else {
            $item = new Document();
        }
        $item->fill($this->app->request->post);
        $item->save();

    }


    public function action403(){
        $this->data->err='403';
    }



}