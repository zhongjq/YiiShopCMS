<?php

Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/chosen/chosen.css');
Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/chosen/chosen.jquery.min.js');
$js = '$("select.chzn-select").chosen({allow_single_deselect:true});';
Yii::app()->getClientScript()->registerScript("select",'$(function(){'.$js.'});');

$this->pageTitle = $product->name;

$this->breadcrumbs=array($product->name);

$this->renderPartial('records/secondMenu',array('product'=>$product));

if ( $record->isAdminEdit ) echo CHtml::beginForm();

$this->widget('zii.widgets.grid.CGridView', array(
    'afterAjaxUpdate'=>'function(id, data){ '.$js.' }',
	'dataProvider'=>$record->search(),
	'filter'=>$record,
	'columns'=>
		array_merge(
			$record->getAdminTableFields()
			,
			array(
				array(
					'htmlOptions'=>array('class'=>'edit'),
					'class'=>'CButtonColumn',
					'template'=>'{update}',
					'buttons'=> array(
						'update' => array(
							'url'=> 'Yii::app()->createUrl("admin/product/editrecord",array("productId"=>'.$product->id.',"recordId"=>$data->id) )',
							'imageUrl'=>null,
							'label'=>'<span class="icon-pencil pointer" title="'.Yii::t('AdminModule.main','Edit').'"></span>',
                            'htmlOptions'=>array('title'=>Yii::t('AdminModule.main','Edit')),
						)
					)
				),
				array(
					'htmlOptions'=>array('class'=>'delele'),
					'class'=>'CButtonColumn',
					'template'=>'{delete}',
					'buttons'=> array(
						'delete' => array(
							'url'=> 'Yii::app()->createUrl("admin/product/deleterecord",array("productId"=>'.$product->id.',"recordId"=>$data->id) )',
							'imageUrl'=>null,
							'label'=>'<span class="close" title="'.Yii::t('AdminModule.main','Delete').'">&times;</span>',
                            'htmlOptions'=>array('title'=>Yii::t('AdminModule.main','Delete')),
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

<?php if ( $record->isAdminEdit ) : ?>
<div class="row submit">
<?php echo CHtml::submitButton('Сохранить',array('class'=>'btn')); ?> <span class="label label-important">Warning</span> поля не прощедшие валидацию будут проигнорированны.
</div>
<?php echo CHtml::endForm(); ?>
<?php endif ?>






