<?php

class ProductController extends Controller
{
	public $layout='/layouts/main';

	public function actionIndex()
	{
		$criteria = new CDbCriteria();

		$count = Products::model()->count($criteria);

		$pages=new CPagination($count);
		// элементов на страницу
		$pages->pageSize=10;
		$pages->applyLimit($criteria);

		$Products = Products::model()->with('productsFields')->findAll($criteria);

		$this->render('index', array(
			'Products' => $Products,
			'pages' => $pages
		));
	}

	public function actionView($ProductID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);
		$Product->getAttributes();

		$Goods = $Product->getGoodsObject();
        
		
		$Goods = $Goods->with( $Goods->getRelationsNameArray() )->findAll();

        
		$this->render('records/view', array(
			'Product' => $Product,
			'Goods' => $Goods,
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
			if(isset($_POST['submit']) && $Goods->save()){
				$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
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
			if(isset($_POST['submit']) && $Goods->save()){
				$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
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

		if( $Goods->delete() ){
			$this->redirect($this->createUrl('/admin/product/view',array('ProductID'=>$Product->ID)));
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

		$Product = new Products('create');

		$this->performAjaxValidation($Product);

		$Form = $Product->getMotelCForm();

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$Product->attributes = $_POST['Products'];
				if( $Product->save() ){
					Yii::app()->db->createCommand()->createTable($Product->Alias, array(
						'ID' => 'pk',
						'Alias' => 'varchar(255)',
						'Title' => 'text',
						'Keywords' => 'text',
						'Description' => 'text',
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

		$this->render('create', array('Form'=>$Form) );
	}

	public function actionDelete($id)
	{
		Products::model()->findByPk($id)->delete();
		$this->redirect(array('/admin/product'));
	}

	public function actionEdit($ProductID)
	{
		$Product = Products::model()->findByPk($ProductID);

		$this->performAjaxValidation($Product);

		$Form = $Product->getMotelCForm();

		if(isset($_POST['Products']))
		{
			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$Product->attributes = $_POST['Products'];
				if( $Product->save() ){
					$transaction->commit();
					$this->redirect(array('/admin/product'));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}

		}

		$this->render('edit', array('Form'=>$Form,'Product' => $Product) );
	}


	public function actionFields($ProductID)
	{
		$Product = Products::model()->with('productsFields')->findByPk($ProductID);

        $criteria=new CDbCriteria;
    	$criteria->compare('ProductID',$ProductID);
        $Fields = new CActiveDataProvider('ProductsFields',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));
        
		$this->render('fields/index', array(
			'Product'   => $Product,
            'Fields'    => $Fields
		));
	}

	public function actionAddField($ProductID)
	{
		$Product = Products::model()->findByPk($ProductID);
		$ProductField = new ProductsFields('add');
		$ProductField->ProductID = $Product->ID;

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

							'ajax' => array(
								'type'  =>  'POST',
								'url'   =>  "",
								'replace'=>  '#FieldForm',
							)

						),
						'Name'=>array(
							'type'=>'text',
							'maxlength'=>255
						),
						'Alias'=>array(
							'type'      =>  'text',
							'maxlength' =>  255,
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
					'label' =>  'Создать',
					'class' =>  "btn"
				),
			),
		);

		$class = null;
		$FieldType = null;
		if ( isset($_POST['ProductsFields']['FieldType']) )
			$FieldType = $_POST['ProductsFields']['FieldType'];

		if ( $FieldType > 0 ){
			$ClassName = TypeFields::$Fields[$FieldType]['class'];
			$class = $ProductField->CreateField($FieldType);
			$ArrayForm['elements'][$ClassName] = $class->getElementsMotelCForm();
			$ProductField->moredata = $class;
		}

		$Form = new CForm($ArrayForm);
		$Form['ProductsFields']->model = $ProductField;
		if( $FieldType > 0 ) $Form[$ClassName]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" ){
			$validate = array($ProductField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductsFields']) ) {
			$ProductField->attributes = $_POST['ProductsFields'];
            
            // чтобы сохранять значение
            if( isset($_POST[$ClassName]) )
                $class->attributes = $_POST[$ClassName];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( isset($_POST[$ClassName]) && $ProductField->save() ){
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

		if( Yii::app()->request->isAjaxRequest && $FieldType > 0 ){
			$Form = $Form->render();
			$sc = '';
			Yii::app()->clientScript->render($sc);
			echo $Form.$sc;
			Yii::app()->end();
		}
        
		$this->render('fields/add', array(
			'Product' => $Product,
			'Form' => $Form,
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

		$FieldType = $ProductField->FieldType;
		$ClassName = TypeFields::$Fields[$FieldType]['class'];
		$class = $ProductField::CreateField($FieldType);
		$class = $class::model()->findByPk($FieldID);
		$ArrayForm['elements'][$ClassName] = $class->getElementsMotelCForm();
		$ProductField->moredata = $class;

		$Form = new CForm($ArrayForm);
		$Form['ProductsFields']->model = $ProductField;
		$Form[$ClassName]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "FieldForm" ){
			$validate = array($ProductField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductsFields']) ) {
			$ProductField->attributes = $_POST['ProductsFields'];

            // чтобы сохранять значение
            if( isset($_POST[$ClassName]) )
                $class->attributes = $_POST[$ClassName];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( isset($_POST[$ClassName]) && $ProductField->save() ){
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