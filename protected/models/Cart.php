<?php

class Cart extends CModel {

    static private $instance = NULL;

    public static function model(){
        if (self::$instance == NULL){
            self::$instance = new self();
        }
        return self::$instance;        
    }

    public function attributeNames(){
        return array();
    }
    
    public function attributeLabels(){
        return array();
    }
    
    public function quantity(){
        return 1;
    }

    public function price(){
        return 1;
    }

}
