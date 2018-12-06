<?php

namespace App\Controllers;
use App\Components\Auth\Identity;
use App\Components\Controller;
use App\Models\Document;
use T4\Core\Std;
use T4\Fs\Helpers;
use App\Models;
use T4\Mvc\Route;



class Index
    extends Controller
{
    const PAGE_SIZE = 5;

   public function actionDefault(){

    }



        public function actionList($page = 1)
        {
            $this->data->items = Document::findAll([
                'order' => 'modifyat DESC',
                'offset' => ($page - 1) * self::PAGE_SIZE,
                'limit' => self::PAGE_SIZE]);
        }

    public function actionGetList($page=1){
        $data=Document::findAll([
            'order' => 'modifyat DESC',
            'offset'=> ($page-1)*self::PAGE_SIZE,
            'limit'=> self::PAGE_SIZE]);

        foreach ($data as $key_0 => $value_0) {
            $data[$key_0] = $value_0;
            $documents[$key_0]['createAT']=$data[$key_0]['createat'];
            $documents[$key_0]['modifyAT']=$data[$key_0]['modifyat'];
            $documents[$key_0]['guid']=$data[$key_0]['guid'];
            $documents[$key_0]['payload']=$data[$key_0]['payload'];
            $documents[$key_0]['status']=$data[$key_0]['status'];


        }


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
                $identity->authenticate($login);

            } catch (\T4\Auth\Exception $e) {

                $this->data->login_err=$e->getMessage();

            }

        }
    }

    public function actionEdit($id = null)
    {
        if ($this->app->user) {

            if (null === $id || 'new' == $id) {
                $this->data->item = new Document();
            } else {
                $item = Document::findByPK($id);
                if ($item->users->name == $this->app->user->name) {
                    $this->data->item = $item;
                } else {
                    header('HTTP/1.0 403 Forbidden', true, 403);
                    $route = new Route($this->app->config->errors['403']);
                    $route->params->message="Вы не являетесь владельцем документа";
                    $this->app->runRoute($route, 'html');
                    die;
                }
            }
        } else {
            header('HTTP/1.0 401 Forbidden', true, 401);
            $route = new Route($this->app->config->errors['401']);
            $route->params->message="Вы не авторизованый пользователь";
            $this->app->runRoute($route, 'html');
            die;
        }

    }

    public function actionSave()
    {
        date_default_timezone_set("UTC");
        if (!empty($this->app->request->post->__id)) {
            $item = Document::findByPK($this->app->request->post->__id);
            $item->modifyat=time()+abs($this->app->request->post->tz);
        } else {
            $item = new Document();
            $item->createat=time()+abs($this->app->request->post->tz);
            $item->guid=Document::getGUID();
            $item->user=$this->app->user->Pk;
        }
        if($this->app->request->post->action == 'publish')
            $item->published=true;


        $item->fill($this->app->request->post);

        $item->save();


    }


    public function action403($message){
        $this->data->msg=$message;
    }

    public function action401($message){
        $this->data->msg=$message;
    }

}