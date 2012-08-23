<?php

$this->breadcrumbs=array(
    Yii::t('lists',"Lists") => array("/admin/lists"),
    Yii::t('lists',"Items list #").$item->list->id => $this->createUrl('/admin/lists/items',array('id'=>$item->list_id) ),
    Yii::t('lists',"Edit item #").$item->id
);

$this->renderPartial('items/secondMenu',array('list'=>$item->list));

echo $form;
?>