<?php
$this->pageTitle = $product->name;
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
                    + $records->getTableFields()
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
    

?>