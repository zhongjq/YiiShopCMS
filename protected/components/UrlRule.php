<?php

class UrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		switch($route){
			case "products/index":
				return $params['Alias'].$manager->urlSuffix;
			break;
			case "products/view":
				if (isset($params['product'], $params['id']))
					return $params['product'] . '/' . $params['id'].$manager->urlSuffix;
				else if (isset($params['product'], $params['alias']))
					return $params['product'] . '/' . $params['alias'].$manager->urlSuffix;
			break;

			case "manufacturers/view":
				if (isset($params['alias']))
					return 'manufacturer/' . $params['alias'].$manager->urlSuffix;
			break;
		}
		return false;  // не применяем данное правило
	}

	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches))
		{
			// Ищем товар
			if ( Products::model()->find('Alias=:Alias', array(':Alias'=>$matches[1])) ){
				$_GET['alias'] = $matches[1];
				return 'products/index';
			}
		}
		return false;  // не применяем данное правило
	}
}