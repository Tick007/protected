<?php
$this->pageTitle=Yii::app()->name . ' - Ошибка ' . $error['code'];
$this->breadcrumbs=array(
	'Главная'=>'/',
	'Ошибка ' . $error['code'],
	//$error['message']
);



	$this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
			'separator'=>' &gt; ',
			'homeLink'=>false,
			'tagName'=>'div',
	)); 


?>

<style>
	#body
	{
		background: none;
	}
	h2
	{
		font-size: 28px;
	}
	h2 .errorCode
	{
		color: #1A65B9;
		font-size: 58px;
	}
</style>

<h2>Ошибка <span class="errorCode"><?php echo $error['code']; ?></span></h2>

<div class="error">
	<?php 
	if(isset($error['message']) && trim($error['message'])!=''){
		echo '<p>'.$error['message'].'</p>';
	}
	else {
	?>
	<p>
		Произошла ошибка. Возможно группа или товар были отключены или удаленны, или изменился адрес страницы. Попробуйте обновить страницу, или вернитесь на главную страницу <a href="/">сайта</a>.
	</p>
	<?php 
	}
	ob_start(); ?>
	
		<?php 
			$request = Yii::app()->getRequest(); 
			$user = Yii::app()->user;
			$browser = new CBrowserComponent();
		?>
	
		<div style="margin: 10px; padding: 10px; border: 1px solid #ccc;">
			<h1><?php echo $error['type']?></h1>
			
			<div style="padding: 10px;">
				<b>Код:</b> <?php echo $error['code']; ?>
			</div>
			<div style="padding: 10px;">
				<b>Сообщение:</b> <?php echo nl2br(CHtml::encode($error['message'])); ?>
			</div>
			<div style="padding: 10px;">
				<b>Хост:</b> <?php echo $_SERVER['HTTP_HOST']; ?>
			</div>
			<div style="padding: 10px;">
				<b>Запрос:</b> <?php echo $request->getUrl(); ?>
			</div>
			<div style="padding: 10px;">
				<b>Реферер:</b> <?php echo $request->getUrlReferrer() ? $request->getUrlReferrer() : 'неизвестен'; ?>
			</div>
			<div style="padding: 10px;">
				<b>Файл:</b> <?php echo CHtml::encode($error['file']); ?>
			</div>
			<div style="padding: 10px;">
				<b>Строка:</b> <?php echo CHtml::encode($error['line']); ?>
			</div>
			<div style="padding: 10px;">
				<b>Информация о запросе:</b>
				<pre><?php print_r($request); ?></pre>
			</div>
			<div style="padding: 10px;">
				<b>ID пользователя:</b> <?php echo $user->id ? $user->id : 'отсутствует (это гость)'; ?>
			</div>
			<div style="padding: 10px;">
				<b>Информация о пользователе:</b>
				<pre><?php print_r($error); ?></pre>
			</div>
			<div style="padding: 10px;">
				<b>Агент:</b>
				<div style="padding-left: 20px"><b>IP:</b> <?php echo $_SERVER['REMOTE_ADDR']; ?></div>
				<div style="padding-left: 20px"><b>Браузер:</b> <?php echo $browser->getBrowser(); ?></div>
				<div style="padding-left: 20px"><b>Версия:</b> <?php echo $browser->getVersion(); ?></div>
				<div style="padding-left: 20px"><b>Платформа:</b> <?php echo $browser->getPlatform(); ?></div>
				<div style="padding-left: 20px"><b>Подробная информация:</b> <?php echo $browser->getUserAgent(); ?>				</div>
			</div>
            <div style="padding: 10px;">
            <b>Дамп ошибки:</b>
            <pre><?php print_r($user); ?></pre>
            </div>
		</div>
	
	<?php $out = ob_get_clean(); ?>
	
	<?php /* if(YII_DEBUG):*/ if(isset(Yii::app()->params['display_error']) AND Yii::app()->params['display_error']==true ): ?>

		<?php echo $out; 
		echo '<pre>';
		print_r($error);
		echo '</pre>'
		?>
	
	<?php else: ?>
		
		<?php
			
			$send = true;
			$exceptionAgents = array(
				'google',
				'ezooms',
				'mail',
			);
			foreach($exceptionAgents as $agent)
			{
				if(strpos(strtolower($browser->getUserAgent()), $agent) !== false)
				{
					$send = false;
					break;
				} 
			}
		?>
	
		<?php if($send): ?>
			
			<?php
				$params['headers']['from'] = 'info@'. $_SERVER['HTTP_HOST'];
				$MailManager = new MailManager($params);
				if($error['code']==500) $MailManager->sendErrorAlarm('Ошибка. ' . $_SERVER['HTTP_HOST'] . '. ' . $error['message'], $out)
			?>
		
		<?php endif; ?>
	
	<?php endif; ?>
</div>