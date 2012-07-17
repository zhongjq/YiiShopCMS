<?php

class ProductController extends Controller
{
	public $layout='/layouts/main';

	public function actionIndex()
	{
		$this->render('index');
	}	
	
	public function actionProduct()
	{
		$this->render('index');
	}

	public function actionAdd()
	{
		$this->render('index');
	}

	public function actionCreate()
	{
		$Product = new Products();

		if(isset($_POST['Products']))
		{
			$Product->attributes=$_POST['Products'];
			if($Product->save()){
				$this->redirect(array('view','id'=>$Product->ID));
			}

		}

		$this->render('create', array( "Product" => $Product ) );
	}
}