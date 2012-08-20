<?php
$this->pageTitle	=	Yii::t("manufacturers", "Manufacturers");
$this->breadcrumbs	=	array(Yii::t("manufacturers", "Manufacturers"));

$this->renderPartial('secondMenu');

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'	=>	$manufacturers,
	'enableSorting'	=>	false,
	'ajaxUpdate'	=>	false,
	'columns' => array(
		array('name'=>'id','htmlOptions'=>array('width'=>'10')),
		array('name'=>'name','value'=>'$data->name'),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=> array(
				'update' => array(
					'url'=> 'Yii::app()->createUrl("/admin/manufacturer/edit",array("id"=>$data->id) )',
					'imageUrl'=>null,
					'label'=>'<span class="icon-pencil pointer" title="'.Yii::t('main','Edit').'"></span>'
				)
			)
		),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{delete}',
			'buttons'=> array(
				'delete' => array(
					'url'=> 'Yii::app()->createUrl("/admin/manufacturer/delete",array("id"=>$data->id) )',
					'imageUrl'=>null,
					'label'=>'<span class="close" title="'.Yii::t('main','Delete').'">&times;</span>'
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
		'cssFile'    	=> false,
		'header'        => '',
		'firstPageLabel'=> '&laquo;',
		'prevPageLabel'	=> '&larr;',
		'nextPageLabel'	=> '&rarr;',
		'lastPageLabel' => '&raquo;',
		'htmlOptions'	=> array("class"=>false),            
	),
));

?>
