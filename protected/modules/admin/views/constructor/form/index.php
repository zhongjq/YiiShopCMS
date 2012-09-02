<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->getClientScript()->registerCssFile($this->assetsBase.'/chosen/chosen.css');
Yii::app()->getClientScript()->registerScriptFile($this->assetsBase.'/chosen/chosen.jquery.min.js');

Yii::app()->getClientScript()->registerScript("select",'$(function(){$("form select").chosen({allow_single_deselect:true});});');

echo $form->render();


?>

	<script>
	$(function() {
		$( "div.tab-pane" ).sortable().disableSelection();

		$( "ul:first li", "div.tabbable" ).droppable({
			tolerance: "touch",
			accept: "div.row",
			hoverClass: "ui-state-hover",
			drop: function( event, ui ) {
				var $item = $( this );
				var $list = $( $item.find( "a" ).attr( "href" ) );
				ui.draggable.hide( "slow", function() {
					$( this ).appendTo( $list ).show( "slow" );
				});
			}
		});

	});
	</script>