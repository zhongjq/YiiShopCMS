<?php
Yii::import('zii.widgets.jui.CJuiInputWidget');
class datePicker extends CJuiInputWidget
{
	public $mode='datetime';
	public $language;
	public $defaultOptions;

	public function init()
	{
		if(!isset($this->language))
			$this->language=Yii::app()->getLanguage();

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

		if($this->hasModel())
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textField($name,$this->value,$this->htmlOptions);


		$options=CJavaScript::encode($this->options);

		$js = "jQuery('#{$id}').{$this->mode}picker($options);";

		if (isset($this->language)){
			//$this->registerScriptFile($this->i18nScriptFile);

			$js = "jQuery('#{$id}').datePicker(
						{
							createButton:false,
							displayClose:true,
							closeOnSelect:false,
							selectMultiple:true
						}
					)
					.bind(
						'click',
						function()
						{
							$(this).dpDisplay();
							this.blur();
							return false;
						}
					)
					.bind(
						'dateSelected',
						function(e, selectedDate, \$td, state)
						{
							console.log('You ' + (state ? '' : 'un') // wrap
								+ 'selected ' + selectedDate);

						}
					)
					.bind(
						'dpClosed',
						function(e, selectedDates)
						{
							console.log('You closed the date picker and the ' // wrap
								+ 'currently selected dates are:');
							console.log(selectedDates);
						}
					);


			";
		}

		$cs = Yii::app()->getClientScript();

		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR);
		$cs->registerCssFile($assets.'/datePicker.css');
		$cs->registerScriptFile($assets.'/date.js',CClientScript::POS_END);
		$cs->registerScriptFile($assets.'/jquery.datePicker.js',CClientScript::POS_END);

		$cs->registerScript(__CLASS__, 	$this->defaultOptions?'jQuery.{$this->mode}picker.setDefaults('.CJavaScript::encode($this->defaultOptions).');':'');
		$cs->registerScript(__CLASS__.'#'.$id, $js);

	}
}