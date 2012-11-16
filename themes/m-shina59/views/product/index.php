<?php
$this->pageTitle = $product->name;
$this->pageDescription = $product->description;
$this->pageKeywords = $product->keywords;

$this->breadcrumbs = array(
    CHtml::encode($product->name) => array('product/index','alias'=>$product->alias)
);


$alias = $product->alias;

    $this->widget('zii.widgets.grid.CGridView', array(
            'filterPosition'=>'body',
            'ajaxUpdate'=>false,
            'enablePagination' => true,
            'dataProvider'=>$records->search(),
    		'filter'=>$records,
        	'columns' =>

                    array(
                        'name'=> array(
                            'value'=>' $data->getLink( $data->name ) ',
                            'type'=>'raw'
                        )
                    )
                    + $records->getTableFields() +
                    array(
                        array(
                            'value'=>' $data->getAddToCartLink( "add" ) ',
                            'type'=>'raw'
                        )
                    )
                ,
        	'htmlOptions'=>array('class'=>false),
        	'itemsCssClass'=>'table table-bordered table-striped',
        	'template'=>'{summary} {items} {pager}',
        	'pagerCssClass'=>'pagination',
        	'pager'=>array(
        		'class' => 'myLinkPager',
        		'cssFile' => false,
        		'header' => '',
        		'firstPageLabel'=> '&laquo;',
        		'prevPageLabel'	=> '&larr;',
        		'nextPageLabel'	=> '&rarr;',
        		'lastPageLabel' => '&raquo;',
        		'htmlOptions'	=> array("class"=>false),
        	),
        ));


$this->beginClip('sidebar');
  echo $records->getFilterForm();
$this->endClip();

?>