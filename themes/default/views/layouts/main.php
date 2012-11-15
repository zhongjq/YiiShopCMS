<!DOCTYPE html>
<html>
    <head>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    	<!--[if lt IE 9]>
    		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    	<![endif]-->

        <?php
            Yii::app()->clientScript->registerCoreScript('jquery');
    
            Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/bootstrap/js/bootstrap.min.js');
    
            Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/bootstrap/css/bootstrap.css');
            Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/bootstrap/css/bootstrap-responsive.css');
            Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/style.css');    
        ?>
	</head>
	<body>
        <div class="container">
            <?php echo $content; ?>
        </div>
	</body>
</html>