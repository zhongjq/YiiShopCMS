<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">

	<!-- icons -->
	<link rel="shortcut icon" href="<?= Yii::app()->theme->baseUrl ?>/img/favicon.ico" type="image/x-icon">

    <!-- styles -->
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Yii::app()->theme->baseUrl ?>/css/bootstrap-responsive.min.css" rel="stylesheet">

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
	<?php echo CHtml::metaTag(Yii::app()->params['description'],'description'); ?>
	<?php echo CHtml::metaTag(is_array(Yii::app()->params['keywords'])?implode(Yii::app()->params['keywords'],','):Yii::app()->params['keywords'],'keywords');?>
	<?php echo CHtml::metaTag(Yii::app()->params['author'],'author'); ?>


	<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--  <script src="<?= Yii::app()->theme->baseUrl ?>/js/jquery.min.js"></script>-->
	  <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<script src="<?= Yii::app()->theme->baseUrl ?>/js/bootstrap.min.js"></script>


  </head>

  <body>
  <? echo Yii::app()->request->baseUrl ?>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="i-bar"></span>
			<span class="i-bar"></span>
			<span class="i-bar"></span>
			</a>
			<a class="brand" href="<?php echo Yii::app()->urlManager->baseUrl; ?>/admin"><?php echo CHtml::encode(Yii::app()->name); ?></a>
			<div class="nav-collapse">

			<? if(!Yii::app()->user->isGuest) : ?>
				<?php
					$this->FirstMenu=array(
						array('label'=> Yii::t("AdminModule.main", "Категории"), 'url'=>array('/admin/categories'), 'active'=>$this->getId() =='categories'),
						array('label'=> Yii::t("AdminModule.main", "Товары"), 'url'=>array('/admin/products'), 'active'=>$this->getId() =='product'),
						array('label'=> Yii::t("AdminModule.main", "Заказы"), 'url'=>array('/admin/orders'), 'active'=>$this->getId() =='orders'),
						array('label'=> Yii::t("AdminModule.main", "Пользователи"), 'url'=>array('/admin/users'), 'active'=>$this->getId() =='users'),
					);
				?>
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>$this->FirstMenu,
					'htmlOptions'=>array('class'=>'nav')
				)); ?>
			<? endif;?>

			<ul class="nav pull-right">
				<li class="divider-vertical"></li>

				<ul class="nav pull-right">

					<li class="divider-vertical"></li>

					<? if(!Yii::app()->user->isGuest) : ?>
					<li class="dropdown">
						<?php echo CHtml::link( Yii::app()->user->name.'<b class="caret"></b>', $this->createUrl('/user/view',array('id'=> Yii::app()->user->id  ) ), array("class"=>"dropdown-toggle", "data-toggle"=>"dropdown") )?>
						<ul class="dropdown-menu">
							<li class="divider"></li>
							<li><?=CHtml::link('Выйти',$this->createUrl('/logout'))?></li>
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
				'separator' =>	" &rarr; ",
				'homeLink'	=>	CHtml::link('Главная',$this->createUrl('/admin'),array('title'=>'Главная страница')),
				'links'		=>	$this->breadcrumbs,
				'htmlOptions'=>array('class'=>'breadcrumb'),
		)); ?><!-- breadcrumbs -->


		<?php echo $content; ?>

		<hr>
		<footer id="footer">
			<ul class="nav nav-pills">
				<li><?=CHtml::link('О проекте',$this->createUrl('/page/about'))?></li>
			</ul>
			<?php
				if ( !Yii::app()->user->isGuest && YII_DEBUG && Yii::app()->user->Role == "Administrator" ) {
					echo '<br/>Отработало за ' . sprintf('%0.5f', Yii::getLogger()->getExecutionTime()) . ' с. ';
					echo 'Скушано памяти: ' . round(memory_get_peak_usage() / (1024 * 1024), 2) . ' MB <br>';
					$sql_stats = YII::app()->db->getStats();
					echo $sql_stats[0] . ' запросов к БД, время выполнения запросов - ' . sprintf('%0.5f', $sql_stats[1]) . ' c.';
				}
			 ?>
			<p>&copy; 2011</p>
		</footer><!-- #footer -->

    </div> <!-- /container -->
  </body>
</html>