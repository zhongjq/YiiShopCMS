<div>
<h3><?= $title ?></h3>
<?php if ( $manufacturers )  $this->widget('zii.widgets.CMenu',array('items'=> $manufacturers,encodeLabel=>false)); ?>
</div>