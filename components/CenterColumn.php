<?
class CenterColumn extends CWidget {
	var $connection;
		var $command;
		var $dataReader;
		var $row;
		public $models;
		
	public function __construct(){
	//$this->show_items();
	}
		
	public function show_items() {//////¬ытаскиваем модули дл€ правой колонки
	$this->connection = Yii::app()->db;
			$chapter = 1;
			$query_inclusion="SELECT theme_files.file, theme_files.name  FROM theme_chapters_files  JOIN theme_files ON theme_chapters_files.file_id = theme_files.id 
			WHERE theme_chapters_files.theme_id = ".Yii::app()->GP->GP_theme."  AND theme_chapters_files.chapter_id = $chapter AND file_enabled = 1 AND location='C' ORDER BY theme_chapters_files.sort ";
			$this->command=$this->connection->createCommand($query_inclusion)	;
			$dataReader=$this->command->query();
			while(($row=$dataReader->read())!==false) {
			$file_to_incl = trim($row['file']);
			Yii::import('components\$file_to_incl');
			$SM = new $file_to_incl;
			echo "<div><div id=\"my_block_head_left\">&nbsp;</div>
			<div id=\"my_block_head_right\">&nbsp;</div>
			<div id=\"my_block_head_midle\">$file_to_incl</div><div id=\"my_block\">";
			if (isset($this->models)) $SM->models=$this->models;
			$SM->Draw();
			echo "erterwtw";
			echo "</div></div><br>";
			//$fname=basename($file_to_incl);
				}
	}///////////////function

}
?>