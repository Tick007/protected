<?php
//echo $this->region;
if (isset($this->region)) {
$sitemapkladr_rec = Ma_kladr::model()->findByPk($this->region);
if (isset($sitemapkladr_rec)) $sitemapkladr = FHtml::translit($sitemapkladr_rec->name);
}

//exit();

header("Content-type: text/xml");
echo "<?xml version=\"1.0\"  encoding=\"UTF-8\"?>
<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
 xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
 xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";

if (isset($products) AND $groups) {
		
			//print_r($kladr);
		
		for ($i=0; $i<count($products); $i++) {
			if (isset($kladr[$products[$i]->kladr_belongs]) AND isset($groups[$products[$i]->category_belong]) ) {	
					$url = "<url>
					<loc>";
		/*			if (isset(Yii::app()->params['regional_links']) AND Yii::app()->params['regional_links']==true) $url.="http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('/site/product', array('kladr'=>FHtml::translit($kladr[$products[$i]->kladr_belongs]), 'alias'=>$groups[$products[$i]->category_belong], 'id'=>$products[$i]->id));
					else  $url.="http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('/site/product', array('alias'=>$groups[$products[$i]->category_belong], 'id'=>$products[$i]->id));
					*/
					if (isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url.="http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('/site/product', array('service'=>preg_replace('/[^a-z0-9_-]/','',strtolower( str_replace(" ", "-",trim(FHtml::translit($products[$i]->product_name))))), 'id'=>$products[$i]->id));
					else  $url.="http://".$_SERVER['HTTP_HOST'].Yii::app()->createUrl('/site/product', array('alias'=>$groups[$products[$i]->category_belong], 'id'=>$products[$i]->id));
					$url.="</loc>
					<lastmod>".$products[$i]->modified;
					$url.="</lastmod>
					<changefreq>always</changefreq>
					<priority>1.0</priority>
					</url>\r\n";
				
					echo $url;
			}////if (isset($kladr[$
		}///for ($i=0; $i<count($products); $i++) {
		
		
		//foreach($groups as $gr_id=>$gr_alias) {
		for ($k=0; $k<count($categories); $k++) {
				$gr_id = $categories[$k]->category_id;
				$gr_alias = $categories[$k]->category_name; 
				$url = "<url>
					<loc>";
					$url.="http://";
					if (isset($sitemapkladr) 	AND isset(Yii::app()->params['regional_links']) AND Yii::app()->params['regional_links']==true){
							if (isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true) {
								if($categories[$k]->path!=NULL){
										$path = FHtml::urlpath($categories[$k]->path) ;
										if (trim($path)!='') $path_array=array('kladr'=>FHtml::translit($sitemapkladr_rec->name), 'path'=>$path, 'alias'=>$categories[$k]->alias);
										else $path_array=array('kladr'=>FHtml::translit($sitemapkladr_rec->name), 'alias'=>$categories[$k]->alias);
								}///////
								else {
									echo 'bad cat = '.$categories[$k]->category_id.' '.$categories[$k]->category_name.'<br> ';
									echo $categories[$k]->path;
									exit();
									}
							
							}
							else $path_array= array('kladr'=>$sitemapkladr, 'alias'=>$gr_alias);
					}
					else $path_array= array('alias'=>$gr_alias);
					
					$urlurl = urldecode($_SERVER['HTTP_HOST'].Yii::app()->createUrl('/catalog/group', array('alias'=>$categories[$k]->alias)));
					
					$url.=$urlurl;
					$url.="</loc>
					<lastmod>".date('Y-m-d');
					$url.="</lastmod>
					<changefreq>always</changefreq>
					<priority>0.8</priority>
					</url>\r\n";
				
					echo $url;
		}///for ($i=0; $i<count($groups); $i++) {
		
		
	
		
		echo "</urlset>";
}//if (isset($products)) {
?>