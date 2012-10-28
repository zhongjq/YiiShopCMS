<?php

class UrlRule extends CBaseUrlRule
{
	public $connectionID = 'db';

	public function createUrl($manager,$route,$params,$ampersand)
	{
		switch($route){
			case "product/index":
                $alias = $params['alias'];
				$url = $alias.$manager->urlSuffix;

                if ( isset( $params['page'] ) )
                    $url .= '?page='.$params['page'];

                return $url;
			break;
			case "product/view":

				if (isset($params['product'], $params['id']))
					return $params['product'] . '/' . $params['id'].$manager->urlSuffix;
				else if (isset($params['product'], $params['alias']))
					return $params['product'] . '/' . $params['alias'].$manager->urlSuffix;

			break;

			case "product/addtocart":

				if (isset($params['product'], $params['id']))
					return $params['product'] . '/' . $params['id'].'/addtocart'.$manager->urlSuffix;
				else if (isset($params['product'], $params['alias']))
					return $params['product'] . '/' . $params['alias'].'/addtocart'.$manager->urlSuffix;

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
		if (preg_match('%^(\w+)(/(\d+))?$%', $pathInfo, $matches)){
		    // просмотр единицы товара по id
			if ( isset($matches[1],$matches[3]) ){
				$_GET['product'] = $matches[1];
				$_GET['id'] = $matches[3];
				return 'product/viewId';
			}
		}

		if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)){
			// просмотр единицы товара по alias
			if ( isset($matches[1],$matches[3]) ){
				$_GET['product'] = $matches[1];
				$_GET['alias'] = $matches[3];
				return 'product/viewAlias';
			}
		}

    	if (preg_match('%^(\w+)?$%', $pathInfo, $matches)){
			// просмотр товара
			if ( isset($matches[1]) && !in_array($matches[1],array('admin','importcsv'))  ){
				$_GET['alias'] = $matches[1];
				return 'product/index';
			}
		}

		return false;  // не применяем правило
	}
}