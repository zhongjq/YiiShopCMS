<?php
$this->breadcrumbs=array( Yii::t('lists',"Lists") );

$this->renderPartial('secondMenu');


$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$lists,
	'columns' => array(
		array(
			'name'=>'id',
			'htmlOptions'=>array(
				'width'=> '10'
			),
		),
		'name',
		array(
			'type'=>'raw',
			'value'=>   'sizeof($data->listsItems)'
		),
		array(
			'type'=>'raw',
			'value'=>   'CHtml::link(Yii::t("lists","items"),Yii::app()->createUrl("admin/lists/items",array("id"=>$data->id)));'
		),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=> array(
				'update' => array(
					'url'=> 'Yii::app()->createUrl("/admin/lists/edit",array("id"=>$data->id) )',
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
					'url'=> 'Yii::app()->createUrl("/admin/lists/delete",array("id"=>$data->id) )',
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
	'emptyText' => $this->renderPartial('noResult',null,true),
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