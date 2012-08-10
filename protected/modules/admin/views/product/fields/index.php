<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$Product->ID => $this->createUrl('/admin/product/edit',array('ProductID'=>$Product->ID)),
	'Поля товара',
);

$this->renderPartial('fields/SecondMenu',array('Product'=>$Product));

?>

<?php if( $Product->productsFields ) : ?>
<?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$Fields,
        'columns' => array(
            array(
                'name'=>'ID',
                'htmlOptions'=>array('width'=> '10'),
            ),
            'Name',
            'Alias',
            array(
                "name"=>'IsMandatory',
                'value'=> '$data->IsMandatory ? "Yes" : "No"'
            ),
            array(
                "name"=>'IsFilter',
                'value'=> '$data->IsFilter ? "Yes" : "No"'
            ),            
            array(
                'htmlOptions'=>array('width'=>'10'),
                'class'=>'CButtonColumn',
                'template'=>'{update}',
                'buttons'=> array(
                    'update' => array(
                        'url'=> 'Yii::app()->createUrl("/admin/product/editfield",array("ProductID"=>'.$Product->ID.',"FieldID"=>$data->ID) )',
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
                        'url'=> 'Yii::app()->createUrl("/admin/product/deletefield",array("ProductID"=>'.$Product->ID.',"FieldID"=>$data->ID) )',
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
					$this->createUrl('/admin/product/addfield',array('ProductID'=>$Product->ID)),
					array('class'=>'btn btn-primary btn-large')) ?>

<?php endif; ?>