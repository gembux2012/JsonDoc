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
            'order' => 'published DESC',
            'offset'=> ($page-1)*self::PAGE_SIZE,
            'limit'=> self::PAGE_SIZE]);
        $this->data->items=$requare;

    }

    public function actionLogin($login){
        if (null !== $login) {
            try {
                $identity = new Identity();
                $user = $identity->authenticate($login);

            } catch (\App\Components\Auth\MultiException $e) {

                $this->data->err=$e;
            }

        }
    }




}