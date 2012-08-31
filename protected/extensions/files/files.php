<?php

class Files extends CMultiFileUpload
{

	/**
	 * Runs the widget.
	 * This method registers all needed client scripts and renders
	 * the multiple file uploader.
	 */
	public function run()
	{
		parent::run();

        $name = md5($this->attribute);        
		if ( is_array($this->model->{$name}) && empty($this->model->{$name}) == true ){
			$files = array();
			foreach ($this->model->{$name} as $file) {
				$files[$file->getName()] = $file->getName();
			}
            
            $name = get_class($this->model)."[".$name."]";            
			echo CHtml::checkBoxList($name, null, $files);

		}

	}

}