<?
$clientScript=Yii::app()->clientScript;
$clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide-with-html.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/highslide/highslide.css');
?>
<script type="text/javascript">
hs.graphicsDir = '/js/highslide/graphics/';
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'draggable-header';
hs.minWidth = 1024;
hs.minHeight = 800;
</script>

<script>

function myfunc(kontragent_id){//////////Вызов обновления списка со складами
//alert('This is myfunc()');
jQuery.ajax({'type':'POST','url':'/admindocs/kdetails/','cache':false,'data':'kontragent_id='+kontragent_id,'success':function(html){
//jQuery("#store_doc_ca").html(html)
//alert(html);
document.getElementById('order_admin').submit();
}});
//alert(kontragent_id);
}

function displaypopup(url){
window.open (url,"mywindow","location=0,status=0,scrollbars=1,width=1200,height=700");
}

</script>
<div id="ribbon" style="margin-left:71px">
<?
$this->widget('zii.widgets.CBreadcrumbs', array(
    'links'=>array(
        'Администрирование',
		'Заказы'=>'/adminorders',
		'Заказ № '.$order->id.' от '.$order->recept_date,
        
    ),
));
?>

</div>
<div id="Right_column" style="background-color:#666E73; width:60px; margin-left:0px; float:left">
<?
$RC = new RightColumnAdmin;
?>
</div>

<div id="mainContent" style="padding-left:3px; margin-left:70px">

