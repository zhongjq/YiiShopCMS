<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$Product->ID,
);

$this->renderPartial('GoodsSecondMenu',array('Product'=>$Product));

?>

<?php if( $Goods ) : ?>
<?php $f = array(); ?>
<table id="Goods" class="table table-bordered table-striped">
	<thead>
	<tr>
		<? foreach($Product->productsFields() as $Field) : ?>
			<?php if( $Field->IsColumnTable ) : ?>
				<th><?=$Field->Name?></th>
				<?php $f[] = $Field->Alias; ?>
			<?php endif; ?>
		<? endforeach ?>
	</tr>
	</thead>
	<tbody>

		<? foreach($Goods as $Record) : ?>
			<tr>
				<? foreach($f as $name) : ?>
					<td class="span2"><?= $Record->getAttribute($name) ?></td>
				<? endforeach ?>
			</tr>
		<? endforeach ?>
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