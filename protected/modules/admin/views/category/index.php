<?php
$this->pageTitle	=	Yii::t("categories", "Categories");
$this->breadcrumbs	=	array(Yii::t("categories", "Categories"));

$this->renderPartial('secondMenu');

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'	=>	$categories,
	'enableSorting'	=>	false,
	'ajaxUpdate'	=>	false,
	'columns' => array(
		array('name'=>'id','htmlOptions'=>array('width'=>'10')),
		array(	'name'	=>	'name',
				'type'	=>	'raw',
				'value'	=>	'str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $data->level <= 1 ? 0 : $data->level-1 ).$data->name'
		),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=> array(
				'update' => array(
					'url'=> 'Yii::app()->createUrl("/admin/category/edit",array("id"=>$data->id) )',
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
					'url'=> 'Yii::app()->createUrl("/admin/category/delete",array("id"=>$data->id) )',
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
