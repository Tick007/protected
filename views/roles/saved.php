<div class="updateSummary"><?
 echo $msg;
//CHtml::refresh(1, $url='/roles/index?page='.$page.'&sort='.$sort);
?></div>


<script type="text/javascript">
function refreshAndClose() {
    //window.opener.location.reload(true);
	window.parent.hs.close(); ////////работает
	window.parent.location.reload()
}

$( document ).ready(function() {
	setTimeout(refreshAndClose, 1000);


	
	
});


window.parent.hs.Expander.prototype.onAfterClose = function() {
    window.location.reload();
};

</script>
