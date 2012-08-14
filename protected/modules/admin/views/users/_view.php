<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('ID')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->ID), array('view', 'id'=>$data->ID)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Status')); ?>:</b>
	<?php echo CHtml::encode($data->Status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RoleID')); ?>:</b>
	<?php echo CHtml::encode($data->RoleID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RegistrationDateTime')); ?>:</b>
	<?php echo CHtml::encode($data->RegistrationDateTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ServiceID')); ?>:</b>
	<?php echo CHtml::encode($data->ServiceID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ServiceUserID')); ?>:</b>
	<?php echo CHtml::encode($data->ServiceUserID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Email')); ?>:</b>
	<?php echo CHtml::encode($data->Email); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Password')); ?>:</b>
	<?php echo CHtml::encode($data->Password); ?>
	<br />

	*/ ?>

</div>