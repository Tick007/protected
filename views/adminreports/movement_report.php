<table width="100%" border="0" cellspacing="0" cellpadding="0" class="plain" bgcolor="#CCCCCC">
  <tr> 
    <td valign="top"> 

	
	<!--Эта таблица не покажет, если данные партии не из раходованы, хотя остатки изменились-->
      <table width="100%" border="0" cellspacing="1" cellpadding=" " class="plain" bgcolor="#006666">
        <tr bgcolor="#E9E5D9"> 
          <td rowspan="2" align="left" valign="bottom">Склад</td>
          <td rowspan="2" align="left" valign="bottom">Номенклатура</td>
          <td rowspan="2" align="left" valign="bottom">Дата/документ</td>
          <td rowspan="2" align="left" valign="bottom">Приход</td>
          <td colspan="3">Движение(расход)</td>
          <td rowspan="2" valign="bottom">Расход</td>
          <td rowspan="2" valign="bottom">Остаток</td>
        </tr>
        <tr bgcolor="#E9E5D9"> 
          <td>Кол</td>
          <td>Контрагент</td>
          <td>Документ</td>
        </tr>
        <?
  				for($i=0; $i<count($rows); $i++) {///////////////////////////////////Запрос второго уровня по приходным партиям
			//	list($product_id, $product_name, $series_arrive_dt, $series_id, $series_num, $series_doc_id, $stores_name, $doc_type )=$rows[$i];
			//print_r($rows[$i]);
			extract($rows[$i], EXTR_PREFIX_ALL, 'series');
			//echo $series_series_id.'<br>';
			//Array ( [id] => 8 [product_name] => Коврик в багажник ALFA ROMEO 147 3D Hatchback (полиуретан, с бортом, чёрные) [arrive_dt] => 2011-05-04 11:33:26 [num] => 65 [doc_id] => 2 [name] => Главный склад [type] => Поступление ) 
  ?>
        <tr valign="top" bgcolor="#FFFFFF"> 
          <td align="left"> 
            <?=$series_name?>
          </td>
          <td align="left"> 
            <?=$series_product_name?>
          </td>
          <td align="left"><a href="/admindocs/doc/<?=$series_doc_id?>" target="_blank"><?=$series_type?> № 
            <?=$series_doc_id?>
            от 
            <?=$series_arrive_dt ?>
            </a></td>
          <td align="left"> 
            <?=$series_num?>
          </td>
          <td colspan="3"><table width="100%" border="0" cellspacing="1" cellpadding="1" class="plain" bgcolor="#000099">
              <?
		$sum_rashod = 0;
	
			  $query3="SELECT series_movement.num, series_movement.doc_id,  series_movement.operation_dt, contr_agents.name  FROM series_movement JOIN contr_agents ON contr_agents .id = series_movement.kontragent_id WHERE series_movement.series_id = ".$series_series_id;
// if (@$list_from  )  $query3.="  AND series_movement.operation_dt>='$list_from_sql'  ";
//if (@$list_upto  )  $query3.="   AND series_movement.operation_dt<='$list_upto_sql' ";
				$connection =   Yii::app()->db;		
				$command=$connection->createCommand($query3)	;
				$dataReader=$command->query();
				$rows3=$dataReader->readAll();	
				//while($next3=mysql_fetch_row($res3)) {///////////////////////////////////Запрос третьего уровня по движению по партиям
				for($k=0; $k<count($rows3); $k++) {////
				extract($rows3[$k], EXTR_PREFIX_ALL, 'series_movement');
				//list($series_movement_num, $series_movement_doc_id, $series_movement_operation_dt, $contr_agents_name )=$next3;
				
				$sum_rashod = $sum_rashod+$series_movement_num;
				
  ?>
              <tr bgcolor="#FFFFFF"> 
                <td width="16%"> 
                  <?=$series_movement_num?>
                </td>
                <td width="44%"> 
                  <?=$series_movement_name?>
                </td>
                <td width="40%"><a href="/admindocs/doc/<?=$series_movement_doc_id?>" target="_blank">№ 
                  <?=$series_movement_doc_id?>
                  от 
                  <?=$series_movement_operation_dt?>
                  </a></td>
              </tr>
              <?
			  }///////////////while($next3=mysql_fetch_row($res3)) {///////////////////////////////////Запрос третьего уровня по движению по партиям
			  $ostatok = $series_num - $sum_rashod;
			  ?>
            </table>
			
		  </td>
          <td> 
            <?=$sum_rashod?>
          </td>
          <td> 
            <?=$ostatok?>
          </td>
        </tr>
        <?
		}/////////////////while($next2=mysql_fetch_row($res2)) {///////////////////////////////////Запрос второго уровня по приходным партиям
		?>
				<tr bgcolor="#E9E5D9"> 
          <td align="left" valign="bottom">Склад</td>
          <td align="left" valign="bottom">Номенклатура</td>
          <td align="left" valign="bottom">Дата/документ</td>
          <td align="left" valign="bottom">Расход</td>
          <td colspan="5">&nbsp;</td>
        </tr>
		<?
for($i=0; $i<count($rows2); $i++) {///////////////////////////////////Запрос второго уровня по приходным партиям
			extract($rows2[$i], EXTR_PREFIX_ALL, 'series');
  ?>
        <tr valign="top" bgcolor="#FFFFFF"> 
          <td align="left"> 
            <?=$series_name?>
          </td>
          <td align="left"> 
            <?=$series_product_name?>
          </td>
          <td align="left"><a href="/admindocs/doc/<?=$series_doc_id?>" target="_blank"><?=$series_type?> № 
            <?=$series_doc_id?>
            от 
            <?=$series_arrive_dt ?>
            </a></td>
          <td align="left"> 
            <?=$series_num?>
          </td>
          <td colspan="3">
			
		  </td>
          <td>&nbsp; 
          </td>
          <td>&nbsp; 
          </td>
        </tr>
        <?
		}///////////////for($i=0; $i<count($rows); $i++) {////////////////////////////Запрос второго уровня по приходным партиям
		?>
      </table>
	  <!--Вытаскиваем движение по партиям--> 
   
    </td>
  </tr>
</table>