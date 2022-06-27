<?php
class LeftColOkodesign extends CWidget
{
	public $items=array();
	public $product;

	public function __construct()
	{
			$pd = Yii::app()->getRequest()->getParam('pd');
			$criteria=new CDbCriteria;
			$criteria->condition = " t.parent = :parent AND t.show_category";
			$criteria->order="t.sort_category";
			$criteria->params=array(':parent'=>Yii::app()->params['main_tree_root']);
			$this->items = Categories::model()->findAll($criteria);
			
			if(isset($pd) AND is_numeric($pd)) {
				$this->product=Products::model()->findByPk($pd);
			}
		
	}

	public function Draw()
	{
		$this->render('okodesign/leftmenu');
	}
	
	public function DrawBottom()
	{
		$this->render('okodesign/bottommenu');
	}
	
	
	public function mainmenuproducts($model){ //////////////////товары
	?>
	<ul class="mainmenuproducts">
		<?php
			for($i=0; $i<count($model->products); $i++) {
				if(empty($this->product)==false AND $model->products[$i]->id==$this->product->id) {
				?>
				<li class="active"><?php
				echo $model->products[$i]->product_name;
				?></li>
				<?php
				}
				else { ?>
				<li><?php
				echo CHtml::link($model->products[$i]->product_name,  array('product/details','pd'=>$model->products[$i]->id, 'alias'=>$model->alias));
                ?></li>
				<?php
				}
			}//////for
				?>
                
		</ul>
	<?php
	}///////////function mainmenuproducts(){
	
	public function categorychilds($model){////////////////подкатегории
	$alias = Yii::app()->getRequest()->getParam('alias');
		?>
		<ul class="mainmenusubgroups"><?php
        for($i=0; $i<count($model->childs); $i++) {
		$url=urldecode(Yii::app()->createUrl('product/list' ,array('alias'=>$model->childs[$i]->alias, 'path'=>FHtml::urlpath($model->childs[$i]->path) ) ) );	
			 if($model->childs[$i]->alias==$alias )  {
				 ?><li class="active">
				 <?php
				 //echo $model->childs[$i]->category_name;
				   echo CHtml::link($model->childs[$i]->category_name,$url);
				 if(isset($model->childs[$i]->products)) $this->mainmenuproducts($model->childs[$i]);
				 ?>
                
				</li> <?php
			 }
			 else {
			?>
			<li>
            <?php
			
            echo CHtml::link($model->childs[$i]->category_name,$url);
			

			
			?>
            </li>
			<?php
			 }//////// else {
		}
		?>
        </ul>
		<?php
	}/////////////public function categorychilds($model){/////////////
	
	
}/////////////class