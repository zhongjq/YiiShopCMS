<?php

class LoaderFiles extends CUploadedFile{

	public static function Load($path){
		$loadFiles = CFileHelper::findFiles($path);

		if (is_array($loadFiles) && !empty($loadFiles) ){
			$files = array();
			foreach ($loadFiles as $key => $file) {
				$files[] = new CUploadedFile(basename($file), $file, CFileHelper::getMimeType($file) , filesize($file), 0);
			}
			return $files;
		} else
			return null;
	}
}