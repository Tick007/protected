 <?php
	if (isset ( $user->card ) && $user->card !=null) {
		?>
<div class="">
	Карта № <?php 
	 echo str_pad(intval ( $user->card->number ),8, '0', STR_PAD_LEFT);
	 ?>, тип - <?php echo $user->card->type; ?>, выдана <?php echo date('d.m.Y', strtotime($user->card->given))?>
	 </div>

	
	Изменить тип карты: 
	<?php 
	echo FHtml::enumDropDownList($user->card, 'type');
	
	?>
	<br>
	Удалить карту:
	<?php 
	echo CHtml::checkBox('ClientCards[delete]', false, array());
	?>
	<?php
	}
	else {
		?>
		Добавить карту: 
		<?php 		echo CHtml::checkBox('ClientCards[add]', false, array());?><br><?php 
		$CC = new ClientCards();
		echo FHtml::enumDropDownList($CC, 'type');
	}
	?>
	<table>
	</table>