<div>
<h3><?= $title ?></h3>
<?php if ( $products )  $this->widget('zii.widgets.CMenu',array('items'=> $products,'encodeLabel'=>false)); ?>
</div>