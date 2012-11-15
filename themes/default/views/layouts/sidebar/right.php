<?php $this->beginContent('//layouts/main'); ?>

    <div class="span3">
        <?php echo $this->clips['sidebar'] ?>
    </div>
    <div class="span9">
        <?php echo $content; ?>    
    </div>

<?php $this->endContent(); ?>