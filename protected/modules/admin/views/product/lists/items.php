<?php
$this->breadcrumbs=array(
    'Товары'    =>  array('index'),
    Yii::t('AdminModule.products',"Lists") => array("/admin/products/lists"),
    "Элементы списка ".$List->ID
);

$this->renderPartial('lists/SecondMenuItems',array('List'=>$List));

?>

<?php if( $List->ListsItems() ) : ?>


<?php 

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$ListsItems,
        'columns' => array(
            array(
                'name'=>'ID',
                'htmlOptions'=>array(
                    'width'=> '10'   
                ),
            ),
            array(
                'name'=>'Status',
                'htmlOptions'=>array(
                    'width'=> '70'   
                ),                
                'type'  =>  'raw',
                'value' =>  '$data->Status ? "<span class=\'label label-success\'>Активирован</span>" : "<span class=\'label label-important\'>Неактивирован</span>"' ,
            ),
            array(
                'name'=>'Priority',
                'htmlOptions'=>array(
                    'width'=> '10'   
                ),
            ),
            'Name',
            array(
                'htmlOptions'=>array('width'=>'10'),
                'class'=>'CButtonColumn',
                'template'=>'{update}',
                'buttons'=> array(
                    'update' => array(
                        'url'=> 'Yii::app()->createUrl("/admin/product/edititem",array("ListID"=>'.$List->ID.',"ItemID"=>$data->ID) )',
                        'imageUrl'=>null,
                        'label'=>'<span class="icon-pencil pointer" title="'.Yii::t('AdminModule.main','Редактировать').'"></span>'
                    )
                )
            ),
            array(
                'htmlOptions'=>array('width'=>'10'),
                'class'=>'CButtonColumn',
                'template'=>'{delete}',
                'buttons'=> array(
                    'delete' => array(
                        'url'=> 'Yii::app()->createUrl("/admin/product/deleteitem",array("ListID"=>'.$List->ID.',"ItemID"=>$data->ID) )',
                        'imageUrl'=>null,
                        'label'=>'<span class="close" title="'.Yii::t('AdminModule.main','Удалить').'">&times;</span>'
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

<?php else : ?>
    <h3><?=Yii::t('AdminModule.products',"Not items.")?></h3><br>
	<?= CHtml::link(Yii::t('AdminModule.products',"Add items"),
					$this->createUrl('/admin/product/additems',array('ListID'=>$List->ID)),
					array('class'=>'btn btn-primary btn-large')) ?>
<?php endif ?>