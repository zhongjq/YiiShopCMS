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
}