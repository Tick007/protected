<?
return array(
    'title'=>'����������, �������������',
 
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
			'value'=>'������ �����',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
    
		'<hr />',
	
	    'rememberMe'=>array(
            'type'=>'checkbox',
        ),
		
		'myselect'=>array (
			'type'=>'dropdownlist',
			//'items'=>array('1'=>'USA','2'=>'Germany'),
		),
		
    ),
	
	
 
    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'����',
        ),
    ),
)
?>