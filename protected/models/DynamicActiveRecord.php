<?php

class DynamicActiveRecord
{
    protected static $_instance;

	private $class;

    public function getClass(){return $this->class;}

    public static function model($className,$scenario='insert'){

		if (!class_exists($className, false) )
			eval("class ".$className." extends CustemCActiveRecord {}");

        if (self::$_instance === null) {
            $class = new $className($scenario);

			$gen = new DynamicActiveRecord($class);
			self::$_instance = $gen->getClass();
        }

        return self::$_instance;
    }

    public function __construct($class)
    {
		//if ( class_exists($className, false) ) eval("class ".$className." extends CustemCActiveRecord {}");
		$this->class = $class;
        $this->class->productName = get_class($class);
		$this->class->product = Product::model()->find('alias = :alias', array(':alias'=> $this->class->productName ));
		$this->class->init();
    }

}
