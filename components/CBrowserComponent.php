<?php
	Yii::import('ext.browser.ABrowser');
	class CBrowserComponent extends CApplicationComponent
	{
		private $_myBrowser;
		public function init() {}
		public function __construct()
		{
			$this->_myBrowser = new Browser2();
		}

		/**
		* Call a Browser function
		*
		* @return string
		*/
		public function __call($method, $params)
		{
			if (is_object($this->_myBrowser) && get_class($this->_myBrowser)==='ABrowser') return call_user_func_array(array($this->_myBrowser, $method), $params);
			else throw new CException(Yii::t('Browser', 'Can not call a method of a non existent object'));
		}
	}
?>