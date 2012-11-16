<?php
$this->pageTitle = $manufacturer->name;
$this->pageDescription = $manufacturer->description;
$this->pageKeywords = $manufacturer->keywords;

$this->breadcrumbs = array(
    CHtml::encode("Производители") => array('manufacturer/index'),
    CHtml::encode($manufacturer->name)
);

if( !empty($products) ){
    foreach($products as $product){
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider'=>$product->search(),
			//'filter'=>$product,
        	//'columns' =>$product->getTableFields(),
            'columns' => array(
                array(
                    'name'=>'name',
                    'value'=>'$data->getLink($data->name)',
                    'type'=>'raw'
                ),
                'price'
            ),
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
    }
}

?>