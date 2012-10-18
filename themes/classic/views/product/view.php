<?php
$this->pageTitle = $product->title;


echo $record->name;


$this->widget('zii.widgets.CDetailView', array(
    'data'=>$record
));

?>