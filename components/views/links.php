<?php
//echo 'qqq';

     if (!defined('_SAPE_USER')){
        define('_SAPE_USER', 'b08e7449c7fba1182de7d253c8188832');
     }
     require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
     $sape = new SAPE_client();
?>

<?php
define('_SAPE_USER', 'b08e7449c7fba1182de7d253c8188832');
require_once($_SERVER['DOCUMENT_ROOT'].'/'._SAPE_USER.'/sape.php');
//Добавьте эти строки для вывода строки <!--check code-->
$o[ 'force_show_code' ] = true;
$o['multi_site'] = true; 
$o['charset'] = 'utf-8'; 
$sape = new SAPE_client( $o );
echo $sape->return_links(2); 
echo $sape->return_links(); 
?>

