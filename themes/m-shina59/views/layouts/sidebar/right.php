<?php $this->beginContent('//layouts/main'); ?>

    <?php

        $this->widget('zii.widgets.CBreadcrumbs', array(
            'separator'=>'<span class="divider">/</span>',
            'htmlOptions'=>array(
                'class'=>'breadcrumb'
            ),
            'links'=>$this->breadcrumbs,
        ));

    ?>


    <div class="row-fluid">
        <div class="span9">1
            <?php echo $content; ?>
        </div>
        <div class="span3">
            <?php $this->widget('Manufacturers',array('id'=>7,'title'=>'Производители')); ?>
            <?php $this->widget('Categories',array('id'=>4,'title'=>'Шины')); ?>
        </div>
    </div>

<?php $this->endContent(); ?>