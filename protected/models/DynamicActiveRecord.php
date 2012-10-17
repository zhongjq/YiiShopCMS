<?php

class DynamicActiveRecord
{
    protected static $_instance;
    public $productName;
    public $product;

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
		// if ( !class_exists($className, false) ) eval("class ".$className." extends CActiveRecord {}");
		$this->class = $class;
        $this->productName = $this->class->productName = get_class($class);
		$this->product = $this->class->product = Product::model()->find('alias = :alias', array(':alias'=> $this->productName ));

		$this->class->init();
    }


}
