<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$Product->ID,
);

$this->renderPartial('records/SecondMenu',array('Product'=>$Product));

?>

<?php if( $Goods && $GoodsData ) : ?>
<?php

    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$GoodsData,
        'columns' => 
            array_merge(
                $Goods->getTableFields()
                ,
                array(       
                    array(
                        'htmlOptions'=>array('width'=>'10'),
                        'class'=>'CButtonColumn',
                        'template'=>'{update}',
                        'buttons'=> array(
                            'update' => array(
                                'url'=> 'Yii::app()->createUrl("/admin/product/editrecord",array("ProductID"=>'.$Product->ID.',"RecordID"=>$data->ID) )',
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
                                'url'=> 'Yii::app()->createUrl("/admin/product/deleterecord",array("ProductID"=>'.$Product->ID.',"RecordID"=>$data->ID) )',
                                'imageUrl'=>null,
                                'label'=>'<span class="close" title="'.Yii::t('AdminModule.main','Удалить').'">&times;</span>'
                            )
                        )                
                    ),           
                )
            ),
        'htmlOptions'=>array(
            'class'=> ''   
        ),
        'itemsCssClass'=>'table table-bordered table-striped',
        'template'=>'{summary} {items} {pager}',
        'pagerCssClass'=>'pagination',
        'pager'=>array(
            'class'         =>'myLinkPager',
            'cssFile'        => false,
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
<h3><?=Yii::t('AdminModule.products',"У товара нет полей.")?></h3><br>
	<?= CHtml::link("Добавить поле",
		$this->createUrl('/admin/product/addfield',array('id'=>$Product->ID)),
		array('class'=>'btn btn-primary btn-large')) ?>

<?php endif; ?>