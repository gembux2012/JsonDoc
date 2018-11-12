<?php

namespace App\Modules\Json\Models;

use T4\Core\Std;
use T4\Dbal\Connection;
use T4\Mvc\Application;
class SimpleOrm

{
    public  function insert($tablename){
        $sxema=Application::instance()->db->default->execute('SHOW COLUMNS FROM '.$tablename);

        return $sxema;
    }
}