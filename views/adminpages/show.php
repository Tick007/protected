<?php
$this->pageTitle ="Администрирование/статьи/".$model->sections->section.'/'.$model->title;
?>
<script>
$(document).ready(function() {
   // put all your jQuery goodness in here.
   $('#mailsender').click(function() {
	   //alert('werwererwe');
	   		
			jQuery.ajax({'type':'POST','url':'http://<?=$_SERVER['HTTP_HOST']?><?=Yii::app()->request->baseUrl?>/adminpages/sendnews/','data':$('#EditPage').serialize(),'cache':false,'success':function(responce){
		///////////////////////////
		if (responce) document.getElementById('mailpad').innerHTML='Разосланно: '+responce;
		} });
	   
	   });
 });
</script>

<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
</script>


<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70px">
<?

$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array(
        'Администрирование',
		'Статьи'=>array('adminpages/list'),
		$model->sections->section=>array('adminpages/list', 'section_id'=>$model->section),
		$model->title,
        
    ),
));

?>
<h2 class="admin_h2"><?php echo $model->title?></h2>

<?
echo  CHtml::errorSummary($model); 

$qqq = Yii::app()->getRequest()->getParam('section_id');
if (trim($qqq)) $sid=$qqq;
else $sid=$model->section;
?>
<br>
<?php echo CHtml::beginForm('/adminpages/edit/',  $method='post',$htmlOptions=array('name'=>'EditPage', 'id'=>'EditPage')); 
echo CHtml::hiddenField('section_id', $sid);
echo CHtml::hiddenField('id', Yii::app()->getRequest()->getParam('id', NULL));
echo CHtml::hiddenField('page', Yii::app()->getRequest()->getParam('page', NULL));

?>


<div class="private_to_right">

    <div class="private_room_div_header" style="background-image:url(/images/filesave_small.png); background-repeat:no-repeat; background-position:right; background-position-x:243px">
    Сохранение и статус
    </div>
    <div class="private_room_div_content">
    <table>
     <tr>
    <td width="125" colspan="4" valign="middle">Группа</td>
    <td valign="top"><?
	 echo CHtml::dropDownList('section_id', $model->section, $section_data, $htmlOptions=array('encode'=>false, 'style'=>'width:190px' ) );
  ?></td>
    </tr>
    <?php
if(count($rubric_data)>1) {
?>
     <tr>
       <td colspan="4" valign="middle">Рубрика</td>
       <td valign="top"><?
 echo CHtml::dropDownList('rubric', $model->rubric, $rubric_data, $htmlOptions=array('encode'=>false, 'style'=>'width:190px' ) );
  ?></td>
     </tr>
     <?php
}
?>
     <tr>
       <td colspan="4" valign="middle">Алиас</td>
       <td valign="top"><?php echo CHtml::textfield('alais', $model->alais,  $htmlOptions=array('encode'=>true, 'size'=>20, )  ) ?></td>
     </tr>

  <tr>
    <td height="30" colspan="4" valign="middle">Статус</td>
    <td valign="middle">
      <label>
        <input type="radio" name="status" value="1" id="active2" <?
        if ($model->active==1) echo " checked";
		?>>        
        Вкл</label>
      &nbsp;&nbsp;&nbsp;&nbsp;
      <label>
        <input type="radio" name="status" value="0" id="active" <?
        if ($model->active==0) echo " checked";
		?>>
        Выкл</label></td>
    </tr>
  <tr>
    <td height="30" colspan="4" valign="middle">Создано</td>
    <td valign="middle"><?php
    //echo FHtml::encodeDate($model->creation_date, 'medium');

		if(isset($model->creation_date)) {
			//echo $model->creation_date.'<br>';
			 $time=explode( ' ', $model->creation_date);
			 //$parts = explode('-', $time[0]);
			 //$datevalue  = $parts[2].'.'$parts[1].'.'.$parts[0];
			 //$datevalue = FHtml::encodeDate($model->creation_date, 'medium');;
			 $oDate = new DateTime($model->creation_date);
			 $datevalue = $oDate->format("d.m.Y");
		}
		
		//echo $datevalue;

		$date_to = new MyDatePicker;
		$date_to->conf = array(
				'name'=>'creation_date',
				'value'=>isset($datevalue)?$datevalue:'',
		// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'dd.mm.yy',
		),
				'htmlOptions'=>array(
		//	'style'=>'height:18px; padding:1px; border:0px'
		),
		'language' => 'ru',
		);
		$date_to->init();

	
	?></td>
    </tr>
  <tr>
    <td height="30" colspan="4" valign="middle">Правка</td>
    <td valign="middle"><?php
    echo FHtml::encodeDate($model->mod_date, 'medium');
	?></td>
  </tr>
  <tr>
    <td height="30" colspan="4" valign="middle">Рассылка</td>
    <td valign="middle"><?
