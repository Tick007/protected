<?php
$num_of_rows =($data->rowcount($sheet_index=0))-1 ;
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <?php
  for($i=3; $i<=$num_of_rows+1; $i++) {
  ?>
  <tr>
    <td><?php echo  '1.'.$data->val($i, 1);?></td>
    <td><?php echo  '2.'.$data->val($i, 2);?></td>
    <td><?php echo  '3.'.$data->val($i, 3);?></td>
    <td><?php echo '4.'.$data->val($i, 4);?></td>
    <td><?php echo '5.'.$data->val($i, 5);?></td>
    <td><?php echo '6.'.$data->val($i, 6);?></td>
    <td><?php echo '7.'.$data->val($i, 7);?></td>
    <td><?php echo  '8.'.$data->val($i, 8);?></td>
    <td><?php echo  '9.'.$data->val($i, 9);?></td>
    <td><?php echo  '10.'.$data->val($i, 10);?></td>
   <td><?php echo  '11.'.$data->val($i, 11);?></td>
    <td><?php echo '12.'.$data->val($i, 12);?></td>
    <td><?php echo '13.'.$data->val($i, 13);?></td>
    <td><?php echo  '14.'.$data->val($i,14);?></td>
    <td><?php echo '15.'.$data->val($i, 15);?></td>
    <td><?php echo '16.'.$data->val($i, 16);?></td>
    <td><?php echo '17.'.$data->val($i, 17);?></td>
    <td><?php echo '18.'.$data->val($i, 18);?></td>
    <td><?php echo '19.'.$data->val($i, 19);?></td>
    <td><?php echo '20.'.$data->val($i, 20);?></td>
 <td><?php echo '21.'.$data->val($i, 21);?></td>
    <td><?php echo '22.'.$data->val($i, 22);?></td>

  </tr>
  <?php
  }
  ?>
</table>
