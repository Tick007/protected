<?php 
if($cont=='page' && $action=='byalias') $alias = Yii::app()->getRequest()->getParam('id', null);
?>
<nav>
    <div class="container">
      <div class="nav-inner"> 
        
        <!-- mobile-menu -->
        <div class="hidden-desktop" id="mobile-menu">
          <ul class="navmenu">
            <li>
              <div class="menutop">
                <div class="toggle"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></div>
                <h2>Меню</h2>
              </div>
              
              <ul style="display:none;" class="submenu">
                <li>
                  <ul class="topnav">
                    <li class="level0 nav-6 level-top first parent"> <a class="level-top" href="/"> <span>Главная</span> </a>

                      <!-- <ul class="level0">
                        <li class="level1"><a href=""><span>Кнопка</span></a> </li>
                        <li class="level1"><a href=""><span>Кнопка</span></a> </li>
                        <li class="level1"><a href=""><span>Кнопка</span></a> </li>
                        <li class="level1"><a href=""><span>Кнопка</span></a> </li>
                      </ul>-->
                    </li>
                    
                    <li class="level0 nav-6 level-top"> <a class="level-top" href="<?php echo Yii::app()->createUrl('catalog/index')?>"> <span>Магазин</span> </a>
   
                        </li>
                        <li class="level1 nav-10-4"> <a href="<?php echo Yii::app()->createUrl('page/byalias',array('id'=>'contacts'))?>"> <span>Контакты</span> </a> </li>
                        <li class="level1"> <a href="<?php echo Yii::app()->createUrl('page/byalias', array('id'=>'delivery'))?>"> <span>Доставка</span> </a> </li>
                        <li class="level1"> <a href="<?php echo Yii::app()->createUrl('page/byalias', array('id'=>'about'))?>"> <span>О магазине</span> </a> </li>

                        
                      </ul>
                    </li>
                    
                  </ul>
                </li>
              </ul>
            </li>
          </ul>
          <!--navmenu--> 
        </div>
        <!--End mobile-menu --> 
        <a class="logo-small" title="Про тюнинг" href="/"><img alt="Про тюнинг" src="<?php echo Yii::app()->theme->baseUrl?>/images/logo4-white.png" width="100" style="margin-top:3px;"></a>
        <ul id="nav" class="hidden-xs">
          <li class="level0 parent drop-menu"><a href="/" class="<?php print(($cont=='site' )?"active":"")?>"><span>Главная</span> </a>
            <!-- <ul class="level1">
              <li class="level1"><a href=""><span>Fashion Store</span></a> </li>
              <li class="level1"><a href=""><span>Digital Store</span></a> </li>
              <li class="level1"><a href=""><span>Furniture Store</span></a> </li>
              <li class="level1"><a href=""><span>Jewellery Store</span></a> </li>
            </ul>-->
          </li>
          <li class="level0 parent drop-menu"><a href="#" class="<?php print((@$alias=='delivery' )?"active":"")?>"><span>О магазине</span> </a>
            <ul class="level1">
              <li class="level1 first"><a href="<?php echo Yii::app()->createUrl('page/byalias',array('id'=>'delivery'))?>"><span>Доставка и оплата</span> </a> </li>
              <li class="level1 nav-10-4"> <a href="<?php echo Yii::app()->createUrl('cart/index')?>"> <span>Коризина</span> </a> </li>
             <!--  <li class="level1 first parent"><a href="<?php echo Yii::app()->createUrl('page/byalias',array('id'=>'delivery'))?>"><span>Оплата</span></a>
                <ul class="level2">
                  <li class="level2 nav-2-1-1 first"><a href="checkout_method.html"><span>Способы оплаты</span></a></li>
                  <li class="level2 nav-2-1-5 last"><a href="checkout_billing-info.html"><span>Проверить платеж</span></a></li>
                </ul>
              </li>-->
              
            </ul>
          </li>
          <li class="level0 nav-5 level-top first"> 
          <a class="level-top <?php print(($cont=='catalog' )?"active":"")?>" href="<?php echo Yii::app()->createUrl('catalog/index')?>"><span> Магазин</span> </a></li>

<?php
//$this->render(Yii::app()->theme->name.'/navbar/shop', array( 'groups'=>$groups));
?>
         <!-- <li class="level0 nav-7 level-top parent"> <a href="" class="level-top"> <span>Отзывы</span> </a>-->

          </li>
          <li class="level0 nav-5 level-top first"> <a class="level-top <?php print((@$alias=='opt' )?"active":"")?>" href="<?php echo Yii::app()->createUrl('page/byalias',array('id'=>'opt'))?>"> <span>Оптовым покупателям</span> </a>

          </li>
          <li class="level0 nav-5 level-top parent">
          <a href="<?php echo Yii::app()->createUrl('page/byalias',array('id'=>'contacts'))?>" class="<?php print((@$alias=='contacts' )?"active":"")?>"><span>Контакты</span> </a>

          </li>
          <li class="level0 parent drop-menu"><a href="<?php echo Yii::app()->createUrl('page/list')?>"><span>Статьи и обзоры</span> </a>
            
          </li>
          <li class="nav-custom-link level0 level-top parent"> <a class="level-top" href="#"><span>BRANDS</span></a>
            <div class="level0-wrapper custom-menu" style="left: 0px; display: none;">
              <div class="header-nav-dropdown-wrapper clearer">
                <div class="grid12-5">
                  <div class="custom_img"><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl?>/images/custom-img1.jpg" alt="custom img1"></a></div>
                  <p>Описание мега супер производителя</p>
                  <button class="learn_more_btn" title="В корзину" type="button"><span>Читать</span></button>
                </div>
                <div class="grid12-5">
                  <div class="custom_img"><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl?>/images/custom-img2.jpg" alt="custom img2"></a></div>
                  <p>Описание мега супер производителя</p>
                  <button class="learn_more_btn" title="В корзину" type="button"><span>Читать</span></button>
                </div>
                <div class="grid12-5">
                  <div class="custom_img"><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl?>/images/custom-img3.jpg" alt="custom img3"></a></div>
                  <p>Описание мега супер производителя</p>
                  <button class="learn_more_btn" title="В корзину" type="button"><span>Читать</span></button>
                </div>
                <div class="grid12-5">
                  <div class="custom_img"><a href="#"><img src="<?php echo Yii::app()->theme->baseUrl?>/images/custom-img4.jpg" alt="custom img4"></a></div>
                  <p>Описание мега супер производителя</p>
                  <button class="learn_more_btn" title="В корзину" type="button"><span>Читать</span></button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>