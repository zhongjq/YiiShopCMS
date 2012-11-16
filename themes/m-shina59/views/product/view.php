<?php

$this->pageTitle = $record->title;
$this->pageDescription = $record->description;
$this->pageKeywords = $record->keywords;

$this->breadcrumbs = array(
    CHtml::encode($product->name) => array('product/index','alias'=>$product->alias),
    CHtml::encode($record->title)
);

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$record
));



?>

<?php if ( $record->image ) : ?>
    <?php foreach($record->image as $image): ?>
        <img src="<?=$image->getURL()?>">
    <?php endforeach  ?>
<?php endif  ?>