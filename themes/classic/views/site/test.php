<?php

        $this->widget('zii.widgets.grid.CGridView', array(
            'ajaxUpdate'=>false,
            'enablePagination' => true,
            'dataProvider'=>$model->search(),
        	'filter'=>$model,
        	'columns' =>$model->getTableFields(),
            
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


        <footer id="footer">
		    <ul class="nav nav-pills">
			    <li><?=CHtml::link('О проекте',$this->createUrl('/page/about'))?></li>
		    </ul>
		    <?php
		    //if ( YII_DEBUG  ) {
			    echo '<br/>Отработало за ' . sprintf('%0.5f', Yii::getLogger()->getExecutionTime()) . ' с. ';
			    echo 'Скушано памяти: ' . round(memory_get_peak_usage() / (1024 * 1024), 2) . ' MB <br>';
			    $sql_stats = YII::app()->db->getStats();
			    echo $sql_stats[0] . ' запросов к БД, время выполнения запросов - ' . sprintf('%0.5f', $sql_stats[1]) . ' c.';
		    //}
		    ?>
		    <p>&copy; 2011</p>
	    </footer><!-- #footer -->