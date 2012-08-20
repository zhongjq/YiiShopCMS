<?php

class ProductController extends Controller
{
	public $layout='/layouts/main';

	public function actionIndex()
	{    
    	$criteria = new CDbCriteria();
		$criteria->with = 'productsFields';
        $products	= new CActiveDataProvider('Product',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('index', array(
			'products' => $products
		));        
	}

	public function actionView($ProductID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Product->getAttributes();

		$Goods = $Product->getGoodsObject();

		$IsColumnTable = array();
		foreach($Product->productsFields() as $Field) {
			if( $Field->IsColumnTable ) $IsColumnTable[] = $Field->Alias;
		}

        $criteria = new CDbCriteria;
        //$criteria->select = implode(',', $IsColumnTable);
        $criteria->with = $Goods->getRelationsNameArray();
        $GoodsData = new CActiveDataProvider($Goods,array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('records/view', array(
			'Product'   => $Product,
			'Goods'     => $Goods,
            'GoodsData' => $GoodsData
		));
	}

	public function actionAdd($ProductID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Goods = $Product->getGoodsObject();

		$extPth = CHtml::asset($this->module->getlayoutPath().'/js/chosen/');
        Yii::app()->getClientScript()->registerCssFile($extPth.'/chosen.css');
        Yii::app()->getClientScript()->registerScriptFile($extPth.'/chosen.jquery.js');

		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "GoodsForm" )
		{
			echo CActiveForm::validate($Goods);
			Yii::app()->end();
		}

		if(isset($_POST[$Goods->tableName()])) {
			$Goods->attributes = $_POST[$Goods->tableName()];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if(isset($_POST['submit']) && $Goods->save()){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		$Form = $Goods->getMotelCForm();

		$this->render('records/add',array('Product'=>$Product,'Form'=>$Form));
	}

    public function actionEditRecord($ProductID,$RecordID)
	{

		$extPth = CHtml::asset($this->module->getlayoutPath().'/js/chosen/');
        Yii::app()->getClientScript()->registerCssFile($extPth.'/chosen.css');
        Yii::app()->getClientScript()->registerScriptFile($extPth.'/chosen.jquery.js');


		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Goods = $Product->getGoodsObject();
        $Goods = $Goods->findByPk($RecordID);
        $Goods->setProductID($ProductID);

		if(Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "GoodsForm" )
		{
			echo CActiveForm::validate($Goods);
			Yii::app()->end();
		}

		if(isset($_POST[$Goods->tableName()])) {
			$Goods->attributes = $_POST[$Goods->tableName()];
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if(isset($_POST['submit']) && $Goods->save()){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		$Form = $Goods->getMotelCForm();

		$this->render('records/edit',array('Product'=>$Product,'Form'=>$Form));
	}

    public function actionDeleteRecord($ProductID,$RecordID)
    {
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Goods = $Product->getGoodsObject();
        $Goods = $Goods->findByPk($RecordID);
        $Goods->setProductID($ProductID);

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $Goods->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
			}
		}
		catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
		{
			$transaction->rollBack();
		}
	}

	protected function performAjaxValidation($model)
	{
		if(Yii::app()->request->isAjaxRequest )
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionCreate()
	{

		$product = new Product('create');

		$this->performAjaxValidation($product);

		$form = $product->getMotelCForm();

		if(isset($_POST['Product']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$product->attributes = $_POST['Product'];
				if( $product->save() ){
					Yii::app()->db->createCommand()->createTable($product->alias, array(
						'id' => 'pk',
						'alias' => 'varchar(255)',
						'title' => 'text',
						'keywords' => 'text',
						'description' => 'text',
					), 'ENGINE=InnoDB');

					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('create', array('form'=>$form) );
	}

	public function actionDelete($id)
	{
		Products::model()->findByPk($id)->delete();
		$this->redirect(array('/admin/product'));
	}

	public function actionEdit($id)
	{
		$product = Product::model()->findByPk($id);

		$this->performAjaxValidation($product);

		$form = $product->getMotelCForm();

		if(isset($_POST['Product']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$product->attributes = $_POST['Product'];
				if( $product->save() ){
					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('edit', array('form'=>$form,'product' => $product) );
	}


	public function actionFields($productId)
	{
		$product = Product::model()->with('productsFields')->findByPk($productId);

        $criteria=new CDbCriteria;
    	$criteria->compare('product_id',$productId);
        $fields = new CActiveDataProvider('ProductField',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('fields/index', array(
			'product'   => $product,
            'fields'    => $fields
		));
	}

	public function actionAddField($productId)
	{
		$product = Product::model()->findByPk($productId);
		$productField = new ProductField('add');
		$productField->product_id = $product->id;

		$arProductFieldForm = $productField->getMotelArrayCForm();

		$class = null;
		$fieldType = null;
		$className = null;
		if ( isset($_POST['ProductsFields']['FieldType']) )
			$FieldType = $_POST['ProductsFields']['FieldType'];

		if ( $FieldType > 0 && isset(TypeFields::$Fields[$FieldType]['class']) ){
			$className = TypeFields::$Fields[$FieldType]['class'];
			$class = $ProductField->CreateField($FieldType);
			$ArrayForm['elements'][$className] = $class->getElementsMotelCForm();
			$ProductField->moredata = $class;
		}

		$form = new CForm($arProductFieldForm);
		$form['productField']->model = $productField;
		if( $fieldType > 0 && $class ) $form[$className]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "fieldForm" ){
			$validate = array($ProductField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductsFields']) ) {
			$ProductField->attributes = $_POST['ProductsFields'];

            // чтобы сохранять значение
            if( $className && isset($_POST[$className]) )
                $class->attributes = $_POST[$ClassName];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $ProductField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/fields',array('ProductID'=>$Product->ID)));
				} else {
					throw new CException("Error save");
				}

			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		if( Yii::app()->request->isAjaxRequest && $fieldType > 0 ){
			$form = $form->render();
			$sc = '';
			Yii::app()->clientScript->render($sc);
			echo $form.$sc;
			Yii::app()->end();
		}

		$this->render('fields/add', array(
			'product' => $product,
			'form' => $form,
		));
	}

	public function actionEditField($ProductID,$FieldID)
	{
		$Product = Products::model()->findByPk($ProductID);
		$Product->setScenario('edit');
		$ProductField = ProductsFields::model()->findByPk($FieldID);
		$ProductField->setScenario('edit');

		$ArrayForm = array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'FieldForm'
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'id' => "FieldForm",
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnChange' => false,
				),
			),

			'elements'=>array(
				'ProductsFields'=> array(
					'type'=>'form',
					'elements'=>array(
						'FieldType'=>array(
							'type'  =>  'dropdownlist',
							'items' =>  TypeFields::getFieldsList(),
							'empty'=>  '',
							'disabled'=>'disabled',
							'ajax' => array(
								'type'  =>  'POST',
								'url'   =>  "",
								'update'=>  '#FieldForm',
							)

						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
						'Alias'=>array(
							'type'      =>  'text',
							'maxlength' =>  255,
							'disabled'=>'disabled',
						),
						'IsMandatory'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'IsFilter'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'IsColumnTable'=>array(
							'type'=>'checkbox',
							'layout'=>'{input}{label}{error}{hint}',
						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
					)
				)
			),

			'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  'Сохранить',
					'class' =>  "btn"
				),
			),
		);

		$class = null;
		$FieldType = null;
		$className = null;
		$FieldType = $ProductField->FieldType;
		if ( isset(TypeFields::$Fields[$FieldType]['class']) ){
			$className = TypeFields::$Fields[$FieldType]['class'];
			$class = $ProductField::CreateField($FieldType);
			$class = $class::model()->findByPk($FieldID);
			$ArrayForm['elements'][$className] = $class->getElementsMotelCForm();
			$ProductField->moredata = $class;

		}


		$Form = new CForm($ArrayForm);
		$Form['ProductsFields']->model = $ProductField;

		if ( $class ) $Form[$className]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" ){
			$validate = array($ProductField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductsFields']) ) {
			$ProductField->attributes = $_POST['ProductsFields'];

            // чтобы сохранять значение
            if( $className && isset($_POST[$className]) )
                $class->attributes = $_POST[$className];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $ProductField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/product/fields',array('ProductID'=>$Product->ID)));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

		$this->render('fields/edit', array(
			'Product' => $Product,
			'Form' => $Form,
		));
	}

	public function actionDeleteField($ProductID,$FieldID)
	{
		$ProductField = ProductsFields::model()->find('ID=:ID AND ProductID=:ProductID',array(':ID'=>$FieldID,'ProductID'=>$ProductID));

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $ProductField->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/product/fields',array('ProductID'=>$ProductID)));
			}
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
	}


	/// LIST
	public function actionLists(){

        $Lists = new CActiveDataProvider('Lists',array('pagination'=>array('pageSize'=>'20')));

    	$this->render('lists/index', array(
            "Lists" => $Lists
        ));

	}

    public function actionAddList(){
        $List = new Lists('add');


      	if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "ListsForm" ){
			echo CActiveForm::validate($List);
			Yii::app()->end();
		}

    	if( isset($_POST['Lists']) ) {
			$List->attributes = $_POST['Lists'];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $List->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/products/lists'));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $Form = new CForm($List->getCFormArray(),$List);

		$this->render('lists/add', array(
            "Form" => $Form
        ));
	}

    public function actionEditList($ListID){

        $List = Lists::model()->with('ListsItems')->findbyPk($ListID);
        $List->setScenario('edit');
        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "ListsForm" ){
			echo CActiveForm::validate($List);
			Yii::app()->end();
		}

    	if( isset($_POST['Lists']) ) {
			$List->attributes = $_POST['Lists'];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $List->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/products/lists'));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $Form = new CForm($List->getCFormArray(),$List);

		$this->render('lists/edit', array(
            "Form" => $Form
        ));
	}

    public function actionDeleteList($ListID){

        $List = Lists::model()->findbyPk($ListID);

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if ( $List->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/products/lists'));
			} else {
				throw new CException("Error save");
			}
		}
		catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
		{
			$transaction->rollBack();
		}
	}

    public function actionItemsList($ListID){

        $List = Lists::model()->with('ListsItems')->findbyPk($ListID);

        $criteria=new CDbCriteria;
		$criteria->compare('ListID',$ListID);
        $ListsItems = new CActiveDataProvider('ListsItems',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

    	$this->render('lists/items/index', array(
            "List" => $List,
            "ListsItems" => $ListsItems
        ));
	}

    public function actionAddItems($ListID){

        $List = Lists::model()->with('ListsItems')->findbyPk($ListID);

        $ItemsList = new ListsItems('add');
        $ItemsList->ListID = $ListID;

        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "additems-form" ){
    		echo CActiveForm::validate($ItemsList);
			Yii::app()->end();
		}

         if( isset($_POST['ListsItems'])  ) {

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
                $Items = explode("\n",$_POST['ListsItems']['Name']);

                foreach( $Items as $Item ){
                    $IL = new ListsItems('add');
                    $IL->ListID = $ListID;
                    $IL->Name = $Item;
          			if ( !$IL->save() ){
    					throw new CException("Error save");
    				}
                }

				$transaction->commit();
				$this->redirect($this->createUrl('/admin/products/lists'));
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $this->render('lists/items/add', array(
            "List" => $List,
            "ItemsList" => $ItemsList,
        ));
	}
    public function actionEditItem($ListID, $ItemID){

        $Item = ListsItems::model()->with('List')->find('ListID = :ListID AND t.ID = :ID', array(':ListID'=>$ListID,':ID'=>$ItemID));
        $Item->setScenario('edit');

        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "additems-form" ){
        	echo CActiveForm::validate($ItemsList);
			Yii::app()->end();
		}

        if( isset($_POST['ListsItems'])  ) {

			$transaction = Yii::app()->db->beginTransaction();
			try
			{

          			if ( $IL->save() ){
                        $transaction->commit();
                        $this->redirect($this->createUrl('/admin/products/lists'));
                    }else{
    					throw new CException("Error save");
    				}

			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $Form = new CForm($Item->getCFormArray(),$Item);

        $this->render('lists/items/edit', array(
            "Form" => $Form,
            "Item" => $Item,
        ));
	}

    public function actionDeleteItem($ListID, $ItemID){

        $Item = ListsItems::model()->with('List')->find('ListID = :ListID AND t.ID = :ID', array(':ListID'=>$ListID,':ID'=>$ItemID));

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $Item->delete() ){
				$transaction->commit();
				$this->redirect( $this->createUrl('/admin/product/itemslist',array('ListID'=>$ListID)) );
			}
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
	}
}