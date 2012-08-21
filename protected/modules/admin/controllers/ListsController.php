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

        $list = Lists::model()->with('ListsItems')->findbyPk($ListID);
        $list->setScenario('edit');
        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "listsForm" ){
			echo CActiveForm::validate($list);
			Yii::app()->end();
		}

    	if( isset($_POST['Lists']) ) {
			$list->attributes = $_POST['Lists'];

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				if ( $list->save() ){
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

        $form = new CForm($list->getCFormArray(),$list);

		$this->render('lists/edit', array(
            "Form" => $form
        ));
	}

    public function actionDeleteList($ListID){

        $list = Lists::model()->findbyPk($ListID);

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if ( $list->delete() ){
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

    public function actionAddItems($id){

        $list = Lists::model()->findbyPk($id);

        $listItem = new ListItem('add');
        $listItem->list_id = $id;

        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "additems-form" ){
    		echo CActiveForm::validate($listItem);
			Yii::app()->end();
		}

         if( isset($_POST['ListItem'])  ) {

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
                $items = explode("\n",$_POST['ListItem']['name']);

                foreach( $items as $item_name ){
                    $IL = new ListItem('add');
                    $IL->list_id = $id;
                    $IL->name = $item_name;
          			if ( !$IL->save() ){
    					throw new CException("Error save");
    				}
                }

				$transaction->commit();
				$this->redirect( $this->createUrl('/admin/lists/items',array('id'=>$id)) );
			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $this->render('items/add', array(
            "list" => $list,
            "listItem" => $listItem,
        ));
	}

	public function actionEditItem($listId, $itemId){

        $item = ListItem::model()->with('list')->find('List_id = :listId AND t.id = :id', array(':listId'=>$listId,':id'=>$itemId));
        $item->setScenario('edit');

        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "itemForm" ){
        	echo CActiveForm::validate($item);
			Yii::app()->end();
		}

		$form = new CForm($item->getCFormArray(),$item);

        if( isset($_POST['ListItem'])  ) {

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
				$item->attributes = $_POST['ListItem'];
				if ( $form->submitted() && $item->save() ){
					$transaction->commit();
					$this->redirect( $this->createUrl('/admin/lists/items',array('id'=>$listId)) );
				}else{
					throw new CException("Error save");
				}

			}
			catch(Exception $e) // в случае ошибки при выполнении запроса выбрасывается исключение
			{
				$transaction->rollBack();
			}
		}

        $this->render('items/edit', array(
            "form" => $form,
            "item" => $item,
        ));
	}

    public function actionDeleteItem($listId, $itemId){

        $item = ListItem::model()->with('list')->find('List_id = :listId AND t.id = :id', array(':listId'=>$listId,':id'=>$itemId));

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if( $item->delete() ){
				$transaction->commit();
				$this->redirect( $this->createUrl('/admin/lists/items',array('id'=>$listId)) );
			}
		}
		catch(Exception $e)
		{
			$transaction->rollBack();
		}
	}
}