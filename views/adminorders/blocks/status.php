
<?php


$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'mydialog2',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Изменение статуса заказа',
		'dialogClass'=> 'profress_div',
        'autoOpen'=>true,
        'modal'=>true,
		'width'=>600,
		//'height'=>400,
    ),
));
?>
<div  style="width:570px; height:120px; padding-left:10px">

<div  style=" float:right; background:url(/themes/enterteh/images/del_cart.png); width:25px;  background-repeat:no-repeat; background-position:right; cursor:pointer; height:21px; display:none" onclick="{
                  $('#mydialog2').dialog('close');
                    }" id="dialog_close">&nbsp; </div>
                    <h3>Поменяйте статус заказа</h3>
                    <div style="clear:both"></div>
<?
            echo CHtml::dropDownList('order_status_force', $order_status, $statlist );
			?>
<br><br>
*Это окно будет всплывать, пока заказ не примет один из 3х последних статусов.
<br><br>
</div>
<?php $this->endWidget();



?>


