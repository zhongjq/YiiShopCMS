<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	public $layout='/layouts/main';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $FirstMenu	=	array();
	public $SecondMenu	=	array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs	=	array();


	public function accessRules()
	{
		return array(
			array(  'allow',    // allow admin user to perform 'admin' and 'delete' actions
					'roles'     =>  array('Administrator')
			),
			array(  'allow',    // allow admin user to perform 'admin' and 'delete' actions
					'actions'   =>  array('error'),
					'users'     =>  array('*'),
			),
			array(  'deny',     // deny all users
					'users'     =>  array('*'),
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	/**
	 * Транслитерация
	 * @param type $name
	 * @return type
	 */
	public static function translit($name){

		$char_division = "_";

		$ru=array(	"а","б","в","г","д","е","ё",	"ж",	"з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч",	"ш",	"щ","ъ",	"ы","ь","э",	"ю",	"я");
		$tr=array(	"a","b","v","g","d","e","oh",	"zh",	"z","i","j","k","l","m","n","o","p","r","s","t","u","f","x","c","ch",	"sh",	"w","qh",	"y","",	"eh",	"ju",	"ya");


		$alfalower = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
		$alfaupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');

		$title = str_replace( $ru,$tr, str_replace($alfaupper, $alfalower, trim($name) ) );


		// Preserve escaped octets.
		$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
		// Remove percent signs that are not part of an octet.
		$title = str_replace('%', '', $title);
		// Restore octets.
		$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

		$title = strtolower($title);
		$title = preg_replace('/&.+?;/', '', $title); // kill entities
		$title = str_replace('.', $char_division, $title);
		$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
		$title = preg_replace('/\s+/', $char_division, $title);
		$title = preg_replace('|-+|', $char_division, $title);
		$title = trim($title, '-');

		return	$title;
	}
}