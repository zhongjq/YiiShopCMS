<?php

class ListsController extends Controller
{
	public $layout='/layouts/main';

	protected function performAjaxValidation($list)
	{
      	if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "listsForm" ){
			echo CActiveForm::validate($list);
			Yii::app()->end();
		}
	}

	public function actionIndex(){

        $lists = new CActiveDataProvider('Lists',array('pagination'=>array('pageSize'=>'20')));

    	$this->render('index', array(
            "lists" => $lists
        ));

	}

    public function actionAdd(){
        $list = new Lists('add');

		$this->performAjaxValidation($list);

    	if( isset($_POST['Lists']) ) {
			$list->attributes = $_POST['Lists'];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $list->save() ){
					$transaction->commit();
					$this->redirect($this->createUrl('/admin/lists'));
				} else {
					throw new CException("Error save");
				}
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $form = new CForm($list->getCFormArray(),$list);

		$this->render('add', array(
            "form" => $form
        ));
	}

    public function actionEditList($ListID){

        $List = Lists::model()->with('ListsItems')->findbyPk($ListID);
        $List->setScenario('edit');
        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "listsForm" ){
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

        $form = new CForm($List->getCFormArray(),$List);

		$this->render('lists/edit', array(
            "Form" => $form
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

    public function actionItems($id){

        $list = Lists::model()->findbyPk($id);

        $criteria=new CDbCriteria;
		$criteria->compare('list_id',$id);
        $items = new CActiveDataProvider('ListItem',array('criteria'=>$criteria,'pagination'=>array('pageSize'=>'20')));

    	$this->render('items/index', array(
            "list" => $list,
            "items" => $items
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

        $form = new CForm($Item->getCFormArray(),$Item);

        $this->render('lists/items/edit', array(
            "Form" => $form,
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