<?
echo CHtml::beginForm(array('/adminorders/', 'orderupdate'=>$order->id),  $method='post',$htmlOptions=array('name'=>'order_admin', 'id'=>'order_admin'));  
?>
<table width="100%" border="0"  cellpadding="0" cellspacing="0">
  <tr> 
    <td>
    
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="plain" bgcolor="#E9E5D9">
  <tr bgcolor="#CFC7AD">
    <td>Номер заказа</td>
    <td><?=$order->id?></td>
    <td colspan="2">Дата: &nbsp;<?=$order->recept_date?>&nbsp;<?=$order->recept_time?></td>
    <td bgcolor="#CFC7AD">&nbsp;</td>
    </tr>
    <!--
  <tr bgcolor="#CFC7AD">
    <td>Клиент</td>
    <td colspan="4"><?=$order->id_client?></td>
    </tr>-->
  <tr bgcolor="#E9E5D9">
    <td>Юрлицо</td>
    <td colspan="4"><?
    	if (isset($order->kontragent)) {
		echo CHtml::link($order->kontragent->name, array('/nomenklatura/kontragent/'.$order->kontragent->id) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
		echo CHtml::hiddenField('parametrs[contragent_id]', $order->kontragent->id, array('id'=>'kontragent_id'));
		echo "&nbsp;&nbsp;&nbsp;".$order->client->urlico_txt."&nbsp;&nbsp;&nbsp;";
		echo CHtml::checkBox('delete_urlico_link').' - удалить ссылку на контрагента';
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".CHtml::checkBox('create_bill').' - Выставить счет контрагенту';
	}
	else  {
		 echo CHtml::link('подбор',array('/nomenklatura/contragents/', 'targetitem'=>'kontragent_id', 'targetform'=>'order_admin') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
		echo CHtml::hiddenField('parametrs[contragent_id]', 0, array('id'=>'kontragent_id'));
	}
	?>&nbsp;</td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Адрес доставки (заказ)</td>
    <td colspan="4"><?
    echo CHtml::textarea('parametrs[order_adress1]', $order->order_adress1,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>30) , array('class'=>'textfield') ) ;
	?>&nbsp;<?
    echo CHtml::textarea('parametrs[order_adress2]', $order->order_adress2,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>30) , array('class'=>'textfield') ) ;
	?>
    <br></td>
  </tr>
  <tr bgcolor="#E9E5D9">
    <td>Примечание</td>
    <td colspan="4"><?
    echo CHtml::textarea('parametrs[primechanie]', $order->primechanie,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>50) , array('class'=>'textfield') ) ;
	?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Клиент</td>
    <td colspan="4" bgcolor="#CFC7AD"><strong><?=$order->client->second_name.' '.$order->client->first_name.' '.$order->client->last_name?></strong>, <?=$order->client->client_email?>, <?php echo CHtml::link('правка', array('/roles/details','id'=>$order->client->id) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
	if(trim($order->client->client_post_index)!='') echo ', индекс: '.$order->client->client_post_index;
	?></td>
  </tr>
  <tr bgcolor="#E9E5D9">
    <td>Адрес из карточки</td>
    <td colspan="4" ><?php echo $order->client->client_city;
	if(trim($order->client->client_street)!='') echo ', '.$order->client->client_street;
	?>&nbsp;</td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Тел. клиента</td>
    <td colspan="4" bgcolor="#CFC7AD"><strong><?=$order->client->client_tels?></strong></td>
  </tr>
  <tr bgcolor="#E9E5D9">
    <td rowspan="2" valign="top">Сумма</td>
    <td>Сумма руб.</td>
    <td align="center">&nbsp;Валюта заказа&nbsp;</td>
    <td align="center">курс</td>
    <td>Доставка</td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td><?=$order->summa_pokupok?></td>
    <? // $ord_cur = $OrderUnit->get_currency_code(); 
	 $ord_cur = Yii::app()->GP->GP_shop_currency_code;
	?>
    <td align="center"><?php echo $ord_cur?>&nbsp;</td>
    <td align="center"><?php echo $order->currency_rate?></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Платильщик</td>
    <td colspan="4"><?
            echo CHtml::dropDownList('parametrs[payment_face]', $order->payment_face, $faceslist );
			?></td>
  </tr>
  <tr bgcolor="#E9E5D9">
    <td height="20">Метод оплаты</td>
    <td colspan="4"><?
            echo CHtml::dropDownList('parametrs[payment]', $order->payment, $paymetlist );
			if(isset($order->payments) AND count($order->payments)>0) {
		?><ul>
        <?php
        for($i=0; $i<count($order->payments); $i++){
			echo '<li>';
			echo date('d-m-Y H:s:i', $order->payments[$i]->operation_datetime);
			echo ',&nbsp;<strong>'.$order->payments[$i]->money.'</strong>' ;
			echo '</li>';
		}
		?>
        </ul><?php
	}
			?>
            
      </td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Статус</td>
    <td colspan="4" bgcolor="#CFC7AD">
	<?
            echo CHtml::dropDownList('parametrs[order_status]', $order->order_status, $statlist );
			?>&nbsp;&nbsp;
	<?=$order->OrderStatus->description2?></td>
    </tr>
   <tr bgcolor="#E9E5D9">
    <td>Список документов</td>
    <td colspan="4"><?
    		$documents = $order->documents;
			for ($k=0; $k<count($documents); $k++) {
   					 echo CHtml::link('№ '.$documents[$k]->id, array('/admindocs/', 'doc'=>$documents[$k]->id), $htmlOptions=array ('encode'=>false) );
					 echo '&nbsp;от&nbsp;'.$documents[$k]->date_dt.';&nbsp;';
			}
    ?></td>
    </tr>
     <tr bgcolor="#CFC7AD">
    <td>Ссылка на оплату</td>
    <td colspan="4"><?php
    $oid1 =  FHtml::base64url_encode($order->id);
    //echo '$oid1 = "'.$oid1.'"<br>';
    $oid2=FHtml::base64url_encode(md5(Yii::app()->params['payments']['tinkoff']['order_id_code_prefix']).$oid1.md5(Yii::app()->params['payments']['tinkoff']['order_id_code_postfix']));
   //echo  '$oid2 = '.$oid2;
    
    //echo urlencode(base64_decode($oid2));
    $payurl =  Yii::app()->createAbsoluteUrl('epayment/tkfpayment', array('id'=>$oid2));
    echo '<br><a href="'.$payurl.'" target="_blank">'.$payurl.'</a>';
    ?></td>
    </tr>
</table>
    
    </td>
  </tr>
  
  <tr> 
    <td><table width="100%" border="0" class="plain"   cellpadding="1" cellspacing="1">
        
        <tr bgcolor="#FFFFFF"> 
          <td width="10">&nbsp;</td>
          <td colspan="3"> Товар</td>
          <td>Артикул</td>
          <td>Кол-во</td>
          <td>Остатки(не товароучет)</td>
          <td>Цена <?=$ord_cur?> за шт.</td>
          <td>Сумма</td>
          <td>Удаление</td>
        </tr>
 <?
$summ = 0;
for ($i=0; $i<count($order->OrderContent); $i++) {
$k=$i+1;
//if (@!$table_part_price_with_nds[$i]) $table_part_price_with_nds[$i]=9999999;
$summ_by_goods=0;
$summ_by_goods=$order->OrderContent[$i]->quantity*round($order->OrderContent[$i]->contents_price, 2);

$summ=$summ+$summ_by_goods;
//$ves=$next[2]*$next[6]; /////////расчёт веса
//$oves=$next[2]*($next[7]*$next[8]*$next[9]/6000);////////////////объёмный вес
//$res_ves=$res_ves+(max($ves, $oves));
//$next[4]=trim(str_replace("00:00:00", "", $next[4]));
//$wtd=str_replace("0:00", "", $next[4]);
//if (@$wtd) $dost[]=$wtd;
      echo "  <tr bgcolor=\"#FFFFFF\"> 
          <td >$k</td>
          <td  colspan=\"3\">";
		  //adminproducts/product.html?id=4540&group=2013&char_filter=
		 echo CHtml::link($order->OrderContent[$i]->contents_name, array('adminproducts/product', 'group'=>$order->OrderContent[$i]->belongs_product->category_belong, 'id'=>$order->OrderContent[$i]->contents_product),  array('target'=>'_blank'));
		 echo "</td><td>";
		  $url=urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$order->OrderContent[$i]->belongs_product->belong_category->alias, 'path'=>FHtml::urlpath($order->OrderContent[$i]->belongs_product->belong_category->path)  ,  'pd'=> $order->OrderContent[$i]->contents_product) ) );
		  echo  CHtml::link($order->OrderContent[$i]->belongs_product->product_article,$url, array('target'=>'_blank'));
		  /*$order->OrderContent[$i]->contents_article*/
		  echo "</td>
          <td><div align =\"center\">";
		  //$order->OrderContent[$i]->quantity;
		  echo CHtml::textfield('quantity['.$order->OrderContent[$i]->field_id.']', $order->OrderContent[$i]->quantity,  $htmlOptions=array('encode'=>true, 'size'=>5 )  ) ;		 
		  echo "</div></td>";
          echo "<td>".$order->OrderContent[$i]->belongs_product->number_in_store."</td>";
		  echo "<td><div align =\"center\">";
		  $price=round($order->OrderContent[$i]->contents_price, 2);
		  //echo   $price;
		   echo CHtml::textfield('contents_price['.$order->OrderContent[$i]->field_id.']', $price,  $htmlOptions=array('encode'=>true, 'size'=>5 )  ) ;		 
		  echo "</div></td>";

		  //$skidka_po_kol_all=0;
          echo "<td align=\"center\">$summ_by_goods</td><td align=\"center\">";
		  echo CHtml::checkBox('del_product['.$order->OrderContent[$i]->field_id.']', 0);
		  echo "</td></tr>";
}
//$res_ves=round($res_ves,1);
////////////подсчёт доставки
//if ($res_ves <=2 ) $dostavka=150;
//else  $dostavka=150+($res_ves-2)*15;
//$dostavka=round($dostavka);

		?>
        <tr bgcolor="#E9E5D9"> 
          <td>&nbsp;</td>
          <td colspan="3">

<input name="add_product" type="hidden" id="add_product" >
<a href="#" onclick="{displaypopup('/nomenklatura?targetitem=add_product&targetform=order_admin')}">
Добавить номенклатуру</a></td>
          <td>&nbsp;</td>
          <td>Всего </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td align="center"><?
		   echo $summ;  
		  ?>
&nbsp;
<?=$ord_cur?></td>
          <td align="center">&nbsp;</td>
        </tr>
        
        
        
    </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<div align="center"><?php
	echo CHtml::link('Печать', array('adminorders/print', 'id'=>$order->id), array('target'=>'_blank'));
?>&nbsp;&nbsp;<?php
  echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'savepricelist' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));?></div>

  <?php

 if($order->order_status<5)$this->renderPartial('blocks/status', array('order_status'=>$order->order_status, 'statlist'=>$statlist));

?>
  

<?php echo CHtml::endForm(); ?>

<?
if (@$pechat) {
echo "<script language=\"JavaScript\">
window.print();
</script>";
}///////////////if (@$pechat) {
?>


</div>
<script>
$('#order_status_force').change(function() {
  $('#parametrs_order_status').val($('#order_status_force').val());
  $('#dialog_close', window.parent.document).click();
});
</script>




