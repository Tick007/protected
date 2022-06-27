<?php
$alias = Yii::app()->getRequest()->getParam('alias');

if(isset($this->items)) {
	?>
	<ul class="bottommenugroups">
    <?php
	$all = count($this->items);
	$i=1;
    foreach( $this->items as $model){
		?><li
        <?php
        if($i==$all) echo 'class="last"';
		?>
        >
        <?php
        echo CHtml::link(mb_strtoupper($model->category_name, 'utf-8'), array('product/list', 'alias'=>$model->alias));
		?>
		</li>
		<?php
		if($i<$all) {
			?>
			<li><div class="yellow_dot"></div></li>
			<?php
		}
		$i++;
	}
	?>
    </ul>
    <div style="clear:both"></div>
	<?
}

?>