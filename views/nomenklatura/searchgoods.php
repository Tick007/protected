 <?php
// print_r($ostatki);
 if(isset($id))echo CHtml::hiddenField('category_id', $id);
 ?>
 <table width="auto" border="0" cellspacing="1" cellpadding="1" class="searchgoodsresult">
        <thead>
        <tr bgcolor="#EAE5D8"  class="fixed">
          <th rowspan="2">В док</th> 
          <th rowspan="2">Группа</th>
          <th rowspan="2">Артикул</th>
          <th rowspan="2"><?php
          echo CHtml::link('Наименование', '#', array('style'=>'color:#06F', 'onclick'=>'sortable("pname")'));
		  ?></th>
          <th colspan="3">Статусы</th>
          <th colspan="3">Цена</th>
          <?php
          if(isset($stores) AND empty($stores)==false) {
				for($k=0; $k<count($stores); $k++) {
					?><th colspan="2"><?php
                    echo $stores[$k]->name;
					?>
					</th><?php
				} 
			}
		  ?>
          </tr>
        <tr bgcolor="#EAE5D8"  class="fixed">
          <th>вкл.</th>
          <th>распр.</th>
          <th><span title="Запрет обновления цены. При отмеченном не будет попадать в список на обновление при загрузке прайслиста">з.о.ц.</span></th>
          <th><?php
          echo CHtml::link('Цена', '#', array('style'=>'color:#06F', 'onclick'=>'sortable("price")'));
		  ?></th>
          <th>РРЦ</th>
          <th>распр.</th>
        <?php
          if(isset($stores) AND empty($stores)==false) {
				for($k=0; $k<count($stores); $k++) {
					?>
          <th>шт.</th>
          <th>руб.</th> 
          <?php
				}}
		  ?>
        </tr>

        </thead>
        <tbody>
<?php
$table = '';
for ($i=0; $i<count($models); $i++) {
				// $task_list[$data[$i]->id]=$data[$i]->product_name;///////////Список брендов для списка выбора?>
				<tr bgcolor="#FFFFFF" >
				<?php
			  echo "<td align=\"center\"><a style=\"cursor:pointer\" onClick=\"{UpdateOpener(".$models[$i]->id.")}\">&lt;&lt;&lt;</a></td> 
			   <td>";
			   if (isset($models[$i]->belong_category)) $table.=$models[$i]->belong_category->category_name;
			  echo " </td>";?>
			  <td>
			 <?php
			 $url=Yii::app()->createUrl('adminproducts/product', array('id'=>$models[$i]->id, 'group'=>$models[$i]->category_belong));
              if(trim($models[$i]->product_article)!='')echo CHtml::link($models[$i]->product_article, $url, array('target'=>'_blank'));
			  else echo CHtml::link('в карточку', $url, array('target'=>'_blank'));
			 ?></td>
			  <td align="left" class="prodname">
              <?php echo mb_substr($models[$i]->product_name, 0, 75, 'utf-8');
			 ?> </td><td align="center"><?php
             //echo  $models[$i]->product_visible;
			 echo CHtml::checkbox('product_visible['.$models[$i]->id.']', $models[$i]->product_visible,  array('class'=>'adjustablestatus', 'id'=>'product_visible_'.$models[$i]->id));
			 ?></td><td align="center">&nbsp;</td><td align="center"><?php
             //echo CHtml::checkbox('product_price_no_auto_update['.$models[$i]->id.']', $models[$i]->product_price_no_auto_update,  array('class'=>'autoupdatelock', 'id'=>'product_price_no_auto_update_'.$models[$i]->id));
			 ?></td>
			  <td align="left" class="prodprice" <?php
              if(isset($models[$i]->product_price) AND isset($models[$i]->product_price_recomended)) {
					if($models[$i]->product_price<$models[$i]->product_price_recomended) echo ' style="background-color:#FF0000" ';
				}
			  ?>><?php
              //echo $models[$i]->product_price; 
			  echo CHtml::textfield('price['.$models[$i]->id.']', $models[$i]->product_price, array('size'=>3, 'class'=>'adjustableprice', 'id'=>'price_with_nds_'.$models[$i]->id));
			  ?></td>
              <td align="left" class="prodprice"><?php
              //echo $models[$i]->product_price; 
			 echo CHtml::textfield('price_recomended['.$models[$i]->id.']',@$models[$i]->product_price_recomended, array('size'=>3, 'class'=>'adjustablerrpprice', 'id'=>'price_rrp_'.$models[$i]->id));
			  ?></td>
               <td align="left" class="prodprice"><?php
              //echo $models[$i]->product_price; 
			 echo CHtml::textfield('sellout_price['.$models[$i]->id.']',@$models[$i]->sellout_price, array('size'=>3, 'class'=>'adjustableselloutprice', 'id'=>'sellout_price_'.$models[$i]->id));
			  ?></td>

<?php
          if(isset($stores) AND empty($stores)==false) {
				for($k=0; $k<count($stores); $k++) {
					?><td   class="prodprice"><nobr>
                    <?php
                   // echo $stores[$k]->name;
				   if(isset($ostatki[$models[$i]->id][$stores[$k]->id])) echo $ostatki[$models[$i]->id][$stores[$k]->id]['quantity'];
				  else echo 'n/a';
					?></nobr>
					</td><td  class="prodprice">
                    <?php
                       if(isset($ostatki[$models[$i]->id][$stores[$k]->id])) echo $ostatki[$models[$i]->id][$stores[$k]->id]['store_price'];
					   else echo 'n/a';
					?>
                    </td><?php
				} 
			}
		  ?>
</tr>
<?php
}



?></tbody>
</table>