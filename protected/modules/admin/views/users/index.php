<?php
$this->breadcrumbs=array(
	'Пользователи',
);

$this->renderPartial('secondMenu');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'	=>	$users->search(),
	'columns' => array(
		array('name'=>'id','htmlOptions'=>array('width'=>'10')),
        array(
            'name'=>'status',
            'value'=>'User::$statuses[$data->status];'
        ),
        'registration_time',
		'email',
        'username',
        array(
            'name'=>'role_id',
            'value'=>'Roles::getRoleString($data->role_id)',
            'filter'    =>	true,
        ),
		array(
			'htmlOptions'=>array('width'=>'10'),
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=> array(
				'update' => array(
					'url'=> 'Yii::app()->createUrl("admin/users/edit",array("id"=>$data->id) )',
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
					'url'=> 'Yii::app()->createUrl("admin/users/delete",array("id"=>$data->id) )',
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