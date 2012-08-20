<h3><?=Yii::t('products',"У товара нет полей.")?></h3><br>
<?= CHtml::link("Добавить поле",$this->createUrl('/admin/product/addfield',array('id'=>$product->id)),array('class'=>'btn btn-primary btn-large')) ?>
