<?php

class ConstructorController extends Controller
{
	public $layout='/layouts/main';

	protected function performAjaxValidation($model)
	{
		if(Yii::app()->request->isAjaxRequest )
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionIndex()
	{
        //$products = new CActiveDataProvider('Product',array('pagination'=>array('pageSize'=>'20')));
        
        $products = Product::model()->findAll();
        
        $products = new CArrayDataProvider($products);
        
		$this->render('index', array(
			'products' => $products
		));
	}

	public function actionCreate()
	{
		$product = new Product();
        
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
		Product::model()->findByPk($id)->delete();
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

	public function actionFields($id)
	{
		$product = Product::model()->findByPk($id);

        $criteria=new CDbCriteria;
    	$criteria->compare('product_id',$id);
		$criteria->order = 'position';
        $fields = new CActiveDataProvider('ProductField',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('fields/index', array(
			'product'   => $product,
            'fields'    => $fields
		));
	}

    protected function performAjaxFieldValidation($productField)
	{
    	if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "fieldForm" ){
			$validate = array($productField);
			if ( $productField->subClass ) $validate[] = $productField->subClass;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}
	}

	public function actionAddField($id)
	{
		$product = Product::model()->findByPk($id);
		$productField = new ProductField('add');
		$productField->product_id = $product->id;
        
        if( isset($_POST['ProductField']) ) $productField->attributes = $_POST['ProductField'];
                        
		$form = $productField->getCForm();

		$this->performAjaxFieldValidation($productField);

		if( isset($_POST['ProductField']) ) {
			$productField->attributes = $_POST['ProductField'];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $form->submitted() && $productField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/constructor/fields',array('id'=>$product->id)));
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				Yii::app()->user->setFlash('error',$e->getFile()."<br/>". $e->getLine().": ". $e->getMessage());
				$transaction->rollBack();
			}
		}

		if( Yii::app()->request->isAjaxRequest && $productField->subClass ){
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

	public function actionEditField($productId,$fieldId)
	{
		$product = Product::model()->findByPk($productId);
		$product->setScenario('edit');
		$productField = ProductField::model()->findByPk($fieldId);
		$productField->setScenario('edit');

    	$form = $productField->getCForm();

    	$this->performAjaxFieldValidation($productField);

		if( isset($_POST['ProductField']) ) {
			$productField->attributes = $_POST['ProductField'];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $form->submitted() && $productField->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/constructor/fields',array('id'=>$product->id)));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				Yii::app()->user->setFlash('error',$e->getFile()."<br/>". $e->getLine().": ". $e->getMessage());
				$transaction->rollBack();
			}
		}

		$this->render('fields/edit', array(
			'product' => $product,
			'form' => $form,
		));
	}

	public function actionDeleteField($productId,$fieldId)
	{
		$productField = ProductField::model()->find('id=:id AND product_id=:product_id',array(':id'=>$fieldId,':product_id'=>$productId));

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $productField && $productField->delete() ){
				$transaction->commit();
				$this->redirect($this->createUrl('/admin/constructor/fields',array('id'=>$productId)));
			}
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
	}

	public function actionSorting()
	{
		if (isset($_POST['fields']) && is_array($_POST['fields'])) {
			$i = 0;
			foreach ($_POST['fields'] as $field_id) {
				Yii::app()->db->createCommand()->update('product_field', array('position'=>$i++), 'id=:id', array(':id'=>$field_id));
			}
		}
	}

	public function actionForm($id) {

		$product = Product::model()->with('productFields')->findByPk($id);
        $record = $product->getRecordObject();
        
    	$form = array(
    		'attributes' => array(
				'class' => 'well',
			),
			'elements' => $record->getTabsFormElements()
		);

		$form = new CForm($form, $record );

		$this->render('form/index', array(
			'product' => $product,
			'form' => $form,
            'tab'=> new Tab('add')
		));
	}

    public function actionAddTab($id){
        $this->layout=false;
        $tab = new Tab('add');
        $tab->product_id = $id;

    	if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "addTabModal" ){
			echo CActiveForm::validate($tab);
			Yii::app()->end();
		}

        if (isset($_POST['Tab'])){
            $tab->name = $_POST['Tab']['name'];
            if ( $tab->save() ){
                $this->redirect($this->createUrl('/admin/constructor/form',array('id'=>$id)));
            }
        }
    }

    public function actionDeleteTab($productId,$tabId){
        $this->layout=false;
        $tab = Tab::model()->findByPk(array("id"=>$tabId,"product_id"=>$productId));

        if ( $tab && $tab->delete() ){
            $this->redirect($this->createUrl('/admin/constructor/form',array('id'=>$productId)));
        }
    }

    public function actionSavePositionTabs($id){
        $this->layout=false;
        $isСommon = false;
        $command = Yii::app()->db->createCommand();
        $tabs = $_POST['tab'];
       
        if ( !empty($tabs) ){
            foreach($tabs as $position => $id){
                $position++;
                if ( $id == 0 ) $isСommon = true;                
                if ( $id != 0 ) {                
                    $command->update('tab', array('position'=> $position * ( $isСommon ? 1 : -1 ) ), 'id=:id', array(':id'=>$id));
                }
            }
        }
        
    }

    public function actionSavePositionField($id){
        $this->layout=false;        
        
        $fieldName = $_POST['fieldName'];
        $tabId = $_POST['tabId'];
        $field = ProductField::model()->find('alias = :alias and product_id = :product_id',array(":alias"=>$fieldName,":product_id"=>$id));
        
        if ( $field ){
            FieldTab::model()->deleteAll('field_id =:field_id',array(":field_id"=>$field->id));
            
            if ( $tabId > 0 ) {
                $fieldTab = new FieldTab('add');
                $fieldTab->field_id = $field->id;
                $fieldTab->tab_id = $tabId;
                $fieldTab->save();
            }
        }
        
    }
  
    public function actionSavePositionFields($id){
        $this->layout=false;        
        
        $fields = $_POST['fields'];
        $tabId = $_POST['tabId'];
        $command = Yii::app()->db->createCommand();
        
        if ( !empty($fields) ){
            
            foreach($fields as $position => $fieldAlias){
                //$command->update('product_field', array('position'=> $position), 'alias = :alias and product_id = :product_id',array(":alias"=>$fieldAlias,":product_id"=>$id));      
                $field = ProductField::model()->find('alias = :alias and product_id = :product_id',array(":alias"=>$fieldAlias,":product_id"=>$id));
                
                if ( $field ){
                    FieldTab::model()->deleteAll('field_id =:field_id',array(":field_id"=>$field->id));                    
                    
                    $fieldTab = new FieldTab('add');
                    $fieldTab->field_id = $field->id;
                    $fieldTab->tab_id = $tabId > 0 ? $tabId : null;
                    $fieldTab->position = $position;
                    $fieldTab->save();
                    
                }               
            }
        }
        

        
    }  
}