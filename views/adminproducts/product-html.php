<?php


 echo CHtml::beginForm(array('adminproducts/product_update_main', 'id'=>$product->id, 'group'=>$group,  'char_filter'=>Yii::app()->getRequest()->getParam('char_filter')),  $method='post',$htmlOptions=array('name'=>'MainParams'));  ?>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="125" valign="top">HTML Титл:</td>
    <td valign="top"><?php echo CHtml::textfield('product_html_title', $product->product_html_title,  $htmlOptions=array('encode'=>true, 'size'=>50 )  ) ?>
    
    </td>
  </tr>
  
  <tr>
    <td valign="top">HTML keywords</td>
    <td valign="top"><?php
	 echo CHtml::textarea('product_html_keywords', $product->product_html_keywords,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>70, 'style'=>"font-family:Tahoma" )  ) ?></td>
  </tr>
  <tr>
    <td valign="top">HTML description</td>
    <td valign="top"><?php
	 echo CHtml::textarea('product_html_description', $product->product_html_description,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>70, 'style'=>"font-family:Tahoma" )  ) ?></td>
  </tr>
  <tr>
    <td valign="top">Короткое описание</td>
    <td valign="top"><?php
	$this->widget('application.extensions.tinymce.ETinyMce', array('name'=>'product_short_descr', 'EditorTemplate'=>'simple', 'id'=>'product_short_descr', 'value'=>$product->product_short_descr, 'height'=>'200px'));
	 //echo CHtml::textarea('product_short_descr', $product->product_short_descr,  $htmlOptions=array('encode'=>true, 'rows'=>4, 'cols'=>70, 'style'=>"font-family:Tahoma" )  ) ;
	 ?>
</td>
  </tr>
  <tr>
    <td width="125" valign="top">Полное описание:</td>
    <td valign="top"><?php
	// echo CHtml::textarea('product_full_descr', $product->product_full_descr,  $htmlOptions=array('encode'=>true, 'rows'=>10, 'cols'=>70, 'style'=>"font-family:Tahoma" )  ) ?>
    
    <?php
	$this->widget('application.extensions.tinymce.ETinyMce', array('name'=>'product_full_descr', 'EditorTemplate'=>'simple', 'id'=>'product_full_descr', 'value'=>$product->product_full_descr, 'height'=>'400px'));
?>
    </td>
  </tr>
  
  
  
  <tr>
    <td width="125" valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top"><?
      echo CHtml::submitButton('Сохранить', $htmlOptions=array ('name'=>'save_html_parametrs' , 'alt'=>'Сохранить', 'title'=>'Сохранить'));
	?></td>
  </tr>
</table>
<?php echo CHtml::endForm(); ?>