<?
//print_r($_POST);
?>


<table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain"  bgcolor="#0000CC">
  <tr bgcolor="#CFC7AD"> 
    <td>&nbsp;</td>
    <td>Сгруппа</td>
    <td>Группа</td>
    <td>Номенклатура</td>
    <td>Поставщик</td>
    <td>Партия</td>
    <td>Кол-во</td>
    <td>Цена закупки без НДС</td>
    <td>Сумма без НДС</td>
    <td>НДС% вх</td>
    <td>Сумма НДС вх</td>
  </tr>
<?


$sum_prib=0;
$sum_nds=0;
$m=0;

for ($i=0; $i<count($rows); $i++) {
$next=(object)$rows[$i];
//print_r($next);
//echo '<br>';
$sgr[]=$next->sgr;
$gr[]=$next->gr;
$product_name[]=$next->product_name;
if (@$parametrs[enable_suplier])  $supname[]=$next->supname;
//$store_name[] = $next->store_name;
if (@$parametrs[enable_buyer]) $kname[]=$next->kname;
//if (@$detailed) $doc_id1[]=$next->doc_id1;
//if (@$detailed) $operation_dt[]=$next->operation_dt;
$sum_num[]=$next->prihod - $next->rashod;
if (@$parametrs[detailed]) $ser[]=$next->doc_id;
if (@$parametrs[detailed])  $price_no_nds_in[]=$next->price_no_nds_in;
if (@$parametrs[detailed]) $nds_in[]=$next->nds_in;
if (@$parametrs[detailed]) $sum_nds_in[]=round(($next->price_no_nds_in*$next->nds_in*($next->prihod - $next->rashod)),2);
if (@$parametrs[detailed]) $sum_no_nds_in[] = round(($next->price_no_nds_in*($next->prihod - $next->rashod)),2);
if (@$parametrs[detailed]) $arrive_dt[]=$next->arrive_dt;

$m++;
}//////for ($i=0; $i<count($rows); $i++) {
  for ($i=0; $i<$m; $i++) {
  $k=$i+1;
		  ?>
  <tr bgcolor="#FFFFFF"> 
    <td> 
      <?=$k?>
    </td>
    <td> 
      <?=$sgr[$i]?>
    </td>
    <td> 
      <?=@$gr[$i]?>
    </td>
    <td> 
      <?=@$product_name[$i]?>
    </td>
    <td> 
      <?=@$supname[$i]?>
	      </td>
    <td><a href="document.php?doc_id=<?=@$ser[$i]?>" target="_blank"><?=@$ser[$i]?></a></td>
    <td> 
      <?=$sum_num[$i]?>
    </td>
    <td> 
      <?=@$price_no_nds_in[$i]?>
    </td>
    <td> 
      <?=@$sum_no_nds_in[$i]?>
    </td>
    <td> 
      <?=@$nds_in[$i]?>
    </td>
    <td> 
      <?=@$sum_nds_in[$i]?>
    </td>
  </tr>
  <?
		}///////////for
		?>

</table>