<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">

	<!-- icons -->
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon.ico" type="image/x-icon">

    <!-- styles -->
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.responsive" rel="stylesheet">

	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet">
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
	<?php echo CHtml::metaTag(Yii::app()->params['description'],'description'); ?>
	<?php echo CHtml::metaTag(is_array(Yii::app()->params['keywords'])?implode(Yii::app()->params['keywords'],','):Yii::app()->params['keywords'],'keywords');?>
	<?php echo CHtml::metaTag(Yii::app()->params['author'],'author'); ?>


	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php Yii::app()->getClientScript()->registerScriptFile( 'jquery.js' ); ?>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>


  </head>

    <body>

	    <div class="container-fluid">
		    <div class="row-fluid">
			    <div class="span2">
				    <?php $this->widget('CategoriesWidget', array("ID"=>1) ) ?>
			    </div>
			    <div class="span10">
				    <?php echo $content; ?>
			    </div>
		    </div>
	    </div>


	</body>
</html>