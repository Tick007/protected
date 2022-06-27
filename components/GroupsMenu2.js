<script>
var item = new Array();
<?
for ($i=0; $i<count($parrent_array); $i++) {
?>
item[<?=$i?>] = <?=$parrent_array[$i]?>;
<?
}
?>
for (i=0; i<<?=count($parrent_array)?>; i++) {
//alert(i);
qqq = item[i]+"";
tbl_id="group_"+qqq;
ide="pic_"+qqq;
//alert(document.getElementById(tbl_id));
//(tbl_id);
if (document.getElementById(tbl_id) != null) document.getElementById(tbl_id).style.display ="";
if (document.getElementById(ide) != null) document.getElementById(ide).src="http://<?=$_SERVER['HTTP_HOST']?>/images/minus.gif";
}
</script>