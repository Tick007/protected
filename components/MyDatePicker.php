<?
//Yii::import('zii.widgets.CPortlet');
 
class MyDatePicker extends CWidget {
	public $conf;
	function __construct() {
	}
	
    public function init()
    {
        parent::init();
		
			$this->widget('zii.widgets.jui.CJuiDatePicker', $this->conf);
    }
 
    protected function renderContent()
    {
        $this->render('mydatepicker');
    }
}
?>