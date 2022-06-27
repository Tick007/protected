
<div style="height:32px; width:775px;  padding-top:5px; margin-top:0px;background-color:#efefef; border-radius: 5px;">
<div style="width:280px; height:28px; float:left; margin-top:6px; color:#58595b">&nbsp;&nbsp;&nbsp;Быстрый поиск</div>
<div align="center" style=" height:28px; float:left; text-align:left; background-color:transparent; ">
<?php

 echo CHtml::dropDownList('brand',   $brand, $brand_list, array('id'=>'brand'));
?>
</div>
<div style=" height:28px; float:left; text-align:left; padding-left:11px">
<?php
 echo CHtml::dropDownList('model',   @$model, $model_list, array('id'=>'model'));
?>
</div>
</div>
