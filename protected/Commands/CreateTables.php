<?php

namespace App\Commands;


use App\Models\User;
use T4\Console\Command;


class CreateTables extends Command
{
    public  function actionInit(){

        $users='CREATE TABLE IF NOT EXISTS  users (__id int (10) AUTO_INCREMENT,
name varchar(20) NOT NULL,
PRIMARY KEY (__id))';

        $session='CREATE TABLE IF NOT EXISTS  sessions (__id int (10) AUTO_INCREMENT,
hash varchar(20) NOT NULL,
userAgentHash varchar(20) NOT NULL,
PRIMARY KEY (__id))';

        $documents='create table if not EXISTS documents (
__id int (10) AUTO_INCREMENT,
guid varchar(100) NOT NULL,
payload text ,
published DATETIME  ,
__user_id int (10) NOT NULL,
PRIMARY KEY (__id),
FOREIGN KEY (__user_id) REFERENCES users (__id) ON DELETE CASCADE)';

        $this->app->db->default->execute($users);
        $this->app->db->default->execute($session);
        $this->app->db->default->execute($documents);

     if(!User::findAllByName('name')) {
         $user = new User();
         $user->name = 'root';
         $user->save();
     }

    }


}