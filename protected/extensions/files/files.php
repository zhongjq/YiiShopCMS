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


		if ( is_array($this->model->{$this->attribute}) && !empty($this->model->{$this->attribute})){
			$files = array();
			foreach ($this->model->{$this->attribute} as $file) {
				$files[$file->getName()] = $file->getName();
			}

			echo CHtml::checkBoxList($this->attribute."Delete", null, $files);

		}

	}

}