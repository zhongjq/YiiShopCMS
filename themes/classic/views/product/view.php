<?php
$this->pageTitle = $record->title;

echo $record->name;


var_dump($record->title);

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$record
));



?>

<?php if ( $record->image ) : ?>
    <?php foreach($record->image as $image): ?>
        <img src="<?=$image->getURL()?>">
    <?php endforeach  ?>
<?php endif  ?>