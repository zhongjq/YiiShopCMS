<div>
<?php 
    if ( $manufacturers )
        $this->widget('zii.widgets.CMenu',array(
            'items'=> $manufacturers,
            'encodeLabel'=> false,
            'htmlOptions'=> array('class'=>'nav nav-tabs nav-stacked')
        )); 
?>
</div>