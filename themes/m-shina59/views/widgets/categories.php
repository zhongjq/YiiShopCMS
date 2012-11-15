<div>
<?php
        $this->widget('zii.widgets.CMenu',array(
            'items'=> $categories,
            'encodeLabel'=> false,
            'htmlOptions'=> array('class'=>'nav nav-tabs nav-stacked')
        ));
?>
</div>