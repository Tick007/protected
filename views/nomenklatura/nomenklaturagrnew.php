<?php
//Плагин TreeView для jquery безнадежно устарел, работает только со старым jquery.min.11.2.js
//https://github.com/jzaefferer/jquery-treeview
//jquery-migrate не знает ничего о такой старой версии jquery
//////Надо смотреть что будет в Yii3

Yii::app()->clientScript->scriptMap=array(
    // 'jquery.js'=>false,
    'jquery.min.js'=>'/js/jquery.min.11.2.js',
    // 'jquery.js'=>'/js/jquery-3.6.0.min.js',
    // 'jquery.min.js'=>'/js/jquery-3.6.0.min.js',
    
);

//Yii::app()->clientScript->registerScriptFile('/js/jquery-migrate-1.4.1.min.js', CClientScript::POS_HEAD);

?>
<script>
function UpdateOpener(id) {
targetitem = '<?=$targetitem?>';
targetform = '<?=$targetform?>';
window.parent.myfunc_razdel(id, targetform, targetitem);
}

function closeshadowbox(){
this.parent.Shadowbox.close();
//alert('qweqweqew');
//$('#sb-nav-close').click();
//console.log($('#sb-nav-close'));
//$('#sb-container').remove();
//console.log($('#sb-container'));
}



</script>

<div class="privareroom_catalog_select" style="height:100%">
<div style="height:20px; padding-left:10px; padding-right:10px; padding-top:5px; border-bottom: 1px  solid #333"><div style="float:left">Выберите категорию из списка:</div>
<div style="float:right; background-image:url(/themes/classic/img/del.png); cursor:pointer"><a title="Закрыть" onclick="{closeshadowbox()}" style="text-decoration:none">&nbsp;&nbsp;&nbsp;</a></div>
</div>
				<div style="padding:0px 10px 10px 10px; background-color:#FFF "><?php

			$tree = new TreeGroups($targetform, $targetitem); 
			?></div>
			</div>


