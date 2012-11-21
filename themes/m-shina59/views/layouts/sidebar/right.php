<?php $this->beginContent('//layouts/main'); ?>

    <div class="row-fluid">
        <div class="span9">
            <?php echo $content; ?>
        </div>
        <div class="span3">
			<?php echo $this->clips['search']; ?>
            <?php $this->widget('Manufacturers',array('id'=>1,'title'=>'Производители')); ?>
            <?php $this->widget('Categories',array('id'=>4,'title'=>'Шины')); ?>
        </div>
    </div>

<?php $this->endContent(); ?>