echo  CHtml::link('Открыть',array('/adminpages/subscribe/', 'id'=>$model->id ) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
?></td>
  </tr>
  <tr>
    <td height="30" colspan="4" valign="middle">Сорт</td>
    <td valign="middle"><?php echo CHtml::textfield('sort', $model->sort,  $htmlOptions=array('encode'=>true, 'size'=>20, )  ) ?></td>
  </tr>
    </table>
    <br />
    <div align="center">
    <?php
	//echo CHtml::submitButton(' ', $htmlOptions=array ('name'=>'save_main_parametrs' , 'alt'=>'Сохранить', 'title'=>'Сохранить', 'class'=>'filesave'));
	?>
     <?
 echo CHtml::submitButton('', $htmlOptions=array ('name'=>'savepage', 'alt'=>'Сохранить', 'title'=>'Сохранить', 'class'=>'filesave'));
 ?><br><br>
 <?
  echo CHtml::submitButton('Закрыть', $htmlOptions=array ('name'=>'closepage' , 'alt'=>'Закрыть не сохраняя', 'title'=>'Закрыть не сохраняя'));
 ?>&nbsp;&nbsp;
    <?=CHtml::submitButton('Ок', $htmlOptions=array ('name'=>'save_close_page' , 'alt'=>'Сохранить и закрыть', 'title'=>'Сохранить и закрыть'));?>
    <br /><br />
        [<?php echo CHtml::link('Назад к списку',array('list', 'section_id'=>$sid, 'page'=>Yii::app()->getRequest()->getParam('page', NULL)  )); ?>]<br/><br/>
    
    </div>
    </div>
<br>
</div>


<?php echo CHtml::errorSummary($model); ?>
<table width="700" class="cat_content_table">
<tr bgcolor="#FFFFFF">
		<th class="label">Наименование</th>
		<td colspan="2"><?php echo CHtml::textfield('name', $model->name,  $htmlOptions=array('encode'=>true, 'size'=>100, )  ) ?>
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
	  <th class="label">Title</th>
	  <td colspan="2"><?php echo CHtml::textfield('title', $model->title,  $htmlOptions=array('encode'=>true, 'size'=>100, )  ) ?></td>
  </tr>
	<tr bgcolor="#FFFFFF">
	  <th class="label">Keywords</th>
	  <td colspan="2"><?
		//echo CHtml::textArea('Page[keywords]', $model->keywords,  $htmlOptions=array('encode'=>false, 'cols'=>76, 'rows'=>4 )  );?>
        <?php echo CHtml::textfield('Page[keywords]', $model->keywords,  $htmlOptions=array('encode'=>true, 'size'=>100, )  ) ?></td>
  </tr>
	<tr bgcolor="#FFFFFF">
	  <th class="label">Description</th>
	  <td colspan="2"><?
		echo CHtml::textArea('Page[description]', $model->description,  $htmlOptions=array('encode'=>false, 'cols'=>76, 'rows'=>4 )  );?></td>
  </tr>

<tr>
  <th class="label">Ссылка на источник</th>
  <td align="left"><?php echo CHtml::textfield('source', $model->source,  $htmlOptions=array('encode'=>true, 'size'=>100, )  ) ?><br><br></td>
</tr>
<tr>
	<th class="label">Краткое описание</th>
    <td style="text-align:left"><?
	//echo CHtml::textArea('teaser', $model->node_revision_one->teaser,  $htmlOptions=array('encode'=>false, 'cols'=>100, 'rows'=>8, )  );
	/*
	 $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'short_descr',
'name'=>'short_descr',
'language'=>'ru',
'editorTemplate'=>'full',
)); 
*/
$this->widget('application.extensions.tinymce.ETinyMce', array('name'=>'Page[short_descr]', 'EditorTemplate'=>'simple', 'id'=>'page_short_descr', 'value'=>$model->short_descr, 'height'=>'75px'));  
if (isset($model->section) AND $model->section==40) echo '<br>Вбейте в данном поле список адресов через ";", сохраните и нажмите кнопку разослать';
	?><br></td>
</tr>
<?php
if (isset($model->section) AND $model->section==40) {

?>
<tr><th class="label">Рассылка: </th><td id="mailpad">
<?php
//public static string ajaxButton(string $label, mixed $url, array $ajaxOptions=array ( ), array $htmlOptions=array ( ))
echo CHtml::button('Разослать', array('id'=>'mailsender')); 
?>
</td></tr>
<?php
}//////////////if (isset($model->section) AND $model->section==4) {
?>
<tr>
  <th class="label">Тело</th>
  <td style="text-align:left"><?php
  //	echo CHtml::textArea('body', $model->node_revision_one->body,  $htmlOptions=array('encode'=>false, 'cols'=>100, 'rows'=>25, )  );
  /*
   $this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'contents',
'name'=>'contents',
'language'=>'ru',
'editorTemplate'=>'full',
)); 
*/
//$this->widget('application.extensions.tinymce.ETinyMce', array('name'=>'Page[contents]', 'EditorTemplate'=>'full', 'id'=>'page_contents', 'value'=>$model->contents, 'height'=>'500px')); 
  echo CHtml::textArea('Page[contents]', $model->contents,  $htmlOptions=array('encode'=>false, 'cols'=>100, 'rows'=>15 )  );
?><br></td>
</tr>
<tr>
<tr>
  <td colspan="2"><div align="center"><br>
 </td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>
</div>