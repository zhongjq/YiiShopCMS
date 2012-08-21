<?php
$this->breadcrumbs=array(
    'Товары'    =>  array('index'),
    Yii::t('lists',"Lists") => array("/admin/lists"),
    "Элементы списка ".$list->id
);

$this->renderPartial('items/secondMenu',array('list'=>$list));



    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$items,
        'columns' => array(
            array(
                'name'=>'id',
                'htmlOptions'=>array(
                    'width'=> '10'
                ),
            ),
            array(
                'name'=>'status',
                'htmlOptions'=>array(
                    'width'=> '70'
                ),
                'type'  =>  'raw',
                'value' =>  '$data->status ? "<span class=\'label label-success\'>Активирован</span>" : "<span class=\'label label-important\'>Неактивирован</span>"' ,
            ),
            array(
                'name'=>'priority',
                'htmlOptions'=>array(
                    'width'=> '10'
                ),
            ),
            'name',
            array(
                'htmlOptions'=>array('width'=>'10'),
                'class'=>'CButtonColumn',
                'template'=>'{update}',
                'buttons'=> array(
                    'update' => array(
                        'url'=> 'Yii::app()->createUrl("/admin/lists/edititem",array("listId"=>'.$list->id.',"itemId"=>$data->id) )',
                        'imageUrl'=>null,
                        'label'=>'<span class="icon-pencil pointer" title="'.Yii::t('main','Редактировать').'"></span>'
                    )
                )
            ),
            array(
                'htmlOptions'=>array('width'=>'10'),
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                'buttons'=> array(
                    'delete' => array(
                        'url'=> 'Yii::app()->createUrl("/admin/lists/deleteitem",array("listId"=>'.$list->id.',"itemId"=>$data->id) )',
                        'imageUrl'=>null,
                        'label'=>'<span class="close" title="'.Yii::t('main','Удалить').'">&times;</span>'
                    )
                )
            ),
        ),
        'htmlOptions'=>array(
            'class'=> ''
        ),
        'itemsCssClass'=>'table table-bordered table-striped',
        'template'=>'{summary} {items} {pager}',
        'pagerCssClass'=>'pagination',
        'pager'=>array(
            'class'         =>'myLinkPager',
            'cssFile'		=> false,
        	'header'        => '',
        	'firstPageLabel'=> '&laquo;',
        	'prevPageLabel'	=> '&larr;',
        	'nextPageLabel'	=> '&rarr;',
        	'lastPageLabel' => '&raquo;',
        	'htmlOptions'	=> array("class"=>false),
        ),
    ));

?>