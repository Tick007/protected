<table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain"  bgcolor="#0000CC">
  <tr bgcolor="#CFC7AD"> 
    <td rowspan="2">№</td>
    <td rowspan="2">Сгруппа</td>
    <td rowspan="2">Группа</td>
    <td rowspan="2">Номенклатура</td>
    <td rowspan="2">Поставщик</td>
    <td rowspan="2">Док.партии</td>
    <td colspan="<?=count($parametrs[stores_id])?>">Кол-во</td>
  </tr>
  <tr bgcolor="#E9E5D9"> 
    <?
for($k=0;$k<count($parametrs[stores_id]);$k++) {
$kk=$k+1;
?>
    <td bgcolor="#CFC7AD"><?=$parametrs[stores_names][$k]?></td>
	<?
	}
	?>
  </tr>
  <?
  

//echo "$query<br>";
//if (!$res=mysql_query($query, $cn)) echo mysql_error();
$sum_prib=0;
$sum_nds=0;
$m=0;
//while($next=mysql_fetch_row($res)) {
for ($i=0; $i<count($rows); $i++) {
$next=(object)$rows[$i];
//$sum_prib = $sum_prib+$next->pribil;
//$sum_nds = $sum_nds+$next->nds_razn;
//print_r($rows[$i]);
//echo '<br>';
$sgr[]=$next->sgr;
$gr[]=$next->gr;
 $product_name[]=$next->product_name;
if (@$parametrs[enable_suplier])  $supname[]=$next->supname;
//$store_name[] = $next->store_name;
if (@$parametrs[enable_buyer]) $kname[]=$next->kname;
if (@$parametrs[detailed]) $doc_id1[]=$next->doc_id1;
if (@$parametrs[detailed]) $operation_dt[]=$next->operation_dt;
//$sum_num[]=$next->prihod - $next->rashod;
//$sum_num2[]=$next->prihod_store2 - $next->rashod_store2;
//$sum_num3[]=$next->prihod_store3 - $next->rashod_store3;
for($k=0;$k<count($parametrs[stores_id]);$k++) {
$kk=$k+1;
$prst='prihod_store'.$kk;
$rhst='rashod_store'.$kk;
$sum_num[$kk][]=$next->$prst - $next->$rhst;
}//////////for($k=0;$k<count($stores_id);$k++) {

//$price_no_nds_in[]=$next->price_no_nds_in;
if (@$parametrs[detailed])  $nds_in[]=$next->nds_in;
//$sum_no_nds_in[]=$next->sum_no_nds_in;
//$sum_nds_in[]=$next->sum_nds_in;
//$price_no_nds_out[]=$next->price_no_nds_out;
if (@$parametrs[detailed])  $nds_out[]=$next->nds_out;
if (@$parametrs[movement_doc_id]) $doc_id2[]=$next->doc_id2;
//$sum_no_nds_out[]=$next->sum_no_nds_out;
//$sum_nds_out[]=$next->sum_nds_out;
//$pribil[]=$next->pribil;
//$nds_razn[]=$next->nds_razn;
$m++;
}////////////for ($i=0; $i<count($rows); $i++) {
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
    <td> № 
      <?=@$doc_id1[$i]?>
    </td>
<?
for($k=0;$k<count($parametrs[stores_id]);$k++) {
$kk=$k+1;
?>
    <td> 
      <?=$sum_num[$kk][$i]?>
    </td>

	<?
	}//////////////for($k=0;$k<count($stores_id);$k++) {
	?>
  </tr>
  <?
		}///////////for
		?>
</table>