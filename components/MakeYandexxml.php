<?php

class MakeYandexxml extends MakeXml
{

	


public function makefile() {

 //Открывай вывод в XML ядекса на следующие категории:  2043
 //Игорь, привет! поправили категорию 351 открывай вывод в яндекс маркет.
 //Категорию 1278 - выводи в яндекс маркет, отредактировали
 //548 2456 1278 1531 1532 1090 351
 //Открывай 1926 2483
 //еще открывай 2012

$not_allowed_directories="74,186,187,188,189,190,485,486,487,488,489,490,179,180,181,182,183,184,185,362,363,364,365,366,367,368,369,370,371,372,373,374,375,376,377,378,379,380,381,382,383,384,191,192,193,194,195,196,197,385,386,387,388,389,390,391,144,145,146,147,148,149,150,151,152,153,356,357,358,359,360,361,392,296,396,397,398,399,400,401,402,403,404,405,406,407,408,409,410,411,412,413,414,415,416,417,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,505,2024,2022,2027,2030,2079,2025,2031,2032,2026,2023,2029,2028,208,209,210,211,212,213,214,215,216,217,218,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,154,155,156,157,158,159,160,161,162,163,164,339,340,341,342,343,344,345,346,347,450,451,452,453,454,455,456,457,458,459,460,461,462,463,464,465,466,467,468,469,470,471,418,419,420,421,422,423,424,425,426,427,428,429,430,255,256,354,355,478,479,480,481,482,483,484,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,257,258,497,498,499,500,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,431,432,433,434,435,436,437,438,439,440,441,442,443,444,445,446,447,448,449,312,313,314,319,320,321,322,323,324,315,316,317,318,494,495,496,325,326,327,328,329,330,331,332,333,334,335,336,337,338,472,473,474,475,476,477,501,502,503, 2277,2276,2266,2260,2256,2251,2251,2278,2273,2279,2261,2263,2257,2249,2252,2250,2255,2248,2254,2271,2258,2270,2268,2269,2272,2262,2275,2274,2264,2259,2267,2265,2253,  167, 166, 168, 169, 170, 171, 172, 173, 174, 175,  177, 176, 178, 199, 200, 201, 202, 203, 204, 205, 206, 207, 493, 2492, 2491, 2490, 2489, 2488, 2479, 2471, 2470, 2468, 2458, 2279, 2278, 2277, 2270, 2275, 2269, 2266, 2263, 2256, 2252, 2153, 2151, 2146, 2142, 2122, 2097, 2093, 2091, 2081, 2080, 2078, 2077, 2072, 2062, 2048, 2047, 2046, 2045, 2044,  2041, 2040, 2038, 2036, 2035, 2034, 2019, 2018, 2014, 2013, 1995, 1995, 1981, 1980, 1953, 1943, 1929, 1923, 1920, 1917, 1916, 1915, 1911, 1889, 1847, 1763, 1686, 1573, 1557, 1285, 1263, 1260, 1259, 1246, 1245, 1239, 1236, 1232, 1231, 1230, 1229, 998, 997, 989, 973, 969, 768, 761, 753, 744, 742, 741, 727, 726, 714, 709, 706, 705, 704, 703, 700, 698, 695, 693, 563, 547, 542, 537, 535, 534, 503, 484, 479, 471, 470, 469, 466, 465, 464, 463, 462, 461, 460, 459, 458, 457, 456, 455, 454, 453, 451, 445, 444, 440, 438, 437, 436, 435, 433, 432, 430, 429, 428, 427, 426, 425, 424, 423, 422, 421, 420, 419, 417, 415, 414, 413, 412, 411, 410, 409, 408, 406, 405, 400, 399, 398, 397, 388, 387, 386, 383, 359, 353, 350, 349, 346, 145, 146, 147, 148, 149, 150, 151, 153, 169, 222, 226, 264, 300, 341, 342, 344, 346 ";  
	
/////Игорь добавь пожалуйста вывод в XML для яндекса следующие категории 2483 и 2035
	
$allowed_directories="349,350,352,353,1926,2493,2483,2521,2531,2495,351,2144,761,2147,765,707,2520,2456,2096,1997,2525,787,1919,1961,1993,2089,2152,1976,2527,2143,929,932,2070,2071,2069,2067,2068,300,1922,1920,1923,1928,1917,1916,1918,2005,1915,1898,1900,1901,1902,1897,1899,2013,2015,2012,2019,2021,2018,1999,2138,2140,2040,2064,2062,2063,2048,2045,2049,2044,2047,2043,2057,2060,2056,2051,2059,2054,2153,2072,2077,2080,2034,2036,2037,2038,2035,1230,1229,1231,1232,2142,2145,2146,1239,1240,1245,1246,1263,1264,1259,1261,1260,1756,895,898,888,891,890,866,1870,1760,2151,1754,1881,1409,1407,1278,1686,1093,1091,2484,1090,996,990,989, 998, 1846,1844,1845"; 
	
$this->make_query(NULL, $allowed_directories, 5);

	
$fp = fopen($this->docroot.'/pictures/files/yandex_market.xml', 'w');

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
	$buf='';


	
	for ($k=0; $k<count($this->categories); $k++) {
		if($this->categories[$k]->category_id == $this->categories[$k]->parent) {
			echo 'Ошибка с группой '.$this->categories[$k]->category_id;
			exit();
		}
		if($this->categories[$k]->parent==Yii::app()->params['main_tree_root']) $buf= '<category id="'.$this->categories[$k]->category_id.'">'.htmlspecialchars($this->categories[$k]->category_name).'</category>';
		elseif(trim($this->categories[$k]->category_name)!='') $buf= '<category id="'.$this->categories[$k]->category_id.'" parentId="'.$this->categories[$k]->parent.'">'.htmlspecialchars($this->categories[$k]->category_name).'</category>';
		fwrite($fp,  $buf);
	}
	ob_start();
	?>
</categories>	
<offers>
<?php
	$buf = ob_get_contents();
	ob_end_clean();
	fwrite($fp,  $buf);
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
//if($vender_model==1) echo ' type="vendor.model" ';
?> available="true">

	<url><?php

    //$url='http://'.$site.urldecode(Yii::app()->createUrl('product/details' ,array('alias'=>$products[$i]->belong_category->alias, 'path'=>FHtml::urlpath($products[$i]->belong_category->path), 'pd'=>$products[$i]->id ) ) );
	  if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url='http://'.$this->site.urldecode( '/catalog/'.FHtml::urlpath($this->products[$i]->belong_category->path).'/'.$this->products[$i]->belong_category->alias.'/'.$this->products[$i]->id.'.'.'html' ) ;
	  else  $url='http://'.$this->site.urldecode( '/product/'.$this->products[$i]->id.'.'.'html' ) ;
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
    

  
  <name><?php echo htmlspecialchars($this->products[$i]->product_name)?></name>
 
<?php  
  if(trim($add_params) AND $vender_model!=1) echo $add_params;
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
	fwrite($fp,  $buf);
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
	$buf='';

fclose($fp); //Закрытие файла

@unlink($fname2);
copy($fname1, $fname2);
				
	echo 'Selected from DB : '.count($this->products)." products \n";			
		echo 'Saved to pricelist  '.$proceed." products \n";			
				

	

}/////////public function makefile() {
	
	
}



