<div class="form"> 
  <table border="0" cellpadding="1" cellspacing="1">
    <thead>
      <tr>
        <td colspan="4"><i><strong>Контактные данные</strong></i></td>
        </tr>
        <td>Электронная почта</td>
        <td colspan="3"><?=$form->elements['client_email']?></td>
        </tr>
      <tr>
        <td>ФИО</td>
        <td><?=$form->elements['second_name']?></td>
        <td><?=$form->elements['first_name']?></td>
        <td><?=$form->elements['last_name']?></td>
        </tr>
      <tr>
        <td>Телефоны</td>
        <td colspan="3"><?php echo $form->elements['client_tels']?></td>
        </tr>
       <tr>
        <td>Логин</td>
        <td colspan="3"><?
       echo $model->login;
		?></td>
      </tr>
      <tr>
        <td>Новый пароль</td>
        <td colspan="3"><?
    echo CHtml::textField('PrivateRoom[client_password]','', $htmlOptions=array('encode'=>false, 'size'=>30) );
	?></td>
      </tr>
      <tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td colspan="4"><i><strong>Адрес</strong></i></td>
        </tr>
      <tr>
        <td>Индекс</td>
        <td colspan="3"><?php echo $form->elements['client_post_index']?></td>
        </tr>
      <tr>
        <td>Страна/Область/Район</td>
        <td><?=$form->elements['client_country']?></td>
        <td><?=$form->elements['client_oblast']?></td>
        <td><?=$form->elements['client_district']?></td>
        </tr>
      <tr>
        <td>Город/Улица</td>
        <td><?=$form->elements['client_city']?></td>
        <td><?=$form->elements['client_street']?></td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td>Дом/Корпус/Строение</td>
        <td><?=$form->elements['client_house']?></td>
        <td><?=$form->elements['client_korpus']?></td>
        <td><?=$form->elements['client_stroenie']?></td>
        </tr>
      <tr>
        <td>Подъезд/Этаж/Квартира</td>
        <td><?=$form->elements['client_entrance']?></td>
        <td><?=$form->elements['client_flore']?></td>
        <td><?=$form->elements['client_apart']?></td>
        </tr>
      <tr>
        <td>Код домофона</td>
        <td colspan="3"><?php echo $form->elements['client_code']?></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><i><strong>Паспортные данные <br>(для почтовой отправки)</strong></i></td>
        <td colspan="3"><?php echo $form->elements['client_passport']?></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td><i><strong>Комментарии</strong></i></td>
        <td colspan="3"><?=$form->elements['client_comments']?></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4"><i><strong>Для юридических лиц</strong></i></td>
      </tr>
      <tr>
        <td>Наименование компании</td>
        <td colspan="3"><?=$form->elements['urlico_txt']?></td>
      </tr>
     <?
     if (isset($model->kontragent)) {
	 ?>
      <tr>
        <td>Присвоенная вам организация</td>
        <td colspan="3"><strong><?
       echo $model->kontragent->name;
		?></strong></td>
      </tr>
      <?
      }///////  if (isset($model->kontragent)) {
	  ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </thead>
    <?
/*
	while (list($key, $val) = each($model->private_face)) {
						if (@isset($model->private_face_labels[$val])) {
						echo '<tr><td>'.$model->private_face_labels[$val].'</td><td>'.$form->elements[$val].'</td></tr>';
						//echo $val;
						}
				}
*/
?>
    <tr>
      <td colspan="4" align="center"><input name="saveclient" type="submit" value="Сохранить" /></td>
      </tr>
  </table>
</div>
