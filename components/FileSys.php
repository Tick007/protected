
<?
class FileSys extends CWidget {//////////////////Виджет для рисования дерева папок

	var $targetform;
	var $targetitem;
	var $basedir;
	var $yfm_path;///////////////путь контроллер/актион для pfgecrf файлового менеджера
	
	public function __construct($targetform, $targetitem){/////////
	
				$this->targetform = $targetform;
				$this->targetitem = $targetitem;
				//$connection =  Yii::app()->db;
				//$criteria=new CDbCriteria;
				//$criteria->order = 't.group_name';
				//$criteria->condition = " t.parent = 0";
				//$models= Contr_agents_groups::model()->with('child_categories')->findAll($criteria);
				$this->basedir = $_SERVER['DOCUMENT_ROOT'].'/themes/'.Yii::app()->theme->name;
				$this->yfm_path="/adminmedia/yfb/";
				
				$dir = $this->basedir;
				if ( $dir [ strlen( $dir ) - 1 ] != '/' )
				{
					$dir .= '/'; //добавляем слеш в конец если его нет
				}
				$nDir = opendir ($dir);
				while ( false !== ( $file = readdir( $nDir ) ) )
				{
					if ( $file != "." AND $file != ".." )
					{
						if ( is_dir( $dir . $file ) )
						{ 
							
							//если это не директория
							$files [] = $file;
						}
					}
				}
				sort ($files);
				for ($i=0; $i<count($files); $i++) {
				//echo $files[$i].'<br>';
					$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
							//	'text'=>CHtml::link($models[$i]->group_name, array('/nomenklatura/contragents/'.$models[$i]->group_id.'?targetform='.$targetform.'&targetitem='.$targetitem)),
							'text'=>CHtml::link($files[$i], array($this->yfm_path, 'folder'=>$dir.$files[$i])),
								'id'=>1,
							 'children'=>$this->print_models($dir.$files[$i]),
								);			

				}/////////for ($i=0; $i<count($models); $i++) {
				
				
				

			/*	
			$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link('qqqq', array('/products/details/','cat'=>5)),
								'id'=>1,
							//	'children'=>$this->find_models($this->brand_ids[$i]),
								);			
				*/
				
		
			$this->widget(
			'CTreeView',
			array(
			//'url' => array('ajaxFillTree'),//////////////////При использовании ажакса не запоминает открытые узлы
			'data'=>$treee, // передаем массив
   		    'animated'=>'fast', // скорость анимации свертывания/развертывания
   		     'collapsed'=>true, // если тру, то при генерации дерева, все его узлы будут свернуты
			 'cookieId'=>'yfb',
    		  'persist'=>'cookie',
			   'unique'=>true)
			);
			
	}//////////////public function __construct(){
		
	private function print_models($dir) {
				//echo 'пришло = '.$dir.'|<br>';
				
				//$dir=$this->basedir.'/'.$dir;
				if ( $dir [ strlen( $dir ) - 1 ] != '/' )
				{
					$dir .= '/'; //добавляем слеш в конец если его нет
				}
				$nDir = opendir ($dir);
				while ( false !== ( $file = readdir( $nDir ) ) )
				{
					if ( $file != "." AND $file != ".." )
					{
						//echo $file;
						if ( is_dir( $dir . $file ) )
						{ 
							//если это не директория
							$files [] = $file;
						}
					}
				}
				if (isset($files) AND count($files)>0) {
						sort ($files);
				        for ($i=0; $i<count($files); $i++) {
						//echo $dir.$files[$i].'<br>';
			    		$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($files[$i], array($this->yfm_path, 'folder'=>$dir.$files[$i])),
								'id'=>1,
							'children'=>$this->extract_childs($dir.$files[$i]),
								);			

						}/////////for ($i=0; $i<count($files); $i++) {
				}/////////////if (count($files)>0) {
				if(isset($treee))return $treee;
				
	}///////////private function print_models($models) {	
		
	private function extract_childs ($dir) {
	
				if ( $dir [ strlen( $dir ) - 1 ] != '/' )
				{
					$dir .= '/'; //добавляем слеш в конец если его нет
				}
				$nDir = opendir ($dir);
				while ( false !== ( $file = readdir( $nDir ) ) )
				{
					if ( $file != "." AND $file != ".." )
					{
						if ( is_dir( $dir . $file ) )
						{ 
							//если это не директория
							$files [] = $file;
						}
					}
				}
				if (isset($files) AND count($files)>0) {
						sort ($files);
				        for ($i=0; $i<count($files); $i++) {
			    		$treee[]=array(
							//'text'=>"<a href=\"?r=products/details&cat=".$this->brand_ids[$i]."\">".$this->brand_names[$i].'</a>',
								'text'=>CHtml::link($files[$i], array($this->yfm_path, 'folder'=>$dir.$files[$i])),
								'id'=>1,
							'children'=>$this->print_models($dir.$files[$i]),
								);			

						}/////////for ($i=0; $i<count($files); $i++) {
				}/////////////if (count($files)>0) {
				if(isset($treee) ) return $treee;
	}
		
}////////////////class Tree extends CWidget {
?>