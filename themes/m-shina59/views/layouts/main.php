<!DOCTYPE html>
<html>
    <head>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>" />
        <meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>" />
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
			<header>
				<img class="logo" src="http://dummyimage.com/350x150/c0c0c0"/>
                <div id="cart">
                    <i class="icon-shopping-cart"></i>
                    <span class='quantity'></span>/<span class="price"></span>
                </div>
                <br/>
				<div class="navbar">
					<div class="navbar-inner">
					<?php
						$this->widget('zii.widgets.CMenu',array(
							'items'=> array(
								 array('label'=>'Главная', 'url'=>array('site/index')),
								 array('label'=>'Доставка', 'url'=>array('site/delivery')),
								 array('label'=>'Контакты', 'url'=>array('site/сontacts')),
							),
							'encodeLabel'=> false,
							'htmlOptions'=> array('class'=>'nav')
						));
					?>
					</div>
				</div>
			</header>
			<?php

				$this->widget('zii.widgets.CBreadcrumbs', array(
					'separator'=>'<span class="divider">/</span>',
					'htmlOptions'=>array(
						'class'=>'breadcrumb'
					),
					'links'=>$this->breadcrumbs
				));

			?>

            <?php echo $content; ?>
            <footer id="footer">
    		    <?php
    		    if ( YII_DEBUG  ) {
    			    echo '<br/>Отработало за ' . sprintf('%0.5f', Yii::getLogger()->getExecutionTime()) . ' с. ';
                    echo 'Скушано памяти: ' . round(memory_get_peak_usage() / (1024 * 1024), 2) . ' MB <br>';
    			    $sql_stats = YII::app()->db->getStats();
    			    echo $sql_stats[0] . ' запросов к БД, время выполнения запросов - ' . sprintf('%0.5f', $sql_stats[1]) . ' c.';
    		    }
    		    ?>
    	    </footer>
        </div>

	</body>
</html>