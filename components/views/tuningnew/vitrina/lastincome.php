<!-- Featured Slider -->
<section class="featured-pro wow animated parallax parallax-2">
	<div class="container">
		<div class="std">
			<div class="slider-items-products">
				<div class="featured_title center">
					<h2><?php echo $title?></h2>
				</div>
				<?php 
              if(isset($products) AND  is_array($products)) {
              ?>
				<div id="featured-slider" class="product-flexslider hidden-buttons set_align_img ">
					<div class="slider-items slider-width-col4">
					<?php 
                foreach ($products as $id => $product){
                ?>
						<!-- Item -->
						<div class="item">
							<div class="col-item">
							 <?php 
                    if($product['product_sellout']==1){
                    ?>
								<div class="sale-label sale-top-right">Sale</div>
								<?php 
                    }
								?>
								<div class="images-container">
									<a class="product-image" title="<?php echo $product['name']?>" href="<?php echo Yii::app()->createUrl('catalog/info', array('alias'=>$product['category_alias'], 'id'=>$id))?>"> 
									<img src="<?php
									$src_new = Yii::app()->baseUrl.'/pictures/add/icons/'.$product['icon_id'].'.png';
									if(file_exists($_SERVER['DOCUMENT_ROOT'].$src_new)) echo $src_new;
									else $src =  Yii::app()->baseUrl.'/pictures/'.$id.'.jpg';
									if(isset($src) && file_exists($_SERVER['DOCUMENT_ROOT'].$src)) echo $src;
									else echo Yii::app()->baseUrl.'/images/nophoto_200.png';
									?>"
										class="img-responsive" alt="a" />
									</a>
									<div class="actions">
										<div class="actions-inner">
											<button type="button" title="В корзину"  rel="<?php echo $id?>"
												class="button btn-cart">
												<span>В корзину</span>
											</button>
											<ul class="add-to-links">
												<li><a href="#" title="В избранное" class="link-wishlist"><span>В
															избранное</span></a></li>
												<li><a href="#" title="В сравнение" class="link-compare "><span>В
															сравнение</span></a></li>
											</ul>
										</div>
									</div>
									<div class="qv-button-container">
										<a href="" class="qv-e-button btn-quickview-1"><span><span>Просмотр</span></span></a>
									</div>
								</div>
								<div class="info">
									<div class="info-inner">
										<div class="item-title">
											<a title=" Sample Product" href="l"> <?php echo $product['name']?> </a>
										</div>
										<!--item-title-->
										<div class="item-content">
											<div class="ratings">
												<div class="rating-box">
													<div style="width: 60%" class="rating"></div>
												</div>
											</div>
											<div class="price-box">
												<p class="special-price">
													<span class="price"> <?php echo $product['price']?></span>
												</p>
												<p class="old-price">
													<span class="price-sep">-</span> <span class="price">
														<?php echo $product['price_old']?></span>
												</p>
											</div>
										</div>
										<!--item-content-->
									</div>
									<!--info-inner-->

									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<!-- Item END-->
				<?php 
                }
                ?>
					</div>
				</div>
				<?php 
              }
				?>
			</div>
		</div>
	</div>
</section>
<!-- End Featured Slider -->