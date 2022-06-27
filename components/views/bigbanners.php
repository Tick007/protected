<style>
ul#portfolio{
        padding: 0;
        margin: 0;
        list-style-type: none;
		height:600px;
}
ul#portfolio li{
        padding: 0;
		max-height: 500px;
		overflow:hidden;
}
</style>
<script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>    
<script type="text/javascript" src="/js/jquery.innerfade.js"></script> 

<script type="text/javascript"> 
       $(document).ready( 
       function(){ 
        $('#news').innerfade({ 
         animationtype: 'slide', 
         speed: 750, 
         timeout: 2000, 
         type: 'random', 
         containerheight: '1em' 
        }); 
           
        $('ul#portfolio').innerfade({ 
         speed: 1000, 
         timeout: 3000, 
         type: 'sequence', 
         containerheight: '220px' 
        }); 
           
        $('.fade').innerfade({ 
         speed: 1000, 
         timeout: 6000, 
         type: 'random_start', 
         containerheight: '1.5em' 
        }); 
           

      }); 
      </script>
      
 <div >     
<ul id="portfolio">        

<?
foreach ($banners as $imgsrc=>$link ):
$img = "<img border=\"0\" src=\"/pictures/banners/".$imgsrc."\">";
echo '<li>'.CHtml::link($img, '/product/details/'.$link).'</li>'; 
endforeach;
?>
</ul>
</div>