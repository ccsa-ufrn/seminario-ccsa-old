<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rb{
        
    function __construct(){
        
        // Include database configuration
        include_once(APPPATH.'config/database.php');
        // Include RedBean
        include_once(APPPATH.'/third_party/rb/rb.php');
        // Database information
        $host = $db[$active_group]['hostname'];
        $user = $db[$active_group]['username'];
		$pass = $db[$active_group]['password'];
		$db = $db[$active_group]['database'];
        //Setup DB connection
        R::setup("mysql:host=$host;dbname=$db", $user, $pass);
        
        
    }
    
}

?>