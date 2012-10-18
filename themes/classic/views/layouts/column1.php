<?php $this->beginContent('//layouts/main'); ?>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="span3">
                    <?php // $this->widget('Products', array("title"=>"Продуксты") ) ?>
    			    <?php // $this->widget('Categories', array('title'=>"Категории",'id'=>1) ) ?>
					<?php // $this->widget('Manufacturers', array("title"=>"Производители") ) ?>
    </div>
    <div class="span9">
      <?php echo $content; ?>    
    </div>
  </div>
</div>

<?php $this->endContent(); ?>