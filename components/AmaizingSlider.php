<?
class AmaizingSlider  extends CWidget{
//private $prod_char_id = 175;
function __construct(){
	//echo 'construct';		
}



public function draw($viewname, $folder) {
		//$banners=array('3_1_big.png'=>'1', '3_2_big.png'=>'2', '3_3_big.png'=>'3');
		$this->render(Yii::app()->theme->name.'/'.$viewname, array('folder'=>$folder));
		
}///////////////public function Draw() {


public function drawGallery($viewname, $icon_image='', $rel='', $folder, $fancyboxclass){
	$banners = FHtml::get_files($_SERVER['DOCUMENT_ROOT'].$folder, 1);
	if(isset($banners) AND empty($banners)==false) {
		$c = count($banners);
		$banners_clear=null;
		for($i=0; $i<$c; $i++){
			$file=$_SERVER['DOCUMENT_ROOT'].$folder.$banners[$i];
			if(is_file($file) && file_exists($file)) {
		  $banners_clear[]= $banners[$i];
			}
		}
		if(empty($banners_clear)==false)$this->render(Yii::app()->theme->name.'/'.$viewname, 
			array('folder'=>$folder, 'icon_image'=>$icon_image, 'rel'=>$rel,
					'banners_clear'=>$banners_clear,
					'fancyboxclass'=>$fancyboxclass));
	}
}

public function drawGalleryFolders($viewname, $icon_image='', $rel='', $folder, $fancyboxclass){
	$banners = FHtml::get_files($_SERVER['DOCUMENT_ROOT'].$folder, 1);
	if(isset($banners) AND empty($banners)==false) {
		
		//print_r($banners);
		foreach ($banners as $banner_folder){
		
			$banners_f = FHtml::get_files($_SERVER['DOCUMENT_ROOT'].$folder.$banner_folder.'/', 1);
			
			//print_r($banners_f);
			
			$c = count($banners_f);
			$banners_clear[$banner_folder]=null;
			for($i=0; $i<$c; $i++){
				$file=$_SERVER['DOCUMENT_ROOT'].$folder.$banner_folder.'/'.$banners_f[$i];
				//echo $file.'<br>';
				if(is_file($file) && file_exists($file)) {
			  $banners_clear[$banner_folder][]= $banners_f[$i];
				}
			}
		}
		

			if(empty($banners_clear)==false)$this->render(Yii::app()->theme->name.'/'.$viewname,
					array('folder'=>$folder, 'icon_image'=>$icon_image, 'rel'=>$rel,
							'banners_clear'=>$banners_clear,
							'fancyboxclass'=>$fancyboxclass));
					
	
	}
}


}///////////class Vitrina {
?>


