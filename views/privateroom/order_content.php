<?
///////////Выбираем курс уе
//$KURS=get_usd_curs ($cn);
//echo "www- $KURS<br>";
//mysql_query("SET NAMES cp1251",$cn);
//$OrderUnit = new  order_details($order_id);
//$OrderUnit->SET_NAMES(1);
//$ClientUnit =  new  client_details($OrderUnit->order_id);
//$ClientUnit->SET_NAMES(1);


$table_part_product_name = $OrderUnit->table_part_array['table_part_product_name'];
$table_part_product_id = $OrderUnit->table_part_array['table_part_product_id'];
$table_part_num = $OrderUnit->table_part_array['table_part_num'];
//$table_part_product_attribute = $OrderUnit->GetAttrValue('table_part_product_attribute');
$table_part_article = $OrderUnit->table_part_array['table_part_article'];
$table_part_price_with_nds = $OrderUnit->table_part_array['table_part_price_with_nds'];

$order_item_currency = $OrderUnit->currency;
//$table_part_nds_out = $OrderUnit->GetAttrValue('table_part_nds_out');
//$table_part_ostatki = $OrderUnit->GetAttrValue('table_part_ostatki');
$summa_pokupok_stat = $OrderUnit->summa_pokupok;
?>
<table width="100%" border="0"  cellpadding="0" cellspacing="0">
  <tr> 
    <td>
    
    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="plain" bgcolor="#E9E5D9">
  <tr bgcolor="#CFC7AD">
    <td>Номер заказа</td>
    <td><?=$OrderUnit->id?></td>
    <td colspan="2">Дата</td>
    <td bgcolor="#CFC7AD"><?=$OrderUnit->recept_date?>&nbsp;<?=$OrderUnit->recept_time?></td>
    </tr>
    <!--
  <tr bgcolor="#CFC7AD">
    <td>Клиент</td>
    <td colspan="4"><?=$OrderUnit->id_client?></td>
    </tr>-->
  <tr bgcolor="#CFC7AD">
    <td>Юрлицо</td>
    <td colspan="4"><?
    if (isset($OrderUnit->kontragent) ) echo $OrderUnit->kontragent->name;
	else echo $ClientUnit->urlico_txt;
	?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Адрес доставки</td>
    <td colspan="4"><?=@$OrderUnit->order_adress1?>
    <br><?=@$OrderUnit->order_adress2?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td bgcolor="#CFC7AD">Комментарий</td>
    <td colspan="4"><?=@$OrderUnit->primechanie?>    </td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Тел. клиента</td>
    <td colspan="4" bgcolor="#CFC7AD"><?=$ClientUnit->client_tels?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td rowspan="2" valign="top" bgcolor="#CFC7AD">Сумма</td>
    <td>Сумма руб.</td>
    <td align="center">&nbsp;Валюта заказа&nbsp;</td>
    <td align="center">курс</td>
    <td>Доставка</td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td><?=$summa_pokupok_stat?></td>
    <? $ord_cur = $OrderUnit->get_currency_code(); ?>
    <td align="center"><?=@$ord_cur?>&nbsp;</td>
    <td align="center"><?=$OrderUnit->currency_rate?></td>
    <td align="center"><?=$OrderUnit->currency_rate?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Платильщик</td>
    <td colspan="4"><?=$OrderUnit->get_payment_face()?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td height="20">Метод оплаты</td>
    <td colspan="4"><?php echo $OrderUnit->get_payment_method();
	if(isset($OrderUnit->payments) AND count($OrderUnit->payments)>0) {
		?><ul>
        <?php
        for($i=0; $i<count($OrderUnit->payments); $i++){
			echo '<li>';
			echo date('d-m-Y H:s:i', $OrderUnit->payments[$i]->operation_datetime);
			echo ',&nbsp;<strong>'.$OrderUnit->payments[$i]->money.'</strong>';
			echo '</li>';
		}
		?>
        </ul><?php
	}
	?></td>
  </tr>
  <tr bgcolor="#CFC7AD">
    <td>Статус</td>
    <td colspan="4" bgcolor="#CFC7AD"><?=$OrderUnit->get_order_status()?></td>
    </tr>
  <tr bgcolor="#CFC7AD">
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
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
          <td>Цена <?=$ord_cur?> за шт.</td>
        </tr>
        <?
// $query="SELECT products.product_name, products.product_article, contents.quantity,  contents.contents_price, contents.quantity, contents.quantity,
// products.product_ves, products.product_dlina, products.product_shirina, products.product_visota, products.product_id, products.product_price  
// FROM products, contents WHERE id_order=$order_id AND products.product_id=contents.contents_product";
//echo "$query<br>"
//$res=mysql_query($query, $cn);
//$num_of_messages=mysql_num_rows($res); // ???????? ??????? ????? ???????
$summ=0;
$i=0;
$ves=0;
$oves=0;     //////////////объёмный вес
$res_ves=0;
//
//while ($next=mysql_fetch_row($res)) {
//echo count($table_part_product_name);
for ($i=0; $i<count($table_part_product_name); $i++) {
$k=$i+1;
//if (@!$table_part_price_with_nds[$i]) $table_part_price_with_nds[$i]=9999999;
$summ_by_goods=0;
$summ_by_goods=$table_part_num[$i]*round($table_part_price_with_nds[$i], 2);

$summ=$summ+$summ_by_goods;
//$ves=$next[2]*$next[6]; /////////расчёт веса
//$oves=$next[2]*($next[7]*$next[8]*$next[9]/6000);////////////////объёмный вес
//$res_ves=$res_ves+(max($ves, $oves));
//$next[4]=trim(str_replace("00:00:00", "", $next[4]));
//$wtd=str_replace("0:00", "", $next[4]);
//if (@$wtd) $dost[]=$wtd;
      echo "  <tr bgcolor=\"#FFFFFF\"> 
          <td >$k</td>
          <td  colspan=\"3\">$table_part_product_name[$i]</td>
          <td>$table_part_article[$i]</td>
          <td><div align =\"center\">$table_part_num[$i]</div></td>
          <td><div align =\"center\"><i>";
		  $price=round($table_part_price_with_nds[$i], 2);
		  echo   $price;
		  echo "</i></div></td>";

		  //$skidka_po_kol_all=0;
          echo "</tr>";
}
//$res_ves=round($res_ves,1);
////////////подсчёт доставки
//if ($res_ves <=2 ) $dostavka=150;
//else  $dostavka=150+($res_ves-2)*15;
//$dostavka=round($dostavka);

		?>
        <tr bgcolor="#E9E5D9"> 
          <td>&nbsp;</td>
          <td colspan="3"><div align="right">Вес(объёмный вес)</div></td>
          <td> <div align="center"> 
              <?
		 // echo  $res_ves;
		  ?>
              кг </div></td>
          <td>Всего </td>
          <td><div align="center"> 
              <?
		   echo $summ;  
		  ?>
            &nbsp;<?=$ord_cur?></div></td>
        </tr>
        
        
        
    </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>

<?
if (@$pechat) {
echo "<script language=\"JavaScript\">
window.print();
</script>";
}///////////////if (@$pechat) {
?>