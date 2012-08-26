<?php

class UrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		switch($route){
			case "product/index":
				return $params['alias'].$manager->urlSuffix;
			break;
			case "product/view":

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
		if (preg_match('%^(\w+)(/(\d+))?$%', $pathInfo, $matches))
		{
			// Ищем товар
			if ( isset($matches[1],$matches[3]) ){
				$_GET['product'] = $matches[1];
				$_GET['id'] = $matches[3];
				return 'product/viewId';
			}
		}

		if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches))
		{
			// Ищем товар
			if ( isset($matches[1],$matches[3]) ){
				$_GET['product'] = $matches[1];
				$_GET['alias'] = $matches[3];
				return 'product/viewAlias';
			}
		}
		return false;  // не применяем данное правило
	}
}