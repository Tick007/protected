<?
//print_r($_POST);
?>
<?
$nomenklat = Yii::app()->getRequest()->getParam('nomenklat', NULL);
$enable_buyer = Yii::app()->getRequest()->getParam('enable_buyer', NULL);
$detailed = Yii::app()->getRequest()->getParam('detailed', NULL);
$enable_suplier = Yii::app()->getRequest()->getParam('enable_suplier', NULL);
$enable_date_split = Yii::app()->getRequest()->getParam('enable_date_split', NULL);
$enable_group_split = Yii::app()->getRequest()->getParam('enable_group_split', NULL);
$child_nomenklat = Yii::app()->getRequest()->getParam('child_nomenklat', NULL);
$enable_store = Yii::app()->getRequest()->getParam('enable_store', NULL);
?>
<br>
<?
//print_r($rows);
?>
<table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain"  bgcolor="#0000CC">
  <tr bgcolor="#CFC7AD">
    <td>&nbsp;</td>
    <td>Дата</td>
    <td>Сгруппа</td>
    <td>Группа</td>
    <td>Номенклатура</td>
    <td>Поставщик</td>
    <td>Склад расход</td>
    <td>Покупатель</td>
    <td>Док.партии</td>
    <td>Док.движения</td>
    <td>Кол-во</td>
    <td>Цена закупки без НДС</td>
    <td>Сумма без НДС</td>
	<?
	if(@$enable_nds==1) {
	?>
    <td>НДС% вх</td>
    <td>Сумма НДС вх</td>
    <td>НДС% исх</td>
    <td>Сумма НДС исх</td>
    <?
	}////////if(@$enable_nds) {
	?>
	<td>Цена исх без НДС</td>
    <td>Сумма исх без НДС</td>
    <td>Разница НДС</td>
		<td>Прибыль</td>
    <td>Прибыль%</td>
  </tr>
  <?
//echo "$query<br>";
$sum_prib=0;
$sum_nds=0;
$m=0;
//while($next=mysql_fetch_row($res)) {
for ($i=0; $i<count($rows); $i++) {
$next=(object)$rows[$i];
$sum_prib = $sum_prib+$next->pribil;
$sum_nds = $sum_nds+$next->nds_razn;
$sgr[]=$next->sgr;
if (@$parametrs[enable_group_split]) $gr[]=$next->gr;
if (@$parametrs[nomenklat] OR @$parametrs[child_nomenklat]) $product_name[]=$next->product_name;
if (@$parametrs[enable_suplier])  $supname[]=$next->supname;
if (@$parametrs[enable_store])$store_name[] = $next->store_name;
if (@$parametrs[enable_buyer]) $kname[]=$next->kname;
if (@$parametrs[detailed]) $doc_id1[]=$next->doc_id1;
if (@$parametrs[detailed] AND @$parametrs[enable_date_split]) $operation_dt[]=$next->operation_dt;
$sum_num[]=$next->sum_num;
$price_no_nds_in[]=$next->price_no_nds_in;
if (@$parametrs[detailed])  $nds_in[]=$next->nds_in;
$sum_no_nds_in[]=$next->sum_no_nds_in;
$sum_nds_in[]=$next->sum_nds_in;
$price_no_nds_out[]=$next->price_no_nds_out;
if (@$parametrs[detailed])  $nds_out[]=$next->nds_out;
if (@$parametrs[movement_doc_id]) $doc_id2[]=$next->doc_id2;
$sum_no_nds_out[]=$next->sum_no_nds_out;
$sum_nds_out[]=$next->sum_nds_out;
$pribil[]=$next->pribil;
$pribil_ps[]=$next->pribil_ps;
$nds_razn[]=$next->nds_razn;
$m++;
//echo $next->pribil_ps."<br>";
}//////////////while
  for ($i=0; $i<$m; $i++) {
  $ii=$i+1;
		  ?>
  <tr bgcolor="#FFFFFF">
    <td><?=$ii?></td>
    <td> 
      <?=@$operation_dt[$i]?>    </td>
    <td> 
      <?=$sgr[$i]?>    </td>
    <td><?=@$gr[$i]?></td>
    <td class="plainslim"> 
      
      <?=substr(@$product_name[$i],0,100)?>    </td>
    <td>
      <?=@$supname[$i]?>    </td>
    <td> 
      <?=@$store_name[$i]?>    </td>
    <td> 
      <?=@$kname[$i]?>    </td>
    <td> № 
      <?=@$doc_id1[$i]?>    </td>
    <td>№ 
      <?=@$doc_id2[$i]?>    </td>
    <td> 
      <?=$sum_num[$i]?>    </td>
    <td> 
      <?=$price_no_nds_in[$i]?>    </td>
    <td> 
      <?=$sum_no_nds_in[$i]?>    </td>
	<?
	if(@$enable_nds==1) {
	?>
    <td> 
      <?=@$nds_in[$i]?>    </td>
    <td> 
      <?=$sum_nds_in[$i]?>    </td>
    <td><?=@$nds_out[$i]?>    </td>
    <td><?=$sum_nds_out[$i]?>    </td>
	<?
	}/////nds
	?>
    <td><?=$price_no_nds_out[$i]?>    </td>
    <td><?=$sum_no_nds_out[$i]?>    </td>
    <td> 
      <?=$nds_razn[$i]?>    </td>
	<td> 
      <?=$pribil[$i]?>    </td>
    <td><?=$pribil_ps[$i]?><?/*=round(($pribil[$i]/$sum_no_nds_in[$i])*100,2);*/?></td>
  </tr>
  <?
		}///////////for
		?>
  <tr bgcolor="#FFFFFF">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<?
	if(@$enable_nds==1) {
	?>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
	<?
	}/////////nds
	?><td>&nbsp;</td>
    <td>&nbsp;</td>
    <td> 
      <?=$sum_nds?>    </td>
	<td> 
      <?=$sum_prib?>    </td>
    <td>&nbsp;</td>
  </tr>
</table>