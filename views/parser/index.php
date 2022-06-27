<div id="parser">
	<h1>Парсер</h1>
	<div id="parserForm">
		<?php echo CHtml::beginForm(); ?>
			<?php echo CHtml::label('Web сайт'); ?>
			<?php
            echo CHtml::dropDownlist('parser_site', Yii::app()->getRequest()->getParam('parser_site', NULL), $this->web_sites); 
			?>
            <br>	 <br>	
            <?php echo CHtml::label('Запись'); ?>
            <?php echo CHtml::checkBox('make_record', false); ?> <br>	 <br>	
			<?php echo CHtml::label('URL продукта', 'urlField'); ?>
			<?php echo CHtml::textField('url', isset($_POST['url']) ? $_POST['url'] : null, array('id' => 'urlField')); ?> <br>	 <br>	
			<?php echo CHtml::submitButton('Парсить'); ?>

		<?php echo CHtml::endForm(); ?>
	</div>
	<?php if(isset($result)): ?>
		<div id="parserResult">
			<?php $link = CHtml::link($parsedUrl, $parsedUrl, array('target' => '_blank')); ?>
			<?php if(!$error): ?>

					<div class="success">Парсинг данных с <?php echo $link; ?> прошел успешно.</div>

					<div class="item"><b>Наименование товара</b> <?php echo $result['productName']; ?></div>
					<div class="item"><b>Артикул</b> <?php echo $result['articul']; ?></div>
					<div class="item"><b>Категория</b> <?php echo $result['categoryName']; ?></div>
					<div class="item"><b>Родительская категория</b> <?php echo $result['parentCategoryName']; ?></div>
					
					<?php if(!empty($result['characteristics'])): ?>
						<div class="item">
							<b>Характеристики</b>
							<table>
								<?php foreach($result['characteristics'] as $charName => $charVal): ?>
									<tr>
										<td><?php echo $charName; ?></td>
										<td><?php echo $charVal; ?></td>
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					<?php endif; ?>
					
					<div class="item"><b>Цена</b> <?php echo $result['price']; ?></div>
					
					<?php if(!empty($result['createdPhotos'])): ?>
						<div class="item">
							<b>Фото</b>
							<?php foreach($result['createdPhotos'] as $src): ?>
								<div><img src="<?php echo $src; ?>" width="100px" /></div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

			<?php else: ?>

					<div class="error">Не удалось собрать данные с <?php echo $link; ?>.</div>
					<div class="error"><?php echo $error; ?></div>
					
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<style>
	h1
	{
		font-size: 18px;
		padding-bottom: 5px;
	}
	#parser
	{
		padding: 20px;
	}
	#parserForm
	{
		border-radius: 10px 10px 0 0;
		background-color: #eee;
		padding: 10px;
	}
	#parserResult
	{
		border-radius: 0 0 10px 10px;
		border: 5px solid #eee;
		border-top: 0px;
		padding: 10px 15px;
	}
	#parserResult .error
	{
		color: crimson;
		margin-bottom: 10px;
	}
	#parserResult .error a
	{
		font-weight: bold;
		color: crimson;
	}
	#parserResult .success
	{
		color: #060;
		margin-bottom: 10px;
	}
	#parserResult .success a
	{
		font-weight: bold;
		color: #060;
	}
	#parserResult .item
	{
		margin: 0;
		padding: 0;
		border: 0;
		margin-bottom: 10px;
	}
	#parserResult .item table
	{
		margin-left: 20px;
	}
	#urlField
	{
		margin-left: 10px;
		width: 350px;
		border: 1px solid #ccc;
		border-radius: 5px;
		padding-left: 10px;
	}
</style>