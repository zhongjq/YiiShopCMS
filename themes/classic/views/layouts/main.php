<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

	<!-- icons -->
	<link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/img/favicon.ico" type="image/x-icon">

    <!-- styles -->
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/bootstrap.min.responsive.css" rel="stylesheet">

	<link href="<?= Yii::app()->theme->baseUrl ?>/css/style.css" rel="stylesheet">
	<style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}
		.sidebar-nav {
			padding: 9px 0;
		}
	</style>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php Yii::app()->clientScript->registerCoreScript('jquery.js'); ?>
	<script src="<?= Yii::app()->theme->baseUrl ?>/js/bootstrap.min.js"></script>


  </head>

    <body>

	    <div class="container-fluid">
		    <div class="row-fluid">
			    <div class="span3">
				    <?php $this->widget('Categories', array('title'=>"Категории",'id'=>1) ) ?>
					<?php $this->widget('Manufacturers', array("title"=>"Производители") ) ?>
			    </div>
			    <div class="span9">
				    <?php echo $content; ?>
				    <?=CHtml::link('О проекте',$this->createUrl('/products/view',array('product'=>'bus',"id"=>"asd")))?>
			    </div>
		    </div>
	    </div>
	    <hr>
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

	</body>
</html>