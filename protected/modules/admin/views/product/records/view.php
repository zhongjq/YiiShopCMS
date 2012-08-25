<?php
$this->pageTitle = $product->name;

$this->breadcrumbs=array($product->name);

$this->renderPartial('records/secondMenu',array('product'=>$product));

$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$record->search(),
	'filter'=>$record,
	'columns' =>
		array_merge(
			$record->getTableFields()
			,
			array(
				array(
					'htmlOptions'=>array('width'=>'10'),
					'class'=>'CButtonColumn',
					'template'=>'{update}',
					'buttons'=> array(
						'update' => array(
							'url'=> 'Yii::app()->createUrl("admin/product/editrecord",array("productId"=>'.$product->id.',"recordId"=>$data->id) )',
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
							'url'=> 'Yii::app()->createUrl("admin/product/deleterecord",array("productId"=>'.$product->id.',"recordId"=>$data->id) )',
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