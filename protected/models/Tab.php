<?php

/**
 * This is the model class for table "tab".
 *
 * The followings are the available columns in table 'tab':
 * @property string $id
 * @property string $product_id
 * @property integer $position
 * @property string $name
 *
 * The followings are the available model relations:
 * @property FieldTab[] $fieldTabs
 * @property ProductField $product
 */
class Tab extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tab the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tab';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, product_id', 'required', 'on'=>"add,edit"),
			array('position', 'numerical', 'integerOnly'=>true),
			array('product_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, product_id, position, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'fieldTabs' => array(self::HAS_MANY, 'FieldTab', 'tab_id'),
			'product' => array(self::BELONGS_TO, 'ProductField', 'product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'product_id' => 'Product',
			'position' => 'Position',
			'name' => 'Name',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function Tabs($arTabs,$isEdit = false){
		
        // сортировка
        $positions = array();
        foreach ($arTabs as $key => $row){
            $positions[$key] = $row['position'];
        }
        
        array_multisort($positions, SORT_ASC, $arTabs);

        
        // вормируем вкладки
        $ul = ' <ul class="nav nav-tabs">';
        if ( !empty($arTabs) ){
            foreach($arTabs as $tab){
                $l = null;
                if ( isset($tab['productId']) && is_numeric($tab['productId']) )
                    $l = CHtml::ajaxButton('×', Yii::app()->createUrl('/admin/constructor/deletetab', array('productId'=>$tab['productId'],"tabId"=>$tab['id'])),
                                                array('type'=>'POST','success' => 'function(){ $("#tab_'.$tab['id'].'").remove(); }'),
                                                array("class"=>"close")
                                            );         
                
                
                $a = CHtml::tag('a',array('href'=>'#content_'.$tab['id'],"data-toggle"=>"tab"),$tab['name'].$l);
                $htmlOptions = array('id'=>'tab_'.$tab['id']);
                if ( isset($tab['htmlOptions']) ) $htmlOptions = array_merge($htmlOptions,$tab['htmlOptions']);
                $ul .= CHtml::tag('li',$htmlOptions,$a);
            }
        }
        
        $ul .= '<li class="exclude"><a href="#seoTab" data-toggle="tab">SEO</a></li>';
        
        if ( $isEdit )
            $ul .= '<li class="exclude"><a id="addTab" href="javascript:void(0);"><i class="icon-plus"></i></a></li>';        
        
        $ul .= '</ul>';
        
        $content[] = '<div class="tab-content">';
        
        $content[] = '<div id="seoTab" class="tab-pane exclude">';
        $content['alias'] = array('type'=>'text','class'=>"span5",'maxlength' => 255);
        $content['title'] = array('type'=>'textarea','class'=>"span5",'rows' => 5);
        $content['keywords'] = array('type'=>'textarea','class'=>"span5",'rows' => 5);
        $content['description'] = array('type'=>'textarea','class'=>"span5",'rows' => 5);
        $content[] = "</div>";

        if ( !empty($arTabs) ){
            foreach($arTabs as $tab){
                $htmlOptions = array('id'=>'content_'.$tab['id'], 'class'=>"tab-pane");
                if ( isset($tab['htmlOptions']) ) {
                    if ( isset($tab['htmlOptions']['class']) ) $tab['htmlOptions']['class'] .= " ".$htmlOptions['class'];
                    $htmlOptions = array_merge($htmlOptions,$tab['htmlOptions']); 
                }               
                $content[] =  CHtml::openTag('div',$htmlOptions);
                $content = array_merge($content,$tab['content']);
                $content[] = "</div>";                
            }
        }


        $content[] = "</div>";
        
        
        $return = array('<div class="tabbable">'.$ul);
        $return = array_merge($return,$content);
        
        $return[] = "</div>";
        return $return;
	}

}