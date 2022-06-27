<?php

class Easylift_podbor extends CWidget
{

    var $brand_list;
    var $model_list = array('0'=>'Модель');
    var $year_list = array('0'=>'Выберите марку и модель');

    // public $krepl_prod_ids;
    // public $krepl_categ_ids;
    // var $device_types_list;
    function __construct($krepl_prod_ids = NULL, $krepl_categ_ids = NULL)
    {

        // print_r($krepl_prod_ids);
        //
        $criteria = new CDbCriteria();
        // $criteria->order = 't.sort_category';
        $criteria->order = 't.category_name';
        $criteria->condition = " t.parent =  :root AND t.show_category = 1  ";
        $criteria->params = array(
            ':root' => Yii::app()->params['main_tree_root']
        );
        // if(empty($this->krepl_categ_ids)==false) $criteria->addCondition('child_categories.category_id IN ('.implode(',', $this->krepl_categ_ids).')');
        $first_tree = Categories::model()->with('child_categories')->findAll($criteria); //
        if (isset($first_tree)) {
            $brand_list = CHtml::listData($first_tree, 'category_id', 'category_name');
            // $this->brand_list = array('0'=>iconv( "CP1251", "UTF-8", "Марка автомобиля"))+$brand_list;
            // $this->brand_list = $brand_list;
            $this->brand_list = array(
                '0' => "Марка автомобиля"
            ) + $brand_list;
        }
    }

    function draw($target_file = NULL, $str_id = null)
    { // //////Марки модели года
        $params = array(
            'brand_list' => $this->brand_list,
            'model_list' => $this->model_list,
            'brand' => @$brand,
            'model' => @$model,
            'yaer' => @$year,
            'str_id'=>$str_id,
            // 'device_types_list'=>$this->device_types_list
        );

        if ($target_file != NULL)
            $this->render('easylift/' . $target_file, $params);
    }
}

?>