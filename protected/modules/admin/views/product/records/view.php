<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$Product->ID,
);

$this->renderPartial('records/SecondMenu',array('Product'=>$Product));

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
		<?php if(!empty($f)) : ?>
		<th width="10"></th>
		<th width="10"></th> 
		<?php endif  ?>
	</tr>
	</thead>
	<tbody>

		<? foreach($Goods as $Record) : ?>
			<tr>
				<? foreach($Product->productsFields() as $Field) : ?>
					<?php if( $Field->IsColumnTable ) : ?>
						<td class="span2">
						<?php 
							switch( $Field->FieldType ) {
								case TypeFields::LISTS :

									if ( $Field->ListFields->IsMultipleSelect ) {
										if ( $Record->{$Field->Alias."Items"} ) {
											$Items = array();
											foreach( $Record->{$Field->Alias."Items"} as $Item ){
												$Items[] = $Item->Name;
											}            
											echo implode(', ', $Items);
										}                                    
									} else
										if ( $Record->{$Field->Alias."Item"} ) echo $Record->{$Field->Alias."Item"}->Name;
								break;
								default:
									echo $Record->{$Field->Alias};
							}                        
						?>
						</td>
					<?php endif; ?>
				<? endforeach ?>

				<td>
					<?= CHtml::link( '<span class="icon-pencil pointer" title="'.Yii::t('AdminModule.main','Редактировать').'"></span>',
						$this->createUrl('/admin/product/editrecord',array('ProductID'=>$Field->ProductID,'RecordID'=>$Record->ID) )
					) ?>
				</td>
				<td>
					<?= CHtml::link( '<span class="close" title="'.Yii::t('AdminModule.main','Удалить').'">&times;</span>',
						$this->createUrl('/admin/product/deleterecord',array('ProductID'=>$Field->ProductID,'RecordID'=>$Record->ID) )
					) ?>
				</td>
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