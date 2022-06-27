 <?php
	if (isset ( $model->card )) {
		?>
<div class="">
	Карта № <?php 
	 echo str_pad(intval ( $model->card->number ),8, '0', STR_PAD_LEFT);;
	 ?>, тип - <?php echo $model->card->type; ?>, выдана <?php echo date('d.m.Y', strtotime($model->card->given))?>
	 </div>
<?php
	}
	?>