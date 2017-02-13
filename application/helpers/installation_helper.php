<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    if ( ! function_exists('verifyingInstallationConf()'))
    {
        
        function verifyingInstallationConf(){
            
            // Verifying if install.json exists
            $exists = is_file(FCPATH.APPPATH."install.json");
            return $exists;

        }
        
    }

?>