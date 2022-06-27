<div style="display:table">
<h3>Загружаемый контент</h3>
<?
for ($i=0; $i<count($gruppa_files); $i++) {///////
	?>
	<div style="float:left; width:100px; height:108px; margin-bottom:10px; margin-right:10px; text-align:center">
    
    <?
	if ($gruppa_files[$i]->ext == 'xls' OR $gruppa_files[$i]->ext == 'xlsx')  $pict = "<img border=\"0\" height=\"50px\" width=\"50px\" src=\"/images/xls.png\">";
	else if ($gruppa_files[$i]->ext == 'pdf') $pict = "<img border=\"0\" height=\"50px\" width=\"50px\"   src=\"/images/pdf.png\">";
	else if ($gruppa_files[$i]->ext == 'doc' OR $gruppa_files[$i]->ext == 'docx') $pict = "<img border=\"0\" height=\"50px\" width=\"50px\"  src=\"/images/doc.png\">";
	
	echo CHtml::link($pict."<br>".$gruppa_files[$i]->description, "/prices/".$gruppa_files[$i]->id.'.'.$gruppa_files[$i]->ext, array('target'=>'_blank'));
	?>
    </div>
	<?
}///////////for ($i=0; $i<count($gruppa_files); $i++) {
?><div style="float:none"></div>
</div>
<br>