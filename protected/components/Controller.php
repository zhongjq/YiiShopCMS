<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout='//layouts/sidebar/right';
    public $pageDescription = null;
    public $pageKeywords = null;
    
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu	=	array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs	=	array();

	private $_assetsBase;
	public function getAssetsBase()
	{
		if ($this->_assetsBase === null) {
				$this->_assetsBase = Yii::app()->assetManager->publish(
						Yii::getPathOfAlias('webroot.themes.'.Yii::app()->theme->name.'.assets'),
						false,
						-1,
						YII_DEBUG
				);
		}
		return $this->_assetsBase;
	}

}