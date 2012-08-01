<?php

class ProductsUrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		switch($route){
			case "products/index":
				return $params['Alias'].$manager->urlSuffix;
			break;
			case "products/view":
				if (isset($params['Product'], $params['id']))
					return $params['Product'] . '/' . $params['id'].$manager->urlSuffix;
				else if (isset($params['Product'], $params['Alias']))
					return $params['Product'] . '/' . $params['Alias'].$manager->urlSuffix;
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
				$_GET['Alias'] = $matches[1];
				return 'products/index';
			}
		}
		return false;  // не применяем данное правило
	}
}