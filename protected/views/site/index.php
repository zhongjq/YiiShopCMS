<?php
    $this->pageTitle  = Yii::app()->name;
?>

	<div class="container-fluid">
        <div class="row-fluid">
		    <div id="citis" class="span3">
            <h1>Города</h1>
            <?php if(!empty($citys)) :?>
                <?php foreach($citys as $city) : ?>
                    <span id="city_<?php echo CHtml::encode($city->id)?>" class="view">

	                    <?php echo CHtml::link(CHtml::encode($city->name), array('city/index', 'city'=>$city->alias),array('title'=>CHtml::encode($city->name))); ?>


                    </span>
                <?php endforeach; ?>
			<?php else : ?>
                <h2>Городов нет</h2>
            <?php endif; ?>
		    </div>
        </div>
	</div>
