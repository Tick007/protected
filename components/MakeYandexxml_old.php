<?php

class MakeYandexxml extends CWidget
{

	public $products;
	public $groups;
	public $site;
	public $docroot;
	public $categories;
	public $pictures_list;
	public $params;
	public $not_allowed_directories_market;
	

	public function __construct($site=NULL, $docroot=NULL)
	{
				
				if($site!=NULL) $this->site = $site;
				if($docroot!=NULL) $this->docroot = $docroot;
				
				//echo 'start';
				
				$not_allowed_directories="74,186,187,188,189,190,485,486,487,488,489,490,179,180,181,182,183,184,185,362,363,364,365,366,367,368,369,370,371,372,373,374,375,376,377,378,379,380,381,382,383,384,191,192,193,194,195,196,197,385,386,387,388,389,390,391,144,145,146,147,148,149,150,151,152,153,356,357,358,359,360,361,392,296,396,397,398,399,400,401,402,403,404,405,406,407,408,409,410,411,412,413,414,415,416,417,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,505,2024,2022,2027,2030,2079,2025,2031,2032,2026,2023,2029,2028,208,209,210,211,212,213,214,215,216,217,218,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,154,155,156,157,158,159,160,161,162,163,164,339,340,341,342,343,344,345,346,347,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,418,419,420,421,422,423,424,425,426,427,428,429,430,255,256,354,355,478,479,480,481,482,483,484,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,257,258,497,498,499,500,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,431,432,433,434,435,436,437,438,439,440,441,442,443,444,445,446,447,448,449,312,313,314,319,320,321,322,323,324,315,316,317,318,494,495,496,325,326,327,328,329,330,331,332,333,334,335,336,337,338,472,473,474,475,476,477,501,502,503, 2277,2276,2266,2260,2256,2251,2251,2278,2273,2279,2261,2263,2257,2249,2252,2250,2255,2248,2254,2271,2258,2270,2268,2269,2272,2262,2275,2274,2264,2259,2267,2265,2253,  167, 166, 168, 169, 170, 171, 172, 173, 174, 175,  177, 176, 178, 199, 200, 201, 202, 203, 204, 205, 206, 207, 493, 2492, 2491, 2490, 2489, 2488, 2479, 2471, 2470, 2468, 2458, 2279, 2278, 2277, 2270, 2275, 2269, 2266, 2263, 2256, 2252, 2153, 2151, 2146, 2142, 2122, 2097, 2093, 2091, 2081, 2080, 2078, 2077, 2072, 2062, 2048, 2047, 2046, 2045, 2044, 2043, 2041, 2040, 2038, 2036, 2035, 2034, 2019, 2018, 2014, 2013, 2012, 1995, 1995, 1981, 1980, 1953, 1943, 1929, 1923, 1920, 1917, 1916, 1915, 1911, 1889, 1847, 1763, 1686, 1573, 1557, 1285, 1263, 1260, 1259, 1246, 1245, 1239, 1236, 1232, 1231, 1230, 1229, 998, 997, 989, 973, 969, 768, 761, 753, 744, 742, 741, 727, 726, 714, 709, 706, 705, 704, 703, 700, 698, 695, 693, 563, 547, 542, 537, 535, 534, 503, 484, 479, 471, 470, 469, 466, 465, 464, 463, 462, 461, 460, 459, 458, 457, 456, 455, 454, 453, 451, 445, 444, 440, 438, 437, 436, 435, 433, 432, 430, 429, 428, 427, 426, 425, 424, 423, 422, 421, 420, 419, 417, 415, 414, 413, 412, 411, 410, 409, 408, 406, 405, 400, 399, 398, 397, 388, 387, 386, 383, 359, 353, 350, 349, 346, 145, 146, 147, 148, 149, 150, 151, 153, 169, 222, 226, 264, 300, 341, 342, 344, 346  ";
		
		$this->not_allowed_directories_market= array(2483, 1926, 548);
		
		
				$criteria=new CDbCriteria;
		$criteria->condition = " picture_product.is_main=1";
		$picture_models = Pictures::model()->with('picture_product')->findAll($criteria);
		if (isset($picture_models)) {
			for($i=0; $i<count($picture_models);$i++) $this->pictures_list[$picture_models[$i]->picture_product[0]->product]=array('pict_id'=>$picture_models[$i]->id, 'ext'=>$picture_models[$i]->ext);
			unset($picture_models);
		}
		
		/*
		$criteria=new CDbCriteria;
		$params = Characteristics::model()->findAll($criteria);
		if(isset($params)) $this->params = CHtml::listdata($params, 'caract_id', 'caract_name');
		*/
		
		
		$criteria=new CDbCriteria;
		//$criteria->select=array( 't.*',  'picture_product.picture AS icon' , 'picture_product.ext AS ext');
		$criteria->condition = " t.product_visible = 1 AND  product_price>300 AND number_in_store>0";
		$criteria->addCondition("t.category_belong NOT IN (".$not_allowed_directories.")");
	/*	$criteria->join ="
			LEFT JOIN ( SELECT product, picture, pictures.ext as ext FROM picture_product  JOIN pictures ON pictures.id= picture_product.picture  WHERE is_main=1 ) picture_product ON picture_product.product = t.id  "; 
			*/
		$this->products=Products::model()->findAll($criteria);
		

		
		
		$criteria=new CDbCriteria;
		$criteria->condition=" t.alias  IS NOT NULL AND t.alias <>'' AND t.path IS NOT NULL AND TRIM(t.path) <>'' AND t.show_category = 1  AND t.category_name<>'' ";
		$criteria->addCondition("t.category_id NOT IN (".$not_allowed_directories.")");
		$criteria->order="t.category_id";
		$this->categories = Categories::model()->findAll($criteria);
		$this->groups =CHtml::listData($this->categories,'category_id','alias');
		
		
		// $uri=Yii::app()->request->url;
		//echo $uri;
		//print_r($_GET);
		//exit();	

		//$params = array('products'=>@$products,  'groups'=>@$groups, 'categories'=>@$categories);
		//if(isset($pictures_list)) $params['pictures_list'] = $pictures_list;
		

		//if($uri == '/pricelist/mail.xml') $this->renderPartial('yandex/torgmail', array('products'=>@$products,  'groups'=>@$groups, 'categories'=>@$categories, 'pictures_list'=>$pictures_list) );
		//else $this->renderPartial('yandex/yml1',  array('products'=>@$products,  'groups'=>@$groups, 'categories'=>@$categories, 'pictures_list'=>$pictures_list));
	//	else
	
}/////////////public function __construct($site, $docroot)

public function makefile() {
	

	
$fp = fopen($this->docroot.'/pictures/files/yandex_market.xml', 'w');
$fp_vm = fopen($this->docroot.'/pictures/files/yandex_market_vm.xml', 'w'); //////////////Отдельный файл вендер - модел

$fname1 = $this->docroot.'/pictures/files/yandex_market.xml';
$fname2 = $this->docroot.'/yandex_market.xml'; //////////////////////для копирование в старое местоположение


//header("Content-type: text/xml");
fwrite($fp,  "<?xml version=\"1.0\"  encoding=\"UTF-8\"?>\r\n");




if (isset($this->products) AND $this->groups) {
	
	ob_start();
	?>
<yml_catalog date="<?php echo date('Y-m-d H:i')?>">
<shop>
<name><?php echo $this->site?></name>
<company><?php echo $this->site?></company>
<url>http://<?php echo $this->site?></url>
<currencies>
<currency id="RUR" rate="1"/>
</currencies>
<categories>
	<?php
	$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp,  $buf);
	fwrite($fp_vm,  $buf);
	$buf='';


	
	for ($k=0; $k<count($this->categories); $k++) {
		if($this->categories[$k]->category_id == $this->categories[$k]->parent) {
			echo 'Ошибка с группой '.$this->categories[$k]->category_id;
			exit();
		}
		if($this->categories[$k]->parent==Yii::app()->params['main_tree_root']) $buf= '<category id="'.$this->categories[$k]->category_id.'">'.htmlspecialchars($this->categories[$k]->category_name).'</category>';
		elseif(trim($this->categories[$k]->category_name)!='') $buf= '<category id="'.$this->categories[$k]->category_id.'" parentId="'.$this->categories[$k]->parent.'">'.htmlspecialchars($this->categories[$k]->category_name).'</category>';
		fwrite($fp,  $buf);
		fwrite($fp_vm,  $buf);
	}
	ob_start();
	?>
</categories>	
<offers>
<?php
	$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp,  $buf);
	fwrite($fp_vm,  $buf);
	$buf='';
	

$proceed	 = 0;
for ($i=0; $i<count($this->products); $i++) { 

if(isset($this->groups[$this->products[$i]->category_belong])) {

$proceed++;

ob_start();
?>
  <?php
  $vender_model = 0;
  $add_params = '';
    if(isset(Yii::app()->params['market_params']))  {
			$params = Products::model()->get_product_params($this->products[$i]->id);
			if($params['vender_model']==true) {
				$vender_model = 1;
				$add_params = $params['attr'];
			}
	}
	?>
<offer id="<?php echo $this->products[$i]->id?>" <?php
if($vender_model==1) echo ' type="vendor.model" ';
?> available="true">

	<url><?php

    //$url='http://'.$site.urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path), 'pd'=>$products[$i]->id ) ) );
	$url='http://'.$this->site.urldecode( '/catalog/'.FHtml::urlpath($this->products[$i]->belong_category->path).'/'.$this->products[$i]->belong_category->alias.'/'.$this->products[$i]->id.'.'.'html' ) ;
	echo $url;
	?></url>
    <price><?php echo $this->products[$i]->product_price?></price>
    <currencyId>RUR</currencyId>
    <categoryId><?php echo $this->products[$i]->category_belong?></categoryId>
    <?php
	if(isset($this->pictures_list[$this->products[$i]->id]))   $iconname = "/pictures/add/".$this->pictures_list[$this->products[$i]->id]['pict_id'].'.'.$this->pictures_list[$this->products[$i]->id]['ext'];
	if (file_exists($this->docroot.$iconname)==1 AND is_file($this->docroot.$iconname)) {
			?>
			<picture>
            http://<?php echo $this->site?><?php echo $iconname?>
        </picture>
			<?php
	} 
	?>
    
  <?php
  
    if($vender_model!=1) {?>
  <name><?php echo htmlspecialchars($this->products[$i]->product_name)?></name>
  <?
  }
  elseif($vender_model==1) { ?>
  <name><?php 
 echo htmlspecialchars($this->products[$i]->product_html_keywords); 
  ?></name>
<?php	
}
  
  if(trim($add_params)) echo $add_params;

  ?>
    <description> 
    <?php
    echo  htmlspecialchars($this->products[$i]->product_html_description);
	?>
    </description>

</offer>
<?php

$buf = ob_get_contents();
	ob_end_clean();
	if($vender_model==1) fwrite($fp_vm,  $buf);
	else 	fwrite($fp,  $buf);
	$buf='';
}////////if(isset($this->groups[$this->products[$i]->category_belong])) {
 }////////for ($i=0; $i<count($products); $i++) {
	 ob_start();
?>
</offers>
	</shop>
    </yml_catalog>
	<?php
}//////////


$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp,  $buf);
	fwrite($fp_vm,  $buf);
	$buf='';

fclose($fp); //Закрытие файла
fclose($fp_vm); //Закрытие файла

@unlink($fname2);
copy($fname1, $fname2);
				
	echo 'Обработанно: '.count($this->products)." товаров \n";			
	echo 'Выгруженно в маркет: '.$proceed." товаров \n";			
				

	

}/////////public function makefile() {
	
	
}



