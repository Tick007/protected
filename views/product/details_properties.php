<?
if(isset($characterictics)) {
?>
<h3>Технические характеристики</h3>
<table  border="0" width="100%" cellspacing="1" cellpadding="1" >
          
          <tr bgcolor="#E9E5D9"> 
            <td bgcolor="#E9E5D9">Параметр</td>
            <td>Значение</td>
      </tr>
          <?
		  	$color=-1;
			for($i=0; $i<count($characterictics); $i++)  {
			$char = $characterictics[$i];
			
		  ?>
          <tr <?
          if($color==-1) $bgcolor="#F6F6F6";
		  else $bgcolor="#FFFFFF";
		  echo "bgcolor=\"$bgcolor\"";
		  ?>> 
            <td style="font-family:Geneva, Arial, Helvetica, sans-serif; background-image:url(<?=Yii::app()->request->baseUrl?>/images/dot4x20.gif); background-repeat:repeat-x;"> 
             <span style=" background-color:<?=$bgcolor?>; background-image:none;"><?=$char['caract_name']?></span></td>
            <td align="right" style="background-image:url(<?=Yii::app()->request->baseUrl?>/images/dot4x20.gif); background-repeat:repeat-x; padding-right:0px"><span style=" background-color:<?=$bgcolor?>; background-image:none;"><?=$char['value']?>&nbsp;<?=$char['caract_mesuare']?></span></td>
      </tr>
          <?
		  $color=$color*(-1);
		  }
		  ?>
        </table>
<?
        }////////////if(isset($characterictics)) {
		elseif(isset($compability_list)) {
		?>
        <table  width="600" border="0" cellspacing="1" cellpadding="1" class="plain">
  
  <tr bgcolor="#d7d7d7">
    <td>Артикул</td>
    <td>Номенклатура</td>
    </tr>
  <?
  //SELECT products_compability.id, products_compability.compatible, products_compability.active, products.product_name,  products.product_article
	for($i=0; $i<count($compability_list); $i++)  {
			$cl = $compability_list[$i]
  ?>
  <tr bgcolor="#FFFFFF">
    <td><?=$cl['product_article']?></td>
    <td><?=$cl['product_name']?></td>
    </tr>
  <?
  }
  ?>
</table>
<?
        }/////////////////////elseif(isset($compability_list)) {
		?>
        