<?php
$videos = Products_video::model()->findAllByAttributes(array('product'=>$product->id));
if($videos!=null){
?>
<table width="100%">
	<thead>
		<tr>
			<th>Идентификатор</th>
			<th>Главное</th>
			<th>Код</th>
			<th>Видео</th>
			<th>Удаление</th>
			<th>Сохранение</th>
		</tr>
	</thead>
	<tbody>
		<?php 
foreach ($videos AS $model){
?>
		<tr>
			<td><?php echo $model->id?></td>
			<td><?php echo CHtml::radioButton('video_is_main', ($model->is_main==1), array('id'=>'video_main_'.$model->id))?></td>
			<td><textarea rows="6" cols=30><?php echo htmlspecialchars($model->html)?></textarea></td>
			<td>
				<div class="video-container" id="video_id_<?php echo $model->id?>">
					<?php echo $model->html?>
				</div>
			</td>
			<td><?php echo CHtml::checkbox('delvideo')?></td>
			<td><?php 
			echo CHtml::link('<img src="/images/filesaveas.png">', '#', array('onClick'=>'updateVideo($(this).parent().parent())'));
			?></td>
		</tr>
		<?php 
}
?>
	</tbody>
</table>
<?php 
}
?>
<script>
function updateVideo(el){
//console.log(el.children());
tds = el.children();

///Радио кнопка
radio_td = tds[1].children;
radio = radio_td[0];
//console.log($(radio).attr('checked'));

////Ячейка с видео
video_td = tds[3].children;
conteiner = video_td[0];

/////HTML код видео
html_td = tds[2].children;
html = html_td[0];
//data= [];
//data['product_id'] = <?php echo $product->id?>;
//data['radio_status'] = $(radio).attr('checked');

jQuery.ajax({
		'type':'POST',
		'url':'<?php echo Yii::app()->createURL('adminproducts/updatevideo')?>',
		'cache':false,
		'async': true,
		'dataType':'json',
		//'data':form.serialize(),
		'data':{ product_id: <?php echo $product->id?>,
								radio_status: $(radio).attr('checked'),
								html: $(html).val(),
								video_id : parseInt(($(radio).attr('id')).replace('video_main_', '')),
								},//radio_status передается только если чекнуто
		'success':function(response){
			//console.log(response);
			if(response=='ok'){ //значит апдейтим контейнер с видео
				$(conteiner).html( $(html).val() );
			}
			if(response=='deleted'){
				el.delete();
			}
			
		},
		'error':function(response, status, xhr){
			//makeAjaxRequest();
		}
		});

}









</script>