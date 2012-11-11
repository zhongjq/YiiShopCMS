<?php

class ImportFields extends CInputWidget
{

	public $options=array();

	public function run()
	{

		list($name,$id) = $this->resolveNameID();

        //if(substr($name,-2)!=='[]') $name.='[0]';

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		$this->registerClientScript();

        $listFiels = array();
    	for($i = 1; $i<=$this->model->countImportFields;$i++)
				$listFiels[] = Yii::t('product','Col #'.$i);

    		echo CHtml::openTag('table',array('class'=>"table"));
				echo CHtml::openTag('tbody',array());
					if ( !empty($this->model->importFields) )
						foreach ($this->model->importFields as &$value) {
							echo CHtml::openTag('tr',array());
								echo CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'[0][to]',$value['to'], $listFiels,array('empty'=>'') );
								echo  CHtml::closeTag('td');
								echo  CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'[0][param]',$value['param'], array(0=>"=",1=>">") );
								echo CHtml::closeTag('td');
								echo CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'[0][from]',$value['from'], CHtml::listData($this->model->fields,'id','name'),array('empty'=>'') );
								echo CHtml::closeTag('td');
								echo CHtml::openTag('td',array());
									echo CHtml::link('<span class="close deletetr" title="'.Yii::t('main','Delete').'">&times;</span>',"#", array('onclick'=>"$(this).closest('tr').remove();"));
								echo CHtml::closeTag('td');
							echo CHtml::closeTag('tr');
						} else {
							echo CHtml::openTag('tr',array());
								echo CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'[0][to]',null, $listFiels,array('empty'=>'') );
								echo  CHtml::closeTag('td');
								echo  CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'[0][param]',1, array(0=>"=",1=>">") );
								echo CHtml::closeTag('td');
								echo CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'[0][from]',null, CHtml::listData($this->model->fields,'id','name'),array('empty'=>'') );
								echo CHtml::closeTag('td');
								echo CHtml::openTag('td',array());
									echo CHtml::link('<span class="close deletetr" title="'.Yii::t('main','Delete').'">&times;</span>',"#", array('onclick'=>"$(this).closest('tr').remove();"));
								echo CHtml::closeTag('td');
							echo CHtml::closeTag('tr');
						}
				echo CHtml::closeTag('tbody');

				echo CHtml::openTag('tfoot',array());
					echo CHtml::openTag('tr',array());
						echo CHtml::openTag('td',array('colspan'=>3));
							echo CHtml::link(Yii::t('fields', 'Еще'),"#", array('id'=>'addFile'));
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
					.find("select").attr('name', function(i, val) {
						var name = val.match(/[\d+]/)[0];
						$(this).find("option:selected").prop("selected", false);
						return val.replace(/[\d+]/, parseInt(name) + 1 )
					}).end()
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