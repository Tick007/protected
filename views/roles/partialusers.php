<?
for ($i=0; $i<count($models); $i++) {
echo "  <tr>
    <td>".$models[$i]->id."</td>
	 <td>".$models[$i]->second_name."</td>
	  <td>".$models[$i]->first_name."</td>
	   <td>";
	   if ($models[$i]->last_vizit != NULL) echo FHtml::encodeDate($models[$i]->last_vizit, 'medium');
	   echo "&nbsp;</td> 
    <td>";
	//CHtml::link($models[$i]->login ,array('/roles/details?uid='.$models[$i]->id.'&page='.Yii::app()->getRequest()->getParam('page', 1).'&sort='.Yii::app()->getRequest()->getParam('sort', 0) ));
	//echo CHtml::link($models[$i]->login, array('/roles/details', 'uid'=>$models[$i]->id) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
	//echo CHtml::link($models[$i]->login, array('/roles/details', 'id'=>$models[$i]->id) );
	 echo CHtml::link($models[$i]->login, array('/roles/details','id'=>$models[$i]->id) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )"));
	echo "</td>";

echo "<td>";
	if (isset($client_groups) AND empty($client_groups)==false) {
	    echo Chtml::dropDownList('client_vip['.$models[$i]->id.']', $models[$i]->client_vip, $client_groups, array('style'=>'width:170px'));
	}
    echo "</td><td>".$models[$i]->client_email."</td>";
    echo "<td>".(($models[$i]->status==0)?'Disabled':'Enabled')."</td>";
    echo "<td>".CHtml::dropDownList('itemname['.$models[$i]->id.']',@$models[$i]->authassignment->itemname, $roles_list)."</td><td>";
if(isset($models[$i]->card)) echo str_pad(intval ($models[$i]->card->number),8, '0', STR_PAD_LEFT).', '.$models[$i]->card->type;

echo "</td><td>";
/*
	if (isset($models[$i]->kontragents)) {
echo "<table border=\"1\">";
//		echo CHtml::link($models[$i]->kontragent->name, array('/nomenklatura/kontragent/'.$models[$i]->kontragent->id) , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
//		echo CHtml::hiddenField('kontragent_id['.$models[$i]->id.']', $models[$i]->urlico, array('id'=>'kontragent_id_'.$models[$i]->id));


		for ($g=0; $g<count($models[$i]->kontragents); $g++) {
				echo "<tr>";
				//echo $models[$i]->kontragents[$g]->name.'<br>';
						echo "<td>";
						echo CHtml::link($models[$i]->kontragents[$g]->name, array('/nomenklatura/kontragent/'.$models[$i]->kontragents[$g]->id) , array('onclick'=>"return 								hs.htmlExpand(this, { objectType: 'iframe' } )")); 
						//echo CHtml::hiddenField('kontragent_id['.$models[$i]->id.']', $models[$i]->urlico, array('id'=>'kontragent_id_'.$models[$i]->id));
						echo "</td><td>";
						echo CHtml::checkBox('delete_urlico_link['.$models[$i]->id.']['.$models[$i]->kontragents[$g]->id.']');
						echo "</td>";
				echo "</tr>";
		}//////////for ($g=0; $g<count($models[$i]->kontragents); $g++) {

echo "</table>";
	}
	else  {

	
	}
	
*/
	
	echo "</td><td align=\"center\">";
	//echo CHtml::checkBox('delete_urlico_link['.$models[$i]->id.']');
		///////////////////// И в любом случае рисуем форму что бы добавить новую организацию
			 echo CHtml::link('pick up',array('/nomenklatura/contragents/', 'targetitem'=>'kontragent_id_'.$models[$i]->id, 'targetform'=>'userlist_form') , array('onclick'=>"return hs.htmlExpand(this, { objectType: 'iframe' } )")); 
		echo CHtml::hiddenField('kontragent_id['.$models[$i]->id.']', 0, array('id'=>'kontragent_id_'.$models[$i]->id));
		
	echo "</td><td align=\"center\">";
	echo CHtml::checkBox('delete_user['.$models[$i]->id.']');
	echo "</td>
    <td>&nbsp;</td>
  </tr>";
}
?>