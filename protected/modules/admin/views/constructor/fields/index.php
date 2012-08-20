<?php
$this->breadcrumbs=array(
	Yii::t("products","Constructor Goods") => array('index'),
	Yii::t('products',"Fields product"),
);

$this->renderPartial('fields/secondMenu',array('product'=>$product));


$this->widget('zii.widgets.grid.CGridView', array(
	"id"=>'fields',
	'dataProvider'=>$fields,
	'columns' => array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('width'=> '10'),
		),
		'name',
		'alias',
		array(
			"name"=>'is_mandatory',
			'value'=> '$data->is_mandatory ? Yii::t("main","Yes") : Yii::t("main","No")'
		),
		array(
			"name"=>'is_filter',
			'value'=> '$data->is_filter ? Yii::t("main","Yes") : Yii::t("main","No")'
		),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=> array(
				'update' => array(
					'url'=> 'Yii::app()->createUrl("/admin/constructor/editfield",array("productId"=>'.$product->id.',"fieldId"=>$data->id) )',
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
					'url'=> 'Yii::app()->createUrl("/admin/constructor/deletefield",array("productId"=>'.$product->id.',"fieldId"=>$data->id) )',
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
	'emptyText' => $this->renderPartial('fields/noResult',array('product'=>$product),true),
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