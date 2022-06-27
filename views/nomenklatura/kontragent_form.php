<?
echo CHtml::beginForm(array('/nomenklatura/updatekagent/'.$contragent->id),  $method='post',$htmlOptions=array('name'=>'ca_form', 'id'=>'ca_form'));  
?>
          <input name="id" type="hidden" value="<?=$contragent->id?>">
<table  border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#003366">
       
           <thead>
        <tr bgcolor="#EAE5D8"  class="fixed">
          <th colspan="4" align="left">Контрагент:&nbsp;<?=$contragent->full_name?></th> 
          </tr>
        </thead>
          <tr> 
            <td bgcolor="#CFC7AD">№</td>
            <td bgcolor="#CFC7AD"> 
            <?=@$kontr_id?>            </td>
            <td align="right" bgcolor="#CFC7AD">Код&nbsp;</td>
            <td bgcolor="#CFC7AD">
            <?
    echo CHtml::textfield('parametrs[kod]', $contragent->kod,  $htmlOptions=array('encode'=>true, 'size'=>10, 'maxlength'=>10)  ) ;
	?></td>
          </tr>
         
		 
          <tr bgcolor="#E9E5D9">
            <td>Префикс</td>
            <td>
            <?
    echo CHtml::textfield('parametrs[prefiks]', $contragent->prefiks,  $htmlOptions=array('encode'=>true, 'size'=>10, 'maxlength'=>10)  ) ;
	?></td>
            <td>Короткое имя&nbsp;</td>
            <td >
            <?
    echo CHtml::textfield('parametrs[name]', $contragent->name,  $htmlOptions=array('encode'=>true, 'size'=>18, 'maxlength'=>255)  ) ;
	?></td>
          </tr>
          <tr> 
            <td bgcolor="#CFC7AD">Полное имя</td>
            <td colspan="3" bgcolor="#CFC7AD">
            <?
    echo CHtml::textfield('parametrs[full_name]', $contragent->full_name,  $htmlOptions=array('encode'=>true, 'size'=>50, 'maxlength'=>255) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
         <tr bgcolor="#E9E5D9">
            <td>ИНН</td>
            <td ><?
    echo CHtml::textfield('parametrs[inn]', $contragent->inn,  $htmlOptions=array('encode'=>true, 'size'=>10, 'maxlength'=>10) , array('class'=>'textfield') ) ;
	?></td>
            <td align="right">КПП&nbsp;</td>
            <td><?
    echo CHtml::textfield('parametrs[kpp]', $contragent->kpp,  $htmlOptions=array('encode'=>true, 'size'=>10, 'maxlength'=>10) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
          <tr> 
            <td bgcolor="#CFC7AD">Юридический адрес</td>
            <td colspan="3" bgcolor="#CFC7AD"><?
    echo CHtml::textarea('parametrs[ur_adress]', $contragent->ur_adress,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>50) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
          <tr bgcolor="#E9E5D9"> 
            <td>Физический адрес</td>
            <td colspan="3" ><?
    echo CHtml::textarea('parametrs[fiz_adress]', $contragent->fiz_adress,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>50) , array('class'=>'textfield') ) ;
	?>            </td>
          </tr>
          <tr> 
            <td bgcolor="#CFC7AD">Тип</td>
            <td colspan="3" bgcolor="#CFC7AD">&nbsp;</td>
          </tr>
          <tr bgcolor="#E9E5D9"> 
            <td>Комментарии</td>
            <td colspan="3" >
            <?
    echo CHtml::textarea('parametrs[comments]', $contragent->comments,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>50) , array('class'=>'textfield') ) ;
	?> </td>
          </tr>
          <tr> 
            <td bgcolor="#CFC7AD">Контакты</td>
            <td colspan="3" bgcolor="#CFC7AD"><?
    echo CHtml::textarea('parametrs[contacts]', $contragent->contacts,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>50) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
         <tr bgcolor="#E9E5D9">
            <td>Группа котрагента</td>
            <td colspan="3" ><?
            echo CHtml::dropDownList('parametrs[groupe]', $contragent->groupe, $contr_agents_groups );
			?>         </td>
          </tr>
           <tr>
            <td bgcolor="#CFC7AD">Р/с</td>
            <td colspan="3" bgcolor="#CFC7AD"><?
    echo CHtml::textfield('parametrs[r_s]', $contragent->r_s,  $htmlOptions=array('encode'=>true, 'size'=>50, 'maxlength'=>50) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
          <tr bgcolor="#E9E5D9">
            <td>К/с</td>
            <td colspan="3"><?
    echo CHtml::textfield('parametrs[k_s]', $contragent->k_s,  $htmlOptions=array('encode'=>true, 'size'=>50, 'maxlength'=>50) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
         <tr>
            <td bgcolor="#CFC7AD">Банк</td>
            <td colspan="3" bgcolor="#CFC7AD">
            <?
    echo CHtml::textfield('parametrs[bank]', $contragent->bank,  $htmlOptions=array('encode'=>true, 'size'=>50, 'maxlength'=>150) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
          <tr bgcolor="#E9E5D9">
            <td>Бик</td>
            <td colspan="3">
            <?
    echo CHtml::textfield('parametrs[bik]', $contragent->bik,  $htmlOptions=array('encode'=>true, 'size'=>50, 'maxlength'=>50) , array('class'=>'textfield') ) ;
	?></td>
          </tr>
           <tr>
            <td bgcolor="#CFC7AD">Добавить склад</td>
            <td colspan="3" bgcolor="#CFC7AD"><input type="checkbox" name="add_store" ></td>
          </tr>
          <tr>
            <td colspan="4" align="center" bgcolor="#CFC7AD"><input name="save_doc" type="submit" value="Сохранить"></td>
          </tr>
          <tr> 
            <td colspan="4" valign="top" bgcolor="#FFFFFF">
            <table  border="0" cellpadding="0" cellspacing="1" bgcolor="#0000CC" width="100%">
  <tr bgcolor="#FFFBF0"> 
    <td bgcolor="#E9E5D9">№</td>
    <td bgcolor="#E9E5D9">Склад</td>
    <td bgcolor="#E9E5D9">Адрес</td>
    <td align="center" bgcolor="#E9E5D9">Вывод в HTML</td>
    <td align="center" bgcolor="#E9E5D9">Удалить</td>
  </tr>
   <?
            if (count($contragent->stores)>0) {
					$stores = $contragent->stores;
					
					for ($i=0; $i<count($stores); $i++) {
							?>
						<tr bgcolor="#FFFFFF"> 
 			   <td><?=$stores[$i]->id?></td>
   			 <td><?
    echo CHtml::textfield('stores[name]['.$stores[$i]->id.']', $stores[$i]->name,  $htmlOptions=array('encode'=>true, 'size'=>20, 'maxlength'=>255)  ) ;
	?></td>
  			  <td><?
    echo CHtml::textfield('stores[store_adress]['.$stores[$i]->id.']', $stores[$i]->	store_adress,  $htmlOptions=array('encode'=>true, 'size'=>20, 'maxlength'=>255)  ) ;
	?></td>
   			 <td align="center"><?
             echo CHtml::checkbox('stores[show_in_html]['.$stores[$i]->id.']', $stores[$i]->show_in_html);
			 ?></td>
   			 <td align="center"><?
             echo CHtml::checkbox('delete_stores['.$stores[$i]->id.']', NULL);
			 ?></td>
 			 </tr>	
							<?
					}//////////////for ($i=0; $i<count($stores); $i++) {
			
			}//////// if (count($contragent->stores)>0) {
			?>
            </table>
            </td>
          </tr>
      </table>        
<?php echo CHtml::endForm(); ?>