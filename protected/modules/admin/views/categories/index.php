<?php
$this->pageTitle	=	Yii::t("AdminModule.categories", "Categories");
$this->breadcrumbs	=	array(Yii::t("AdminModule.categories", "Categories"));

$this->renderPartial('SecondMenu');

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'	=>	$Categories,
	'enableSorting'	=>	false,
	'ajaxUpdate'	=>	false,
	'columns' => array(
		array('name'=>'ID','htmlOptions'=>array('width'=>'10')),
		array(	'name'	=>	'Name',
				'type'	=>	'raw',
				'value'	=>	'str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $data->Level <= 1 ? 0 : $data->Level-1 ).$data->Name'
		),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=> array(
				'update' => array(
					'url'=> 'Yii::app()->createUrl("/admin/categories/edit",array("CategoryID"=>$data->ID) )',
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
					'url'=> 'Yii::app()->createUrl("/admin/categories/delete",array("CategoryID"=>$data->ID) )',
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
