<?php
/**
 * CJuiDateTimePicker class file.
 *
 * @author Anatoly Ivanchin <van4in@gmail.com>
 */

Yii::import('zii.widgets.jui.CJuiDatePicker');
class CJuiDateTimePicker extends CJuiDatePicker
{
    const ASSETS_NAME='/jquery-ui-timepicker-addon';

	public $mode='datetime';
	public $multiselect=false;

	public function init()
	{
		if(!in_array($this->mode, array('date','time','datetime')))
			throw new CException('unknow mode "'.$this->mode.'"');
		if(!isset($this->language))
			$this->language=Yii::app()->getLanguage();
		return parent::init();
	}

	public function run()
	{
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;


		if (  $this->multiselect ){

			//$id = $id.'picker';

			if($this->hasModel()){
				//echo CHtml::hiddenField($name,null,array('id'=>$id,'class'=>'btn'));
				echo CHtml::tag("ul",array('id'=>$id."list"));
				echo CHtml::closeTag("ul");

				$new = array();
				if ( is_array($this->model->{$this->attribute}) && !empty($this->model->{$this->attribute})){

					foreach ($this->model->{$this->attribute} as $d) {
						$date = new DateTime($d->datetime);
						$new[]= $date->format("Y-m-d");
					}

				}
				$this->model->{$this->attribute} = implode(',', $new);
				echo CHtml::activeHiddenField($this->model,$this->attribute,$this->htmlOptions);
			} else
				echo CHtml::textField($name,$this->value,$this->htmlOptions);


				$this->options['constrainInput'] = true;
				$this->options['showOn'] = 'button';
				$this->options['buttonText'] = 'Выберете даты';

				$options = CJavaScript::encode($this->options);

$options = substr($options,0,-1);

$options .= <<<EQF
,beforeShowDay: function (date){
	var day = addZero(date.getDate());
	var month = addZero(date.getMonth()+1);
	var d = date.getFullYear()+"-"+month+"-"+day ;
	if ( dates.indexOf(d) >= 0 )return [true,"ui-state-select"];
	return [true,""];
},
EQF;

$options .= <<<EQF
onSelect:function(date, init){
	var td = init.dpDiv.find('a.ui-state-hover').parent('td');
	var d = $.datepicker.parseDate(init.settings.dateFormat, date);
	var day = addZero(d.getDate());
	var month = addZero(d.getMonth()+1);
	var a = d.getFullYear()+"-"+month+"-"+day;
	var index = dates.indexOf(a);
	if ( index >= 0 ){
		window.dates.splice(index, 1);
		td.removeClass('ui-state-select');
	} else {
	   window.dates.push(a);
	   td.addClass('ui-state-select');
	}
	$(this).data('datepicker').inline = true;
},
EQF;

$options .= <<<EQF
onClose: function(date,init){
	var list = $("#{$id}list").empty();
	for(d in dates){
		var date = $.datepicker.formatDate(init.settings.dateFormat, new Date(dates[d]));
		list.append($("<li>").text(date));
	}
	$("#{$id}").val(window.dates.join(','))
	$(this).data('datepicker').inline = false;
},
EQF;

$options .= <<<EQF
create: function(event, ui) {
	window.dates = [];
	var list = $("#{$id}list").empty();
	window.dates = $("#{$id}").val();
window.dates = window.dates.length ? window.dates.split (",") : [];
if ( window.dates.length )
	for(var i in dates){
		var date = $.datepicker.parseDate("yy-mm-dd", dates[i]);
		list.append($("<li>").text($.datepicker.formatDate(this.dateFormat,date)));
	}

console.log(window.dates);
},
EQF;

$options .= "}";

				$js = "jQuery('#{$id}').{$this->mode}picker($options);";

				if (isset($this->language)){
					$this->registerScriptFile($this->i18nScriptFile);
					$js = "jQuery('#{$id}').{$this->mode}picker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$this->language}'], {$options}));";
				}

				$cs = Yii::app()->getClientScript();
$f = <<<F
//function list(){

// Workaround for missing datepickercreate event
(function( $ ) {
  var \$fndatepicker = $.fn.datepicker;
  $.fn.datepicker = function( options ) {
    $( this ).trigger( "datepickercreate" );
    options && options.create && options.create();
    return \$fndatepicker.apply( this, arguments );
  };
})( jQuery );

//}

F;
				$cs->registerScript('dates', $f.'function addZero(date){return date >9 ? date : "0"+date;}');

				$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
				$cs->registerCssFile($assets.self::ASSETS_NAME.'.css');
				$cs->registerScriptFile($assets.self::ASSETS_NAME.'.js',CClientScript::POS_END);

				$cs->registerScript(__CLASS__, 	$this->defaultOptions?'jQuery.{$this->mode}picker.setDefaults('.CJavaScript::encode($this->defaultOptions).');':'');
				$cs->registerScript(__CLASS__.'#'.$id, $js);

		} else {

			if($this->hasModel()){
				echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
			} else
				echo CHtml::textField($name,$this->value,$this->htmlOptions);


			$options = CJavaScript::encode($this->options);

			$js = "jQuery('#{$id}').{$this->mode}picker($options);";

			if (isset($this->language)){
				$this->registerScriptFile($this->i18nScriptFile);
				$js = "jQuery('#{$id}').{$this->mode}picker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$this->language}'], {$options}));";
			}

			$cs = Yii::app()->getClientScript();

			$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');
			$cs->registerCssFile($assets.self::ASSETS_NAME.'.css');
			$cs->registerScriptFile($assets.self::ASSETS_NAME.'.js',CClientScript::POS_END);

			$cs->registerScript(__CLASS__, 	$this->defaultOptions?'jQuery.{$this->mode}picker.setDefaults('.CJavaScript::encode($this->defaultOptions).');':'');
			$cs->registerScript(__CLASS__.'#'.$id, $js);
		}
	}
}