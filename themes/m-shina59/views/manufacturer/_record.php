<?php
    echo CHtml::openTag('li');
        
        if( isset($data->imageFile[0]) ){
            echo CHtml::image( $data->imageFile[0]->getURL(), CHtml::encode($data->model) , array('width'=>150) );
        }
        
        
        
        echo CHtml::openTag('div',array('class'=>'name'));
            echo $data->getLink(CHtml::encode($data->manufacturerManufacturer->name.' '.$data->model));
        echo CHtml::closeTag('div');
        
        echo CHtml::openTag('div',array('class'=>'price'));
            echo CHtml::encode($data->price);
        echo CHtml::closeTag('div');
        
        echo CHtml::openTag('div',array());
            echo CHtml::link('Добавить в корзину', $data->addCartURL(), array('class'=>'btn') );
        echo CHtml::closeTag('div');        
        
    echo CHtml::closeTag('li');
?>