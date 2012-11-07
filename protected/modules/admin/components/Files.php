<?php

class Files extends CInputWidget
{

	public $options=array();

	public function run()
	{

		list($name,$id)=$this->resolveNameID();

        //if(substr($name,-2)!=='[]') $name.='[0]';

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		$this->registerClientScript();

		echo CHtml::openTag('table',array('class'=>"table"));
			echo CHtml::openTag('tbody',array());

                $nameField = $this->attribute;
        		if( isset($this->model->{$nameField})  ){
                    // получаем имеющиеся файлы
                    $files = File::model()->findAll('product_id = :product_id AND record_id = :record_id',array(":product_id"=> $this->model->getProductID(),':record_id'=> $this->model->id));

        			foreach ($files as $file) {
            			echo CHtml::openTag('tr',array());
        					echo CHtml::openTag('td',array());
        						echo CHtml::link($file->name, $file->getUrl() );
        					echo CHtml::closeTag('td');
        					echo CHtml::openTag('td',array());
        						echo CHtml::textField($name.'[existing]['.$file->id.'][description]',$file->description,array('placeholder'=>Yii::t('fields', 'Описание')));
        					echo CHtml::closeTag('td');
        					echo CHtml::openTag('td',array());
        						echo CHtml::link('<span class="close deletetr" title="'.Yii::t('main','Delete').'">&times;</span>',"#", array('onclick'=>"$(this).closest('tr').remove();"));
        					echo CHtml::closeTag('td');
        				echo CHtml::closeTag('tr');
        			}
        		}

                if(substr($name,-2)!=='[]') $name.='[0]';
				echo CHtml::openTag('tr',array());
					echo CHtml::openTag('td',array());
						echo CHtml::fileField($name,'',$this->htmlOptions);
					echo CHtml::closeTag('td');
					echo CHtml::openTag('td',array());
						echo CHtml::textField($name.'[description]','',array('placeholder'=>Yii::t('fields', 'Описание')));
					echo CHtml::closeTag('td');
					echo CHtml::openTag('td',array());
						echo CHtml::link('<span class="close deletetr" title="'.Yii::t('main','Delete').'">&times;</span>',"#", array(
							'onclick'=>"$(this).closest('tr').remove();",
							'style'=>"display:none;"
						));
					echo CHtml::closeTag('td');
				echo CHtml::closeTag('tr');

			echo CHtml::closeTag('tbody');
			echo CHtml::openTag('tfoot',array());
				echo CHtml::openTag('tr',array());
					echo CHtml::openTag('td',array('colspan'=>3));
						echo CHtml::link(Yii::t('fields', 'Добавить еще файл'),"#", array('id'=>'addFile'));
					echo CHtml::closeTag('td');
				echo CHtml::closeTag('tr');
			echo CHtml::closeTag('tfoot');

		echo CHtml::closeTag("table");


	}

	/**
	 * Registers the needed CSS and JavaScript.
	 */
	public function registerClientScript()
	{
$js = <<<JS
$(function(){
	jQuery("#addFile").click(function(){

		var tbody = $(this).closest("table").find("tbody");



		var tr = tbody.find("tr:last").clone()
					.find("input").attr('name', function(i, val) {
						var name = val.match(/[\d+]/)[0];
						return val.replace(/[\d+]/, parseInt(name) + 1 )
					}).end()
					.find("input").val("").end()
					.find("a").show().end();


		console.log(name);

	   tbody.append( tr );

	   return false;
	});
});

JS;

		$cs=Yii::app()->getClientScript();
		$cs->registerScript('files',$js);
	}

}