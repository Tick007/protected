<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
hs.minWidth = 320;
hs.minHeight = 520;
hs.height =520;
hs.width= 320;
</script>

<script>

function myfunc_razdel(id, targetform, targetitem){
//alert (id);
//window.location.reload( true );
document.getElementById(targetitem).value = id;
document.forms[targetform].submit();
return false;
}////////////////

</script>
<?php
$this->pageTitle="Выбор параметров обновления цены для выбранного паставщика";
?>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>
<div id="mainContent" style="padding-left:3px; margin-left:60px; background-color:#fffbf0  ">
<h2>Загрузка прайслистов от поставщика: шаг 2 -  установка наценок</h2>
Загрузка от поставщика/на склад  
<strong>"<?php
echo $store->name;
?>"</strong>. В файле найдено <?php echo $num_of_rows?> строк<br><br>

<?
echo CHtml::beginForm(array('/adminprices/steptwo', 'id'=>$id, 'attributes'=>urlencode(serialize($attributes))),  $method='post', array('name'=>'ruleslist'));  


//print_r(unserialize($store->pricerules));
?>
<div class="add" id="newrulebut"></div>

<div id="profress_div" class="profress_div" style="width:500px; height:100px;">
     <div id="progress" style="height:100; width:0px; line-height:100px; text-align:center; background-color:#06F">
        <div id="progdess_num" style="color:#FFF; font-size:14px;line-height:100px;">
        </div>
    </div>
</div>

<div>
<div >
Общие настройки для склада: 

<table class="cat_content_table"><thead>
	<tr>
        <th>№</th>
        <th>Цена от(>)</th>
        <th>Цена до(<=) </th>
        <th>Наценка</th>
        <th>Удаление</th>
    </tr>
</thead>
	<tbody class="rules">
    <?php
    if(isset($store->pricerules)) {
		$rules = unserialize($store->pricerules);
		for($i=0; $i<count($rules); $i++) {
		?>
		<tr>
        	<td><?php echo $i+1;?></td>
            <td><?php
            echo CHtml::textField('rules['.$i.'][price_from]', $rules[$i]['price_from'], array('size'=>4));
			?></td>
            <td><?php
            echo CHtml::textField('rules['.$i.'][price_to]', $rules[$i]['price_to'], array('size'=>4));
			?></td>
            <td><?php
            echo CHtml::textField('rules['.$i.'][koef]', $rules[$i]['koef'], array('size'=>4));
			?></td>
            <td><?php
            echo CHtml::checkbox("rules[$i][delrule]", false);
			?></td>
        </tr>
		<?php
		}
	}///////// if(isset($store->pricerules)) {
	?>
    </tbody>
