<?php 
    $this->pageTitle = Yii::t('users',"Login");
    
    Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/login.css');
    
    echo $form; 
?>