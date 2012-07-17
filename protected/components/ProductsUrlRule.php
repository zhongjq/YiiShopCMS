<?php

class ProductsUrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		if ($route==='products/view')
		{
			if (isset($params['product'], $params['id']))
				return $params['product'] . '/' . $params['id'].$manager->urlSuffix;
			else if (isset($params['product']))
				return $params['product'];
		}
		return false;  // не применяем данное правило
	}

	public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
	{
		if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches))
		{
			//print_r($matches);
			if ( Products::model()->find('Alias=:Alias', array(':Alias'=>$matches[1])) )
				return 'site/index';
			// Проверяем $matches[1] и $matches[3] на предмет
			// соответствия производителю и модели в БД.
			// Если соответствуют, выставляем $_GET['manufacturer'] и/или $_GET['model']
			// и возвращаем строку с маршрутом 'car/index'.
			//
		}
		return false;  // не применяем данное правило
	}
}