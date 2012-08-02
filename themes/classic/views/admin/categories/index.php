<?php
$this->breadcrumbs=array(
	'Категории',
);

$this->SecondMenu=array(
	array('label'=>Yii::t("AdminModule.main", "Добавить категорию"), 'url'=>array('add')),
);
?>

<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th width="15"></th>
		<th width="15">#</th>
		<th width="100">Стастус</th>
		<th>Наименование</th>
		<th width="110"></th>
	</tr>
	</thead>
	<tbody>
	<?foreach($Categories as $Category):?>
	<tr>
		<td><?= CHtml::checkBox("Categories[]",$Category->Status,array("value"=>$Category->ID));   ?></td>
		<td><?php echo $Category->ID ?></td>
		<td>
			<?php if ($Category->Status == 1) : ?>
			<span class="label label-success">Активирован</span>
			<? else : ?>
			<span class="label label-important">Неактивирован</span>
			<? endif ?>
		</td>
		<td style="<?php
			if ($Category->Level > 1)
				echo "padding-left:".(20*$Category->Level)."px"
			?>"><?php echo $Category->Name ?></td>
		<td><?= CHtml::link("Редактировать", array('/admin/categories/edit','id'=>$Category->ID)) ?></td>
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
