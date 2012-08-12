<?php
$this->breadcrumbs=array(
	'Товары'    =>  array('index'),
	'Работа с товаром #'.$Product->ID." ({$Product->Name})" => $this->createUrl('/admin/product/view',array('id'=>$Product->ID)),
	'Добавление товара',
);

$this->renderPartial('GoodsSecondMenu',array('Product'=>$Product));
?>

<script type="text/javascript" language="JavaScript">
    $(function(){
		$('#tires_Size').chosen();
    });
</script>

<?php
	echo $Form->render();
?>