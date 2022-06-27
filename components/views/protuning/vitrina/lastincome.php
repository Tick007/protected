<?php 
if(isset($products)) {

	 foreach ($products as $id => $product){



		$filename_gif = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$id.'.gif';
		$filename_jpg = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$id.'.jpg';
		$filename_png = $_SERVER['DOCUMENT_ROOT'].'/pictures/img_small/'.$id.'.png';
		$exist_gif = file_exists($filename_gif);
		$exist_jpg = file_exists($filename_jpg);
		$exist_png= file_exists($filename_png);
		if ($exist_gif==false AND $exist_jpg==false AND $exist_png==false) {/////////////Файл не существует, нужно рисовать элемент для закачки
			$picture = "<img border=\"1\" src=\"/images/nophoto_h60.png\" height=\"60\">";
		}//////////Файл не существует, нужно рисовать элемент для закачки
		else {////////////////////Иначе рисуем картинку
			if ($exist_png==true) {
				$filename = $filename_png;
				$filesrc = '/pictures/img_small/'.$id.'.png';
			}
			elseif($exist_jpg==true) {
				$filename = $filename_jpg;
				$filesrc = '/pictures/img_small/'.$id.'.jpg';
			}
			elseif($exist_gif==true) {
				$filename = $filename_gif;
				$filesrc = '/pictures/img_small/'.$id.'.gif';
			}

			//echo "<img src=\"$filesrc\" style=\"max-height:60px\">";
			$picture = "<img src=\"$filesrc\" border=\"1\" style=\"max-height:150px\" alt=\"".str_replace('"', '', $product['name'])."\" />";
		}

		if(trim($product['category_alias'])!='') $tov[]=CHtml::link($picture.'<br/>'.$product['name'], array('product/details', 'pd'=>$id, 'alias'=>$product['category_alias']));
		else $tov[]=CHtml::link($picture.'<br/>'.$product['name'], array('product/details', 'pd'=>$id));
		//echo $income_list[$i]->product_name.'<br/>';
	}
	if(isset($tov)) {
		echo '<ul  class="float_left" style="min-width:330px"><li>';
			
		echo implode('</li><li> ' , $tov);
		echo '</li></ul>';
	}
}

?>