</table>
</div><br><br>
<div >
Индивидуальные настройки групп. <?
	echo CHtml::link('Подбор', array('/nomenklatura/indexgr', 'targetitem'=>'add_category', 'targetform'=>'ruleslist') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
	//echo '<pre>';
	//print_r(unserialize($plh->catpricerules));
	//echo '</pre>';
	?>
<table class="cat_content_table"><thead>
	<tr>
        <th rowspan="2">Группа</th>
        <th colspan="4">Список правил</th>
        <th rowspan="2">Доб.</th>
        <th rowspan="2">Удаление</th>
    </tr>
	<tr>
	  <th width="50">&gt;</th>
	  <th width="50">&lt;=</th>
	  <th width="50">k</th>
	  <th width="50"><img src="/images/delete.gif" width="13" height="13" border="0" /></th>
	  </tr>
</thead>
	<tbody class="catrules"><?php
    	if(trim($plh->catpricerules)!=''){
			$catrules=unserialize($plh->catpricerules);
			if(is_array($catrules)){
				foreach ($catrules as $category_id=>$category_rules) {
					?>
					<tr>
                    	<td><?php
                        echo $plh->categories[$category_id];
						?> <br>(<?php echo $category_id?>)
                        </td>
                        <td colspan="4">
                        <?php
                      		//print_r($category_rules);
						?>
                        <table class="catrulesrules" id="catrules_<?php echo $category_id?>">
                        <?php
                        if(isset($category_rules)) {
							for($i=0; $i<count($category_rules); $i++) {
								?>
								<tr>
                                	<td><?php  echo CHtml::textField('catrules['.$category_id.']['.($i).'][price_from]', $category_rules[$i]['price_from'], array('size'=>4)); ?></td>
                                    <td><?php  echo CHtml::textField('catrules['.$category_id.']['.($i).'][price_to]', $category_rules[$i]['price_to'], array('size'=>4)); ?></td>
                                    <td><?php  echo CHtml::textField('catrules['.$category_id.']['.($i).'][koef]', $category_rules[$i]['koef'], array('size'=>4)); ?></td>
                                   <td><?php echo CHtml::checkbox("catrules[$category_id][$i][delrule]", false);	?></td>
                                </tr>
								<?php
							} //////for
						}//////// if(isset($category_rules)) {
						?>
                        </table>
                        </td>
                        <td align="center" valign="middle">
                        <div class="addsmall"></div>
                        </td>
                        <td><?php
                        echo CHtml::checkbox("delcatrules[$category_id]", false);
                        ?></td>
                    </tr>
					<?php
				}
			}//////if(is_array($catrules)){
		}/////////if(trim($plh->catpricerules)!='')
	?></tbody>
</table>
</div><br style="clear:both">
</div>


<div class="private_to_right" style="top:170px; left:700px; margin-left:0px; width:180px; top:100px">

    <div class="private_room_div_header" style="background-image:url(/images/db_commit5.png); background-repeat:no-repeat; background-position:right; background-position-x: 153px; width: 170px;">
    Загрузка
    </div>
    <div class="private_room_div_content">
	<div id="exrtainfo" class="errorSummary" style="display:none"></div>
    <div align="center">
    <?php
if(isset($rules)) {
?>
	Предварительная загрузка данных из XLS в прайслист в БД с применением новых цен
	<div id="preloadprices"></div>
    <?php
}
	?>

<br>
<?
     if (@$pricelist->status==0) echo CHtml::submitButton('', $htmlOptions=array ('name'=>'savepricelist' , 'alt'=>'Сохранить', 'title'=>'Сохранить', 'class'=>'filesave'));

	?>
     <?
// echo CHtml::submitButton('', $htmlOptions=array ('name'=>'savepage', 'alt'=>'Сохранить', 'title'=>'Сохранить', 'class'=>'filesave'));
 ?><br><br>

    
    </div>
    </div>
<br>
</div>
<?
	//echo CHtml::hiddenField('attributes',  urlencode(serialize($attributes)) );
	echo CHtml::hiddenField('add_category',  NULL, array('id'=>'add_category') );
	echo CHtml::endForm(); ?>


<br><br><br>
</div>
<script>

$(document).ready(function(){

		$('#newrulebut').click(function() {
			
			
			count = $('.rules').children('tr').size();
			
			tr = '<tr><td></td><td><input type="text" name="rules['+count+'][price_from]"></td><td><input type="text" name="rules['+count+'][price_to]"></td><td><input type="text" name="rules['+count+'][koef]"></td>	<td></td></tr>';
			$('.rules').append(tr);
		});
		
		$('#preloadprices').click(function() {////////////Эта функция должна вызывать аджакс оббработку файла xls по частям или вызов в функции вызванной аджаксом обращения к консольному скрипту
				//alert('фиг с маслом');
				$('#profress_div').toggle();	
				$('#progress').css('width', 0);
				$('#exrtainfo').css('display','none');
				
				var xls_rows = <?php echo $num_of_rows?>;
				var sql_operations_limit = <?php echo  $this->sql_operations_limit?>;
				var parts = <?php $parts =  round($num_of_rows/$this->sql_operations_limit, 0);
				if($parts<($num_of_rows/$this->sql_operations_limit)) $parts++;
				if($parts<1) $parts=1;
				echo $parts;
				?>;
				
				
				//$("#empty_span").append("<id>");			
				
				//make_request(1, parts);
				
				setTimeout("make_request(1, "+parts+")", 100);
				
				
		});
		


		$('.addsmall').click(function() { //////////////ДОбавление правила в таблицу для групп
			ruletable = $(this).parent().prev().children('table');
			catid= ruletable.attr('id').replace('catrules_', '');
			count = $(ruletable).children('tbody').children('tr').size();
			//alert(count);
			
			tr = '<tr><td><input type="text" name="catrules['+catid+']['+count+'][price_from]" size="4"></td><td><input type="text" name="catrules['+catid+']['+count+'][price_to]" size="4"></td><td><input type="text" name="catrules['+catid+']['+count+'][koef]" size="4"></td><td></td></tr>';
			$(ruletable).append(tr);
			
			//console.log(partr);
		});

});

function  make_request(step, parts){
		var responce ;
		var delay = <?php
		$delay = $this->sql_operations_limit*3;
		if ($delay<1000) $delay = 1000;
		echo $delay;
		?>;
		var data =  {
					'pricelist':<?php echo $id;?>,
					'store':<?php echo $attributes['store_id']?>,
					'article_col':<?php echo $attributes['article_col']?>,
					'price_col':<?php echo $attributes['price_col']?>,
					'store_col':<?php echo $attributes['store_col']?>,
					'tempfile':'<?php echo $attributes['tempfile']?>',
					'step':step , 
					'parts':parts,
				}
	
		jQuery.ajax({
					'type':'POST',
					'url':'/adminprices/loadpricelist',
					'cache':false,
					'async': false,
					'dataType':'json',
					'data':data,
					'success':function(html){
						
						//alert(step);
						responce = html;

					
					}});
					
					
					step++;
					if(step<=parts)  {
						draw_progress(parts, (step-1));
						setTimeout("make_request("+step+", "+parts+")", 100);
						//make_request(step,parts);
					}	
					else {
						draw_progress(parts, (step-1));
						
						
						
						setTimeout("finalize()", 1000);
						//alert(responce);
						$('#exrtainfo').html(responce);
						
					}
					
}

function finalize(){
	$('#profress_div').toggle();
	$('#exrtainfo').css('display','block');
	
}


function draw_progress( pecies, i){
		//console.log(i);
		width = (500/pecies)*(i);
		$('#progress').css('width', width);
		num = Math.round((100/pecies)*(i));
		$('#progdess_num').text(num+'%');
		//console.log(num);
}

</script>