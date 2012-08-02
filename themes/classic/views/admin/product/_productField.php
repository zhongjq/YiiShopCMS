<tr>
	<td>
		<?= $Field->ID?>
	</td>
	<td>
		<?= TypeFields::$Fields[$Field->FieldType]['name'] ?>
	</td>
	<td>
		<?= $Field->Name?> (<?= $Field->Alias?>)
	</td>
	<td>
		<i class="<?= $Field->IsMandatory ? "icon-plus" : "icon-minus" ?>"></i>
	</td>
	<td>
		<i class="<?= $Field->IsFilter ? "icon-plus" : "icon-minus" ?>"></i>
	</td>
	<td>
		<?= CHtml::link( '<span class="icon-pencil pointer" title="'.Yii::t('AdminModule.main','Редактировать').'"></span>',
						$this->createUrl('/admin/product/editfield',array('id'=>$Field->ProductID,'FieldID'=>$Field->ID) )
		) ?>
	</td>
	<td>
		<?= CHtml::link( '<span class="close" title="'.Yii::t('AdminModule.main','Удалить').'">&times;</span>',
		$this->createUrl('/admin/product/deletefield',array('id'=>$Field->ProductID,'FieldID'=>$Field->ID) )
		) ?>
	</td>
</tr>