<?php

//$GS = General_settings::model()->findByPk(1);
//company_name
?>

<div class="print">
<div>
    <div class="print_head"><?php echo $_SERVER['HTTP_HOST']?></div>
    <div class="print_contacts">Интернет-магазин <?php echo  $_SERVER['HTTP_HOST']?><br>				
    тел. <?php // echo  $GS->company_tel;?>
    </div>
</div>
<br style="clear:both" /><br />
<div align="center" style="font-size:16px">
ТОВАРНЫЙ ЧЕК № 001<?php echo $order->id?> от <?php
echo date('d.m.Y');
?>
</div>
<br style="clear:both" /><br /><br />

<div>	
	<div class="print_seller">Продавец:</div>
    <div class="print_seller_name"><?php // echo $GS->company_name?></div>
</div>
<br style="clear:both" /><br />
<div>
	<div class="print_adres">Адрес:</div>
    <div class="print_adres_value"><?php
    echo $order->order_adress1;
	if(trim($order->order_adress2 )) echo ', '.$order->order_adress2;
	?></div>
</div>

<br style="clear:both" /><br />
<div>
	<div class="print_buyer">Покупатель:</div>
    <div class="print_buyer_value"><?=$order->client->second_name.' '.$order->client->first_name.' '.$order->client->last_name?></strong>, <?=$order->client->client_tels?></div>
</div>
<table width="100%" border="0" class="plain"   cellpadding="1" cellspacing="1" bgcolor="#333333">
        
        <tr bgcolor="#FFFFFF"> 
          <td width="10"><strong>№</strong></td>
          <td><strong>Код</strong></td>
          <td colspan="3"><strong> Товар</strong></td>
          
          <td><strong>Кол-во</strong></td>
          <td><strong>Ед.</strong></td>
          <td><strong>Цена, руб</strong></td>
          <td><strong>Сумма, руб</strong></td>
        </tr>
 <?

for ($i=0; $i<count($order->OrderContent); $i++) {
$k=$i+1;
//if (@!$table_part_price_with_nds[$i]) $table_part_price_with_nds[$i]=9999999;
$summ_by_goods=0;
$summ_by_goods=$order->OrderContent[$i]->quantity*round($order->OrderContent[$i]->contents_price, 2);

@$summ=$summ+$summ_by_goods;
//$ves=$next[2]*$next[6]; /////////расчёт веса
//$oves=$next[2]*($next[7]*$next[8]*$next[9]/6000);////////////////объёмный вес
//$res_ves=$res_ves+(max($ves, $oves));
//$next[4]=trim(str_replace("00:00:00", "", $next[4]));
//$wtd=str_replace("0:00", "", $next[4]);
//if (@$wtd) $dost[]=$wtd;
      echo "  <tr bgcolor=\"#FFFFFF\"> 
          <td >$k</td>
		  <td>";
		  echo  $order->OrderContent[$i]->belongs_product->product_article;
		  /*$order->OrderContent[$i]->contents_article*/
		  echo "</td>
          <td  colspan=\"3\">";
		  //adminproducts/product.html?id=4540&group=2013&char_filter=
		 echo $order->OrderContent[$i]->contents_name;
		 echo "</td>
          <td><div align =\"right\">";
		  //$order->OrderContent[$i]->quantity;
		  echo  $order->OrderContent[$i]->quantity;		 
		  echo "</div></td>";
		   echo "<td><div align =\"left\">";
		  //$order->OrderContent[$i]->quantity;
		  echo  'шт';		 
		  echo "</div></td>";
         		  echo "<td><div align =\"right\">";
		  $price=round($order->OrderContent[$i]->contents_price, 2);
		  echo   $price;
		  echo "</div></td>";
		  echo '<td align="right">';
		  echo $summ_by_goods;
		echo '</td>';	
		  //$skidka_po_kol_all=0;
          echo "</tr>";
}
//$res_ves=round($res_ves,1);
////////////подсчёт доставки
//if ($res_ves <=2 ) $dostavka=150;
//else  $dostavka=150+($res_ves-2)*15;
//$dostavka=round($dostavka);

		?>
        <tr bgcolor="#FFFFFF"> 
          <td colspan="8" align="right"><strong>Итого</strong>&nbsp;</td>
          <td align="right"><strong><?
		   echo $summ;  
		  ?>
&nbsp;
<?php //$ord_cur?><strong/></td>
        </tr>
        
        
        
    </table>
<br style="clear:both" /><br /><br /><br />
<div>
	<div class="print_received">Получено:</div>
    <div class="print_recieved_sign"><?php
	$ntt = new Numtotext;
	$strok = trim($ntt->Convert($summ, 'руб.'));
	echo FHtml::mb_ucfirst($strok);
	?> копеек</div>
</div>

<br style="clear:both" /><br /><br /><br />
<div>	
	<div class="print_seller_sign">Продавец:</div>
    <div class="print_seller_sign_line"></div>
</div>
<br style="clear:both" /><br /><br /><br />
Товар получил(ла) полностью. Претензий по комплектности, внешнему виду и упаковке не имею.		<br>				
С правилами гарантийного обслуживания ознакомлен(на).						
<br><br>
<?php
echo date('d.m.Y');
?>

<br style="clear:both" /><br /><br /><br />
<div>	
	<div class="print_seller_sign">Покупатель:</div>
    <div class="print_seller_sign_line">&nbsp;</div>
</div>

</div><!--<div class="print">-->


