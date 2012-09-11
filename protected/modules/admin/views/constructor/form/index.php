<?php
$this->pageTitle = Yii::t('products',"Add field product.");

$this->breadcrumbs=array(
    Yii::t("products","Constructor Goods") => array('index'),
	Yii::t('products',"Constructor Form"),
);


Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/chosen/chosen.css');
Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/chosen/chosen.jquery.min.js');

Yii::app()->getClientScript()->registerScript("select",'$("form select").chosen({allow_single_deselect:true});');

$savePositionTabs = $this->createUrl('/admin/constructor/savePositionTabs',array('id'=>$product->id));
$savePositionField = $this->createUrl('/admin/constructor/savePositionField',array('id'=>$product->id));
$savePositionFields = $this->createUrl('/admin/constructor/savePositionFields',array('id'=>$product->id));
$js = <<<EQF
$( "div.tabbable ul.nav" ).sortable({
    items:"li:not(.exclude)",
    axis: "x",
    update: function(event, ui) {        
        $.post( "$savePositionTabs" , $(this).sortable('serialize') );       
    }
});

$( "div.tab-pane:not(.exclude)" ).sortable({
    update: function(event, ui) {        
        var fields = new Array; 
        $(this).find("*[name^='{$product->alias}\\[']").each(function(index,field) {
            fields.push( $(field).attr('name').match(/\[(.*)\]/)[1] );
        });
        var tabId = $(this).attr('id').replace("content_","");
        $.post( "$savePositionFields" , {'fields':fields, "tabId":tabId} );
    }
}).disableSelection();

$( "ul:first li:not(.exclude)", "div.tabbable" ).droppable({
	tolerance: "touch",
	accept: "div.row:not(.exclude)",
	hoverClass: "tab-hover",
	drop: function( event, ui ) {
		var item = $( this );
		var list = $( item.find( "a" ).attr( "href" ) );
		ui.draggable.hide( "slow", function() {
            var draggable = $( this )
			draggable.appendTo( list ).show( "slow" );
            var name = draggable.find("*[name^='{$product->alias}\\[']").attr('name').match(/\[(.*)\]/)[1];
            var tabId = list.attr('id').replace("content_","");
            $.post( "$savePositionField" , { "fieldName":name, "tabId":tabId } );
		});
	}
});

$("#addTab").click(function(){
	$('#addTabModal').modal({backdrop: true,keyboard: true}).css({width: 'auto','margin-left': function () {return -($(this).width() / 2);}});
});


EQF;


Yii::app()->getClientScript()->registerScript("dragdrop",$js);

echo $form->render();

?>


<?php $activeForm=$this->beginWidget('CActiveForm', array(
    'id'=>'addTabModal',
    'action'=>$this->createUrl('/admin/constructor/addtab',array('id'=>$product->id)),
    'method'=>'post',
    'enableClientValidation'=>false,
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
		'validateOnSubmit' => true,
        'validateOnChange' => false,
	),
    'htmlOptions'=>array("class"=>"modal hide span3","role"=>"dialog","tabindex"=>"-1","aria-labelledby"=>"myModalLabel","aria-hidden"=>"true")
)); ?>
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h4 id="myModalLabel">Добавление вкладки</h4>
</div>
<div class="modal-body row">
    <?php echo $activeForm->labelEx($tab, 'name'); ?>
    <?php echo $activeForm->textField($tab, 'name'); ?>
    <?php echo $activeForm->error($tab, 'name'); ?>
</div>
<div class="modal-footer">
<?php echo CHtml::htmlButton('Отмена', array('type'=>'submit',"class"=>"btn","data-dismiss"=>"modal","aria-hidden"=>"true")); ?>
<?php echo CHtml::htmlButton('Создать',array('type' => 'submit',"class"=>"btn btn-primary")); ?>
</div>
<?php $this->endWidget(); ?>


