<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Редактирование товара #'.$Product->ID => $this->createUrl('/admin/product/edit',array('id'=>$Product->ID)),
	'Поля товара',
);

$this->renderPartial('FieldsSecondMenu',array('Product'=>$Product));

?>

<?php if( $Product->productsFields ) : ?>
<table id="ProductField" class="table table-bordered table-striped">
	<thead>
	<tr>
		<th width="20">ID</th>
		<th class="span2">Тип поля</th>
		<th>Наименование</th>
		<th width="100">Обязательно</th>
		<th width="100">Фильтр</th>
		<th width="10"></th>
		<th width="10"></th>
	</tr>
	</thead>
	<tbody>
		<? if ( $Product->productsFields() ) : ?>
		<? foreach($Product->productsFields() as $Field) : ?>
			<? $this->renderPartial('_productField', array('Field'=>$Field)); ?>
			<? endforeach ?>
		<? endif ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7">
			</td>
		</tr>
	</tfoot>
</table>
<?php else : ?>
	<h3><?=Yii::t('AdminModule.products',"У товара нет полей.")?></h3><br>
	<?= CHtml::link("Добавить поле",
					$this->createUrl('/admin/product/addfield',array('id'=>$Product->ID)),
					array('class'=>'btn btn-primary btn-large')) ?>

<?php endif; ?>