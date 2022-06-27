<?php 
//$clientScript = Yii::app()->clientScript;
/*
Yii::app()->clientScript->scriptMap=array(
		'jquery.js'=>false,
);

$clientScript->registerScriptFile( 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', CClientScript::POS_HEAD);
*/
//$clientScript->registerScriptFile(Yii::app()->theme->baseUrl .'/js/threesixty/src/threesixty.js', CClientScript::POS_HEAD);
//$clientScript->registerCSSFile( Yii::app()->theme->baseUrl.'/js/threesixty/src/styles/threesixty.css?v='.rand(), 'screen');
?>
<div class="sixty-slider">


<?php
$banners = FHtml::get_files ( $_SERVER ['DOCUMENT_ROOT'] . $folder, 1 );
//print_r ( $banners );
if (isset ( $banners ) and empty ( $banners ) == false) {
    $ban_s = count($banners);
    for($i = ($ban_s-1); $i>=0;  $i--) {
        $big_url = '';
		$img_url = $folder . $banners [$i];
		$img_url_thumb = $img_url;

		$big_file = $_SERVER ['DOCUMENT_ROOT'] . $folder.'/big/'.$banners [$i];
		if(file_exists($big_file) && is_file($big_file)) $big_url = $folder.'/big/'.$banners [$i];



		?>
		
<?php 
if (strpos($img_url_thumb, 'kpp')) $t='kpplink';
elseif (strpos($img_url_thumb, 'hoodset')) $t='hoodset';
elseif (strpos($img_url_thumb, 'hood')) $t='hoodlink';
elseif (strpos($img_url_thumb, 'pinless')) $t='pinlessval';
elseif (strpos($img_url_thumb, 'master')) $t='masterlink';
elseif (strpos($img_url_thumb, 'pin')) $t='vallink';
elseif (strpos($img_url_thumb, 'lamp')) $t='headlamp';
elseif (strpos($img_url_thumb, 'tyre')) $t='sparetire';
elseif (strpos($img_url_thumb, 'control')) $t='controlblocklink';
elseif (strpos($img_url_thumb, 'kpp')) $t='kpplink';
elseif (strpos($img_url_thumb, 'electro')) $t='electrolink';

//echo $t;
if (strpos($img_url_thumb, 'php')){
    ?>
    <div class="slickimg slickvideo <?php echo $t?>">
    <?php 
    include $big_file = $_SERVER ['DOCUMENT_ROOT'] . $img_url;?>
    </div>
    <?php 
}
else{
?>


<div class="slickimg <?php echo $t?>"><a href="<?php echo (($big_url!='')? $big_url:$img_url_thumb)?>" data-type="image" class="fancybox" data-fancybox="gallery<?php echo $t?>" >
		<img src="<?php echo $img_url_thumb?>" alt="Golden Wheat Field">
	</a>
</div>
									
									
									<?php 
}
}
									}
									?>
									

									
</div>



<script>
///////////////слайдер

$(document).ready(function(){

	SlickObject = $('.sixty-slider').slick({
		  dots: true,
		  speed: 300,
		  slidesToShow: 3,
		  adaptiveHeight: true,
		  arrows: true,
		  infinite: true

		});
/*
	$(".fancybox").fancybox({
		fitToView	: false,
		helpers : {
			overlay : {
				locked : false
			}
		}
	});
	*/


	
	});




</script>


                                        <script type="text/javascript">
    var totalImg = 13;
    var currentImg = 1;

    $(function(){$('img.big_preview').click(function(){nextPhoto(1);});})

    function nextPhoto(d)
    {
        var c = currentImg + d;
        var k = c > totalImg ? 1 : (c < 1 ? totalImg : c);
        show_big_preview(k);
    }

    function show_big_preview(k)
    {
        $('#big_preview_' + currentImg).css({"display":"none"});
        $('#preview_' + currentImg).removeClass("preview_selected");
        currentImg = k;
        $('#big_preview_' + k).fadeIn("slow");

        var co = $('#preview_' + k);
        var to = $('#previews');
        var tw = to.width();
        var tl = parseInt(to.css('left'));
        var lw = $('div.preview_block').width();
        var cw = co.width();
        var cl = co.position().left;
        co.addClass("preview_selected");

        if(tw <= lw) return false;
        var hl = lw / 2 - cw / 2;
        var d = hl - (cl + tl);
        var nl = tl + d;
        nl = nl > 0 ? 0 : (nl < -tw + lw ? -tw + lw : nl)-2;
        to.animate({'left': nl + 'px'}, 300);
    }

    jQuery(document).ready(
    function()
    {
        $('img.big_preview').unbind();
        $(function(){$('img.big_preview').click(function(){show_full_size();});})
    }
)

    function show_full_size()
    {
        $('#img_full_size_'+currentImg).trigger('click');
    }
</script>
