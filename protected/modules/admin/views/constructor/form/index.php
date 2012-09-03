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
EQF;


Yii::app()->getClientScript()->registerScript("dragdrop",$js);

echo $form->render();

?>