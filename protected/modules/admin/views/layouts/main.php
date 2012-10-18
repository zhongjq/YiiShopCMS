<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">

    <!-- icons -->
	<link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/img/favicon.ico" type="image/x-icon">

    <!-- styles -->
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
	<!--  <script src="<?= Yii::app()->theme->baseUrl ?>/js/jquery.min.js"></script>-->

    <?php
        Yii::app()->clientScript->registerCoreScript('jquery');

        Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/bootstrap/js/bootstrap.min.js');

        Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/bootstrap/css/bootstrap.css');
        Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/bootstrap/css/bootstrap-responsive.css');
        Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/style.css');

    ?>

  </head>

  <body>
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="i-bar"></span>
			<span class="i-bar"></span>
			<span class="i-bar"></span>
			</a>
			<a class="brand" href="<?php echo Yii::app()->urlManager->baseUrl; ?>/admin">
				<?=Yii::t("main", "Shop") ?>
			</a>
			<div class="nav-collapse">
				<script>
					$(function(){
						$('.dropdown-toggle').dropdown()
					})
				</script>
			<? if(!Yii::app()->user->isGuest) : ?>
				<?php
					$this->FirstMenu=array(
						array(	'label'			=>	Yii::t("main", "Сatalog").'<b class="caret"></b>',
								'url'			=>	'#',
								'itemOptions'	=>	array('class'=>'dropdown'),
								'linkOptions'	=>	array('class'=>'dropdown-toggle','data-toggle'=>'dropdown-toggle'),
								'encodeLabel'	=>	false,
								'items'			=>
                                    array_merge(
                                        Product::getElementsMenuProduct()
                                        ,
                                        array(
        									array(	'label'	=>	Yii::t("categories", "Categories"),
        											'url'	=>	array('/admin/categories'),
        											'active'=>	$this->getId() =='category'),

        									array(	'label'	=>	Yii::t("manufacturers", "Manufacturers"),
        											'url'	=>	array('/admin/manufacturers'),
        											'active'=>	$this->getId() =='manufacturer'),

        									array(  'label' =>  Yii::t('lists',"Lists"),
        											'url'   =>  array('/admin/lists'),
        											'active'=>  $this->getId() == 'lists' ),

        									array(	'label'	=>	Yii::t("products", "Constructor Goods"),
        											'url'	=>	array('/admin/constructor'),
        											'active'=>	$this->getId() =='constructor'),
                                        )
                                    )
							),


						//array('label'=> Yii::t("AdminModule.main", "Товары"),	'url'=>array('/admin/products'), 'active'=>$this->getId() =='product'),
						array('label'=> Yii::t("main", "Заказы"), 'url'=>array('/admin/orders'), 'active'=>$this->getId() =='orders'),
						array('label'=> Yii::t("main", "Пользователи"), 'url'=>array('/admin/users'), 'active'=>$this->getId() =='users'),
					);
				?>
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'			=>	$this->FirstMenu,
					'htmlOptions'	=>	array('class'=>'nav'),
					'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),
					'encodeLabel'=>false,

				)); ?>
			<? endif;?>

			<ul class="nav pull-right">
				<li class="divider-vertical"></li>

				<ul class="nav pull-right">

					<li class="divider-vertical"></li>

					<? if(!Yii::app()->user->isGuest) : ?>
					<li class="dropdown">
						<?php echo CHtml::link( Yii::app()->user->name.'<b class="caret"></b>', "#" , array("class"=>"dropdown-toggle", "data-toggle"=>"dropdown") )?>
						<ul class="dropdown-menu">
							<li><?=CHtml::link('Профиль', $this->createUrl('/admin/user/view',array('UserID'=> Yii::app()->user->id)) )?></li>
							<li class="divider"></li>
							<li><?=CHtml::link('Выйти',$this->createUrl('/admin/default/logout'))?></li>
						</ul>
					</li>
					<? else : ?>
					<li><?=CHtml::link('Войти',$this->createUrl('login'))?></li>
					<? endif;?>
				</ul>

			</div><!--/.nav-collapse -->
		</div>
		</div>
	</div>

	<div class="container">

		<? if(!empty($this->SecondMenu)): ?>
			<div class="subnav subnav-fixed">
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>$this->SecondMenu,
					'htmlOptions'=>array('class'=>'nav nav-pills')
				)); ?>
			</div><br><br>
		<?endif;?>

		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
				'tagName'=>'ul',
				'activeLinkTemplate'=>'<li><a href="{url}">{label}</a><span class="divider">/</span></li>',
				'inactiveLinkTemplate'=>'<li><span>{label}</span></li>',
				'separator'=>null,
				'homeLink' => CHtml::tag('li',array(),CHtml::link('Главная',$this->createUrl('/admin'),array('title'=>'Главная страница')).CHtml::tag('span',array('class'=>'divider'),'/')  ),
				'links' =>	$this->breadcrumbs,
				'htmlOptions'=>array('class'=>'breadcrumb'),
		)); ?><!-- breadcrumbs -->


        <?php if(Yii::app()->user->hasFlash('error')):?>
            <div class="alert alert-erro">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?=Yii::app()->user->getFlash('error'); ?>
            </div>
        <?php endif; ?>

        <?=$content; ?>

		<hr>
		<footer id="footer">
			<?php
				if ( !Yii::app()->user->isGuest && YII_DEBUG && Yii::app()->user->role == "Administrator" ) {
					echo '<br/>Отработало за ' . sprintf('%0.5f', Yii::getLogger()->getExecutionTime()) . ' с. ';
					echo 'Скушано памяти: ' . round(memory_get_peak_usage() / (1024 * 1024), 2) . ' MB <br>';
					$sql_stats = YII::app()->db->getStats();
					echo $sql_stats[0] . ' запросов к БД, время выполнения запросов - ' . sprintf('%0.5f', $sql_stats[1]) . ' c.';
				}
			 ?>
			<p>&copy; <?=date("Y")?></p>
		</footer><!-- #footer -->

    </div> <!-- /container -->
  </body>
</html>