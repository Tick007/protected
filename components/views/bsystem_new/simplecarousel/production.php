<div class="">
   <div class="carousel"> 
      <div class="carousel-button-left"><a href="#"></a></div> 
      <div class="carousel-button-right"><a href="#"></a></div> 
        <div class="carousel-wrapper"> 
           <div class="carousel-items"> 
           <?php
           for($i=0, $c=count($banners); $i<$c; $i++){
		   ?>
              <div class="carousel-block">
                    <img src="<?php echo $banner_path.$banners[$i]?>" alt=""  />
              </div>
<?php
		   }
?>
           </div>
        </div>
   </div>
</div>    
<script>

</script>