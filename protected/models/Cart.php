<?php

class Cart extends CModel {

    static private $instance = NULL;

    public static function model(){
        if (self::$instance == NULL){
            self::$instance = new self();
        }
        return self::$instance;
    }

	public function __construct() {
		if ( Yii::app()->user->getState('cart') === null )
			Yii::app()->user->setState('cart',array());
	}

	public function attributeNames(){
        return array();
    }

    public function attributeLabels(){
        return array();
    }

    public function quantity(){
		$quantity = 0;
		$cart = Yii::app()->user->getState('cart');
		if (!empty($cart)) {
			foreach ($cart as &$value) {
				$quantity += $value['quantity'];
			}
		}
        return $quantity;
    }

    public function price(){
		$price = 0;
		$cart = Yii::app()->user->getState('cart');
		if (!empty($cart)) {
			foreach ($cart as &$value) {
				$price += $value['price'];
			}
		}
        return $price;
    }

    public function add($product,$id){

		$command = Yii::app()->db->createCommand();

		$product = $command->from(Product::tableName())->where('alias=:alias', array(':alias'=>$product))->setFetchMode(PDO::FETCH_OBJ)->queryRow();

		if ( $product ){
			$record = $command->select('id, price')->from($product)->where('id=:id', array(':id'=>$id))->setFetchMode(PDO::FETCH_OBJ)->queryRow();

			if ($record){
				$s[] = array("productId"=>$product->id,
											"recordId"=>$id,
											"quantity"=>1,
											"price"=>$record->price
											);

				Yii::app()->user->setState('cart',$s);
			}
			return true;
		}

    }

    public function clear(){
		Yii::app()->user->setState('cart',array());
    }

	public function getCart() {
		return Yii::app()->user->getState('cart');
	}
}
