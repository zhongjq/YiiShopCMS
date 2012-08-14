<div class="span8">
<h3><?= $title ?></h3>
<? $Children = $Category->children()->findAll() ?>
<? if ( $Children ) : ?>
	<? $Menu = array() ?>
	<?php
		foreach($Children as $Node  ) {
			$Menu[] = array('label'     =>  CHtml::encode($Node->Name),
							'url'       =>  array('/categories/view/','Alias'=>$Node->Alias),
							'active'    =>  CHttpRequest::getParam('Alias') == $Node->Alias,
							'items'     =>  array(
								array('label'=>'Level 4 One', 'url'=>array('product/new')),
								array('label'=>'Level 4 One', 'url'=>array('product/new')),
								array('label'=>'Level 4 Two', 'url'=>array('product/index')),
							)
						);
		}
	?>

	<?php $this->widget('zii.widgets.CMenu',array(
		'items'=>$Menu,

	));


	?>

<? endif ?>
</div>
