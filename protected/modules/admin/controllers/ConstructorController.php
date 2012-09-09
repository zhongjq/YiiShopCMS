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
        $products = new CActiveDataProvider('Product',array('pagination'=>array('pageSize'=>'20')));

		$this->render('index', array(
			'products' => $products
		));
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
		$product = Product::model()->with('productFields')->findByPk($id);

        $criteria=new CDbCriteria;
    	$criteria->compare('product_id',$id);
		$criteria->order = 'position';
        $fields = new CActiveDataProvider('ProductField',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

		$this->render('fields/index', array(
			'product'   => $product,
            'fields'    => $fields
		));
	}

	public function actionAddField($id)
	{
		$product = Product::model()->findByPk($id);
		$productField = new ProductField('add');
		$productField->product_id = $product->id;

		$arProductFieldForm = $productField->getMotelArrayCForm();

		$class = null;
		$fieldType = null;
		$className = null;
		if ( isset($_POST['ProductField']['field_type']) )
			$fieldType = $_POST['ProductField']['field_type'];

		if ( $fieldType > 0 && isset(TypeField::$Fields[$fieldType]['class']) ){
			$className = TypeField::$Fields[$fieldType]['class'];
			$class = $productField->CreateField($fieldType);
			$arProductFieldForm['elements'][$className] = $class->getElementsMotelCForm();
			$productField->moredata = $class;
		}

		$form = new CForm($arProductFieldForm);
		$form['productField']->model = $productField;
		if( $fieldType > 0 && $class ) $form[$className]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "fieldForm" ){
			$validate = array($productField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductField']) ) {
			$productField->attributes = $_POST['ProductField'];

            // чтобы сохранять значение
            if( $className && isset($_POST[$className]) )
                $class->attributes = $_POST[$className];

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

	public function actionEditField($productId,$fieldId)
	{
		$product = Product::model()->findByPk($productId);
		$product->setScenario('edit');
		$productField = ProductField::model()->findByPk($fieldId);
		$productField->setScenario('edit');

		$arProductFieldForm = $productField->getMotelArrayCForm();

		$class = null;
		$fieldType = null;
		$className = null;
		$fieldType = $productField->field_type;
		if ( isset(TypeField::$Fields[$fieldType]['class']) ){
			$className = TypeField::$Fields[$fieldType]['class'];
			$class = $productField::CreateField($fieldType);
			$class = $class::model()->findByPk($fieldId);
			$arProductFieldForm['elements'][$className] = $class->getElementsMotelCForm();
			$productField->moredata = $class;
		}


		$form = new CForm($arProductFieldForm);
		$form['productField']->model = $productField;

		if ( $class ) $form[$className]->model = $class;

		if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "fieldForm" ){
			$validate = array($productField);
			if ( $class ) $validate[] = $class;
			echo CActiveForm::validate($validate);
			Yii::app()->end();
		}

		if( isset($_POST['ProductField']) ) {
			$productField->attributes = $_POST['ProductField'];

            // чтобы сохранять значение
            if( $className && isset($_POST[$className]) )
                $class->attributes = $_POST[$className];

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
				$this->redirect($this->createUrl('/admin/product/fields',array('id'=>$productId)));
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

		$arTabs = array(
			array("id"=>-1,"name"=>"Общее","content"=>"")
		);

        /*
        if ( $product->productFields() ){
            foreach($product->productFields() as $p){
                var_dump( $p->fieldTab );
            }
        }
        */

        $record = $product->getRecordObject();

        $tab = $record->getProductTab();

        $a = '';
        if ( $tab ){
            foreach($tab as $t){

                $idelement = "deltab".$t->id;

                $l = CHtml::ajaxButton('×', $this->createUrl('/admin/constructor/deletetab', array('productId'=>$id,"tabId"=>$t->id)),
                                            array('type'=>'POST','success' => 'function(){ $("#'.$idelement.'").closest("li").remove(); }'),
                                            array('id' => $idelement,"class"=>"close")
                                        );

                $a .= '<li id="tab_'.$t->id.'"><a href="#tab'.$t->id.'" data-toggle="tab">'.$t->name.$l.'</a></li>';
            }
        }

        $elementsSEO = Record::getSEOFieldsArray();
    	$form = array(
    		'attributes' => array(
				'enctype' => 'multipart/form-data',
				'class' => 'well',
				'id' => "recordForm",
			),
			'elements' => array(
				'<div class="tabbable">',
					'<ul class="nav nav-tabs">
						<li id="tab_0" class="active"><a href="#tab1" data-toggle="tab">Общее</a></li>',$a,
                        '<li class="exclude"><a href="#seoTab" data-toggle="tab">SEO</a></li>
                        <li class="exclude"><a id="addTab" href="javascript:void(0);"><i class="icon-plus"></i></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab1"></div>',
     					'<div id="seoTab" class="tab-pane exclude">',
                            'alias' => array('type'=>'text','class'=>"span5",'maxlength' => 255),
                            'title' => array('type'=>'textarea','class'=>"span5",'rows' => 5),
                            'keywords' => array('type'=>'textarea','class'=>"span5",'rows' => 5),
                            'description' => array('type'=>'textarea','class'=>"span5",'rows' => 5),
                        '</div>',
                    '</div>',
                '</div>'
			)
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

}