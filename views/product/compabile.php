<table width="100%" border="0" cellspacing="10" cellpadding="1">
  <tr>
    <td>Артикул</td>
    <td>Наименование</td>
  </tr>
  <?
  for ($i=0; $i<count($compabile);$i++) {
  ?>
  <tr >
    <td><?=CHtml::link($compabile[$i]->compprod->product_article, array('/product/details/'.$compabile[$i]->compprod->id))?></td>
    <td><?=CHtml::link($compabile[$i]->compprod->product_name, array('/product/details/'.$compabile[$i]->compprod->id))?></td>
  </tr>
  <?
  }
  ?>
</table>
