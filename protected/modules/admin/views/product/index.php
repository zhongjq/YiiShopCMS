<?php
$this->breadcrumbs=array(
	'Товары',
);

$this->renderPartial('SecondMenu');

?>

<?php if ( empty($Products) ) : ?>
	<h3><?=Yii::t('AdminModule.main',"Товаров нет.")?></h3><br>
		<?= CHtml::link("Cоздать товар", array('create'),array('class'=>'btn btn-primary btn-large')) ?>

<?php else : ?>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th width="15">#</th>
		<th width="100">Стастус</th>
		<th>Название</th>
		<th width="50">Полей</th>
		<th width="90"></th>
		<th width="50"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($Products as $Product):?>
	<tr>
		<td><?php echo $Product->ID ?></td>
		<td>
			<?php if ($Product->Status == 1) : ?>
			<span class="label label-success">Активирован</span>
			<? else : ?>
			<span class="label label-important">Неактивирован</span>
			<?php endif ?>
		</td>
		<td><?= CHtml::link(CHtml::encode($Product->Name), array('view','ProductID'=>$Product->ID)); ?></td>
		<td><?php echo sizeof( $Product->productsFields() ) ?></td>
		<td><?= CHtml::link("Редактировать", array('edit','ProductID'=>$Product->ID)); ?></td>
		<td><?= CHtml::link("Удалить", array('delete','ProductID'=>$Product->ID)); ?></td>
	</tr>
	<?php endforeach ?>
	</tbody>
</table>

<?$this->widget('myLinkPager', array(
	'pages'			=> $pages,
	'cssFile'		=> false,
	'header'        => '',
	'firstPageLabel'=> '&laquo;',
	'prevPageLabel'	=> '&larr;',
	'nextPageLabel'	=> '&rarr;',
	'lastPageLabel' => '&raquo;',
	'htmlOptions'	=> array("class"=>"pagination"),
))?>

<?php endif ?>