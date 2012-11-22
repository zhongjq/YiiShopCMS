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


		echo CHtml::openTag('script',array('id'=>'trTemplate','type'=>'text/x-jquery-tmpl'));
			echo CHtml::openTag('tr');
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
					echo CHtml::textField($name.'[0][regex]',null,array());
				echo CHtml::closeTag('td');                
				echo CHtml::openTag('td',array());
					echo CHtml::link('<span class="close deletetr" title="'.Yii::t('main','Delete').'">&times;</span>',"#", array(
						'onclick'=>"$(this).closest('tr').remove();",
						'style'=>"display:none;"
					));
				echo CHtml::closeTag('td');
			echo CHtml::closeTag('tr');
		echo CHtml::closeTag('script');

    		echo CHtml::openTag('table',array('class'=>"table"));
				echo CHtml::openTag('tbody',array());
					$key = 0;
					if ( !empty($this->model->importFields) ){
						foreach ($this->model->importFields as $key => $value) {
							echo CHtml::openTag('tr',array());
								echo CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'['.$key.'][to]',$value['to'], $listFiels,array('empty'=>'') );
								echo  CHtml::closeTag('td');
								echo  CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'['.$key.'][param]',$value['param'], array(0=>"=",1=>">") );
								echo CHtml::closeTag('td');
								echo CHtml::openTag('td',array());
									echo CHtml::dropDownList($name.'['.$key.'][from]',$value['from'], CHtml::listData($this->model->fields,'id','name'),array('empty'=>'') );
								echo CHtml::closeTag('td');
                        		echo CHtml::openTag('td',array());
                					echo CHtml::textField($name.'['.$key.'][regex]',$value['regex'],array());
                				echo CHtml::closeTag('td');
                				echo CHtml::openTag('td',array());
            						echo CHtml::link('<span class="close deletetr" title="'.Yii::t('main','Delete').'">&times;</span>',"#", array(
            							'onclick'=>"$(this).closest('tr').remove();",
            						));
            					echo CHtml::closeTag('td');
							echo CHtml::closeTag('tr');
						}
					}
				echo CHtml::closeTag('tbody');

				echo CHtml::openTag('tfoot',array());
					echo CHtml::openTag('tr',array());
						echo CHtml::openTag('td',array('colspan'=>4));
							echo CHtml::link(Yii::t('fields', 'Добавить'),"#", array('id'=>'addFile'));
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

		var tr = $("#trTemplate").html();

		tbody.append( tr );

		tbody.find("tr:last")
					.find("select").attr('name', function(i, val) {
						var tr = tbody.find("tr").eq(-2);
						var name = tr.length > 0 ? tr.find("select:first").attr('name').match(/[\d+]/)[0] : 0;
						$(this).find("option:selected").prop("selected", false);
						return val.replace(/[\d+]/, parseInt(name) + 1 )
					}).end()
    				.find("input").attr('name', function(i, val) {
						var tr = tbody.find("tr").eq(-2);
						var name = tr.length > 0 ? tr.find("input:first").attr('name').match(/[\d+]/)[0] : 0;
						return val.replace(/[\d+]/, parseInt(name) + 1 )
					}).end()                    
					.find("a").show().end();

		return false;
	});
});

JS;

		$cs=Yii::app()->getClientScript();
		$cs->registerScript('files',$js);
	}

}