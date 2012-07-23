<tr>
	<td>
		<? if ( !$Field->isNewRecord ) : ?>
			<?= $Field->ID?>
		<? endif ?>
	</td>
	<td>
		<?= CHtml::hiddenField('Products[ProductField]['.$Field->ID.'][FieldType]',$Field->FieldType)?>
		<?= TypeFields::$Fields[$Field->FieldType]['name'] ?>
	</td>
	<td>
		<?= CHtml::hiddenField('Products[ProductField]['.$Field->ID.'][Name]',$Field->Name)?>
		<?= CHtml::hiddenField('Products[ProductField]['.$Field->ID.'][Alias]',$Field->Alias)?>
		<?= $Field->Name?> (<?= $Field->Alias?>)
	</td>
	<td>
		<?= CHtml::hiddenField('Products[ProductField]['.$Field->ID.'][IsMandatory]',$Field->IsMandatory)?>
		<i class="<?= $Field->IsMandatory ? "icon-plus" : "icon-minus" ?>"></i>
	</td>
	<td>
		<?= CHtml::hiddenField('Products[ProductField]['.$Field->ID.'][IsFilter]',$Field->IsFilter)?>
		<i class="<?= $Field->IsFilter ? "icon-plus" : "icon-minus" ?>"></i>
	</td>
	<td><span class="icon-pencil pointer" title="<?=Yii::t('AdminModule.main','Редактировать')?>">&times;</span></td>
	<td>
		<?php if ( !$Field->IsSystem ) : ?>
			<span class="close">&times;</span>
		<?php else : ?>
			&nbsp;
		<?php endif ?>
	</td>
</tr>