<?php
$this->breadcrumbs=array(
	Yii::t("products","Constructor Goods") => array('index'),
	Yii::t('products',"Fields product")." ".$product->name,
);

$this->renderPartial('fields/secondMenu',array('product'=>$product));

Yii::app()->clientScript->registerCoreScript('jquery.ui');

$csrf_token_name = Yii::app()->request->csrfTokenName;
$csrf_token = Yii::app()->request->csrfToken;

$str_js = "
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };
		var table = $('#fields tbody');
        table.sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            items: 'tr',
            update : function () {
                serial = table.sortable('serialize', {key: 'fields[]', attribute: 'class'});
                $.ajax({
                    'url': '" . $this->createUrl('/admin/constructor/sorting',array('id'=>$product->id)) . "' + '?{$csrf_token_name}={$csrf_token}',
                    'type': 'post',
                    'data': serial,
                    'success': function(data){},
                    'error': function(request, status, error){
                        alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');
                    }
                });
            },
            helper: fixHelper
        }).disableSelection();
    ";

    Yii::app()->clientScript->registerScript('sortable-project', $str_js);


$this->widget('zii.widgets.grid.CGridView', array(
	"id"=>'fields',
	'dataProvider'=>$fields,
	'enableSorting'=>false,
	'rowCssClassExpression'=>'"field_{$data->id}"',
	'columns' => array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('width'=> '10'),
		),
		'name',
		'alias',
        array(
			"name"=>'field_type',
            'value'=>'TypeField::getFieldName($data->field_type)'
		),        
		array(
			"name"=>'is_mandatory',
			'value'=>'$data->is_mandatory ? Yii::t("main","Yes") : Yii::t("main","No")'
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