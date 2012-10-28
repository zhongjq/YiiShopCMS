<?php

class ListsController extends Controller
{
    /**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		$rules = array_merge(
			parent::accessRules(),
			array(
				array(  'allow',    // allow admin user to perform 'admin' and 'delete' actions
						'actions'   =>  array('admin','delete'),
						'roles'     =>  array('Administrator')
				),
				array(  'deny',  // deny all users
						'users'=>array('*'),
				)
			)
		);

		return $rules;
	}

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

    public function actionEditList($id){

        $list = Lists::model()->findbyPk($id);
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

		$this->render('edit', array(
            "form" => $form
        ));
	}

    public function actionDeleteList($id){

        $list = Lists::model()->findbyPk($id);

		$transaction = Yii::app()->db->beginTransaction();
		try
		{
			if ( $list->delete() ){
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

        $listItem = new ListItem('addItems');
        $listItem->list_id = $id;

        if( Yii::app()->request->isAjaxRequest && isset($_POST['ajax']) && $_POST['ajax'] == "additems-form" ){
    		echo CActiveForm::validate($listItem);
			Yii::app()->end();
		}

        if( isset($_POST['ListItem']) ) {

			$transaction = Yii::app()->db->beginTransaction();
			try
			{
                $items = explode("\n",$_POST['ListItem']['name']);

				if ( !empty($items) ){
					foreach( $items as $item_name ){
						$IL = new ListItem('add');
						$IL->list_id = $id;
						$IL->name = trim($item_name);
						if ( !$IL->save() ){
							throw new CException("Error save");
						}
					}

					$transaction->commit();
					$this->redirect( $this->createUrl('/admin/lists/items',array('id'=>$id)) );
				}
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