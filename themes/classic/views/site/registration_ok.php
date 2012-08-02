<?php
$this->pageTitle=Yii::app()->name . ' - Регистрация завершена';


$this->breadcrumbs=array(
	'Регистрация завершена'
);

?>

<h1>Спасибо</h1>
<p>Регистрация прошла успешно. Теперь вы можете <?php echo CHtml::link('войти',$this->createUrl('user/login')); ?> под своим логином.</p>