<script>
function switch_visibility( element_id) {
//alert (level+ " " +element_id);
ide="pic_"+element_id;
tbl_id="group_"+element_id;
//alert (tbl_id);
switched="closed";
//qqq=document.all.item(tbl_id).style.display;
//alert (qqq);
//alert (switched);
//document.all.item(tbl_id).style.display="";
if (document.getElementById(tbl_id).style.display !="") {
document.getElementById(tbl_id).style.display="";
document.getElementById(ide).src="http://<?=$_SERVER['HTTP_HOST']?>/images/minus.gif";
switched="opened";
}

if (document.getElementById(tbl_id).style.display !="none" ) { 
//alert (switched);
if (switched !="opened") {
document.getElementById(tbl_id).style.display="none";
document.getElementById(ide).src="http://<?=$_SERVER['HTTP_HOST']?>/images/plus.gif";
switched="closed";
}
}


}//////////function
</script>