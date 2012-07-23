<?php
$this->breadcrumbs=array(
	'Товары',
);

$this->renderPartial('SecondMenu');

?>

<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th width="15"></th>
		<th width="15">#</th>
		<th width="100">Стастус</th>
		<th>Название</th>
		<th>Полей</th>
		<th width="110"></th>
	</tr>
	</thead>
	<tbody>
	<?foreach($Products as $Product):?>
	<tr>
		<td><?= CHtml::checkBox("users[]",'',array("value"=>$Product->ID));   ?></td>
		<td><?php echo $Product->ID ?></td>
		<td>
			<?php if ($Product->Status == 1) : ?>
			<span class="label label-success">Активирован</span>
			<? else : ?>
			<span class="label label-important">Неактивирован</span>
			<? endif ?>
		</td>
		<td><?php echo $Product->Name ?></td>
		<td><?php echo sizeof( $Product->productsFields() ) ?></td>
		<td><?= CHtml::link("Редактировать", array('edit','id'=>$Product->ID)) ?></td>
	</tr>
		<?endforeach?>
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
