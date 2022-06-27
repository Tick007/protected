<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th width="250" scope="col">Кладр</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <td valign="top"><?php
    $kt = new KladrTree();
	$kt->draw('citieslist');
	?></td>
    <td align="left" valign="top"><div id="citieslist"></div></td>
  </tr>
</table>
