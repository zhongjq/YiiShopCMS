<?php

/* var $product Product */
/* var $record CustemCActiveRecord */

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

<?php if ( $record->imageFile ) : ?>
    <?php foreach($record->imageFile as $image): ?>
        <img src="<?=$image->getURL()?>">
    <?php endforeach  ?>
<?php endif  ?>