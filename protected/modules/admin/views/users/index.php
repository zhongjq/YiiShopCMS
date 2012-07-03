<?php
$this->breadcrumbs=array(
	'Пользователи',
);

$this->SecondMenu=array(
	array('label'=>'Добавить', 'url'=>array('create'))
);
?>

<table class="table table-bordered table-striped">
	<thead>
	    <tr>
			<th width="15"></th>
			<th width="15">#</th>
			<th width="100">Стастус</th>
			<th>Дата/время регистрации</th>
			<th>Email</th>
			<th width="110"></th>
			<th width="110"></th>
		</tr>
	</thead>
	<tbody>
	<?foreach($Users as $User):?>
		<tr>
			<td><?= CHtml::checkBox("users[]",$User->Status,array("value"=>$User->ID));   ?></td>
			<td><?php echo $User->ID ?></td>
			<td>
				<?php if ($User->Status == 0) : ?>
					<span class="label label-success">Активирован</span>
				<? else : ?>
					<span class="label label-important">Неактивирован</span>
				<? endif ?>
			</td>
			<td><?php echo $User->RegistrationDateTime ?></td>
			<td><?php echo $User->Email ?></td>
			<td><?= CHtml::link("Редактировать", array('/admin/users/edit','id'=>$User->ID)) ?></td>
			<td><?= CHtml::link("Изменить пароль", array('/admin/users/passwordedit','id'=>$User->ID)) ?></td>
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
