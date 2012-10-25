<?php $this->beginContent('//layouts/main'); ?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span3">
        <?php echo $this->clips['sidebar'] ?>
    </div>
    <div class="span9">
        <?php echo $content; ?>    
    </div>
  </div>
</div>

<?php $this->endContent(); ?>