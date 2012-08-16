<?php
$this->breadcrumbs=array(
	Yii::t("AdminModule.categories", "Categories")	=>	array('/admin/categories'),
	Yii::t("AdminModule.categories", "Add category")
);

$this->renderPartial('SecondMenu');
?>

<h1>Create Categories</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>