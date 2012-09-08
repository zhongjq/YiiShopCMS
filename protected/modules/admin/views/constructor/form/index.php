<?php
$this->pageTitle = Yii::t('products',"Add field product.");

$this->breadcrumbs=array(
    Yii::t("products","Constructor Goods") => array('index'),
	Yii::t('products',"Fields product")." ".$product->name => $this->createUrl('/admin/constructor/fields',array('id'=>$product->id)),
	Yii::t('products',"Add field"),
);


Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/chosen/chosen.css');
Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/chosen/chosen.jquery.min.js');

Yii::app()->getClientScript()->registerScript("select",'$("form select").chosen({allow_single_deselect:true});');


$js = <<<EQF
$( "div.tabbable ul.nav" ).sortable({
    items:"li:not(.exclude)", 
    axis: "x",
    update: function(event, ui) {
        var newOrder = $(this).sortable('toArray').toString();
        console.log($(this).sortable('serialize'));
    }
});

$( "div.tab-pane:not(.exclude)" ).sortable().disableSelection();

$( "ul:first li:not(.exclude)", "div.tabbable" ).droppable({
	tolerance: "touch",
	accept: "div.row:not(.exclude)",
	hoverClass: "ui-state-hover",
	drop: function( event, ui ) {
		var \$item = $( this );
		var \$list = $( \$item.find( "a" ).attr( "href" ) );
		ui.draggable.hide( "slow", function() {
			$( this ).appendTo( $list ).show( "slow" );
		});
	}
});

$("#addTab").click(function(){
$('#addTabModal').modal({
        backdrop: true,
        keyboard: true
    }).css({
        width: 'auto',
        'margin-left': function () {
            return -($(this).width() / 2);
        }
    });
/*
    var tabbable = $(this).closest("div.tabbable");
    var content = tabbable.find("div.tab-content");
    var id = "t1";
    var a = $("<a>").text("123").attr({"href":"#"+id,"data-toggle":"tab"});
    var li = $("<li></li>").append(a);
    tabbable.find("ul:first li:not(.exclude)").after(li);
    var div = $("<div></div>").attr("id",id).text(123).addClass("tab-pane");
    content.append(div);
*/
});

$("div.tabbable .close1").click(function(){
    $(this).closest("li").remove();
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


