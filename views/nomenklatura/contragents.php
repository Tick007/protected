<script language="javascript">





function UpdateOpener(id) {
//parent.opener.document.getElementById('add_product').innerHTML = id;
targetitem = '<?=$targetitem?>';
targetform = '<?=$targetform?>';

////////////теперь нужно вызвать функцию для обновления второго списка
window.parent.myfunc(id);

//parent.opener.document.getElementById(targetitem).value = id;//////////работает для отделно откарытого окна, не работает для hislide
window.parent.document.getElementById(targetitem).value = id;/////////////////работает на hislide



//alert(window.parent.document.getElementById(targetitem).value);///////////////////работает на hislide
//parent.document.forms.price_form.submit();
//fname='price_form';
//document.getElementById('price_form').submit();
//parent.opener.document.forms[parent_action_form].submit();
//parent.opener.document.forms[targetform].submit();
//parent.opener.submit();
//document.close();
}
</script>
<form action="/nomenklatura/contragents/" method="post" name="form"><table width="auto" border="0" cellspacing="3" cellpadding="0" bgcolor="#CFC7AD">
  <tr>
    <td class="plain"><font color="#000000"><strong>Контрагенты</strong></font></td>
  </tr>
  <tr>
    <td ><table width="100%" border="0" cellspacing="5" cellpadding="0" bgcolor="#FFFBF0">
      <tr>
        <td class="plain"><?
		$gr_id = Yii::app()->getRequest()->getParam('id', NULL);
        if ($gr_id>0) echo CHtml::checkBox('create_ca').' -Создать контрагента';
		else echo 'Для создания нового контрагента выберите группу';
		echo CHtml::hiddenField('id',  $gr_id);
		?></td>
        <td class="plain"><?
         echo CHtml::submitButton('Применить', $htmlOptions=array ('name'=>'apply' ));
		?></td>
        <td align="right" class="plain">&nbsp;</td>
        <td class="plain">
        <?
    echo CHtml::dropDownList('kontragent_id', 0, array(1=>1, 2=>2, 3=>3), array ('ajax' => array('type'=>'POST', 'url'=>CController::createUrl('/admindocs/storelist/'), 'update'=>'#table_content') ), $htmlOptions=array('encode'=>false) );
	?></td>
     </tr>
      <tr>
        <td width="200" colspan="2" valign="top"  bgcolor="#898477"><table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td valign="top" width="200">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="plain" bgcolor="#FFFFF0">

  <tr>
    <td width="100%" colspan="3" valign="top">
<?
$tree = new TreeContr($targetform, $targetitem);
?>
</table></td>
  </tr>
</table></td>
        <td width="500" colspan="2" valign="top" bgcolor="#898477">
        <div class="scroll-table">
        <table width="100%" border="0" cellspacing="1" cellpadding="1">
        <thead>
        <tr bgcolor="#EAE5D8"  class="fixed">
          <th>В док</th> 
          <th>ИНН</th>
          <th>Наименование</th>
          <th>&nbsp;</th>
          <th>Правка</th>
          </tr>
        </thead>
       <tbody id="table_content">

<?
if(isset($models) && $models!=null)for ($i=0; $i<count($models); $i++) {

		?>
        <tr bgcolor="#FFFFFF" class="plainslim">
          <td align="center"><a style="cursor:pointer" class="narrow" onClick="{UpdateOpener(<?=$models[$i]->id?>)}">&lt;&lt;&lt;</a></td> 
          <td><?=$models[$i]->inn?></td>
          <td>
            <?=$models[$i]->name?>          </td>
          <td align="center">&nbsp;</td>
          <td align="center">
          <?
          echo CHtml::link('правка', '/nomenklatura/kontragent/'.$models[$i]->id, array('target'=>'blank'));
		  ?></td>
          </tr>
        <?
		} /////////  for ($i=0; $i<count($models); $i++) {
		else {
		    ?>
		    <tr><td colspan="5"  bgcolor="#FFFFFF">Нет записей</td></tr>
		    <?php 
		}
		?>
</tbody>
</table>
</div></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"  bgcolor="#898477">&nbsp;</td>
        <td colspan="2" valign="top" bgcolor="#898477" id="ostatki">&nbsp;</td>
      </tr>
    </table></td> 
  </tr>
</table>
 </form>