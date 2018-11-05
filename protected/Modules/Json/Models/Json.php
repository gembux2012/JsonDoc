<?php
/**
 * Created by PhpStorm.
 * User: alexc
 * Date: 16.10.18
 * Time: 11:26
 */

namespace App\Modules\Json\Models;
use T4\Console\Application;
use T4\Fs\Helpers;


use  T4\Dbal\Connection;


class Json

{
    static protected $shema = ['document' => [
        'id' => '',
        'status' => 'draft',
        'payload' => [

        ],
        'createAt' => '',
        'modifyAt' => ''
    ]];



  public function init(){

    $conection=new Connection();
    $conection->execute('CREATE TABLE IF NOT EXISTS `mydb`.`users` (
  `` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (``),
  UNIQUE INDEX `_UNIQUE` (`` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
PACK_KEYS = DEFAULT;');
  }

 private function NewDoc(){


        self::$shema['document']['id']=self::getGUID();
        self::$shema['document']['createAt']=date("Y-m-d H:i:s");

        return self::$shema;
    }

    static public function SaveFile($data){

        $path=Helpers::getRealPath('/jsondoc/').$data['document']['id'].'.json';
        try {
            file_put_contents($path, json_encode($data));
        } catch(Exception $e){
            $e;
            echo '';
        }
        echo '';
    }

    static public function getContent($source){

        if ($source=='new')
            return self::NewDoc();

    }

    static public function setContent(){

    }

    static private  function getGUID(){

            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = //chr(123)// "{"
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
                //.chr(125);// "}"
            return $uuid;

    }



}
/*SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`users` (
  `` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (``),
  UNIQUE INDEX `_UNIQUE` (`` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
PACK_KEYS = DEFAULT;


-- -----------------------------------------------------
-- Table `mydb`.`docs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`docs` (
  `` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT NULL,
  `unidock` VARCHAR(45) NULL,
  `path` VARCHAR(45) NULL,
  PRIMARY KEY (``),
  UNIQUE INDEX `_UNIQUE` (`` ASC),
  INDEX `fk_docs_1_idx` (`id_user` ASC),
  CONSTRAINT `user`
    FOREIGN KEY (`id_user`)
    REFERENCES `mydb`.`users` (``)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

