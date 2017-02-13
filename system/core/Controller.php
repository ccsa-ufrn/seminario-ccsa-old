<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	private static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');

		$this->load->initialize();
		
		log_message('debug', "Controller Class Initialized");
	}

	public static function &get_instance()
	{
		return self::$instance;
	}
}
// END Controller class

class Base extends CI_Controller 
{
    
    /*
     * Function : __construct()
     * Description : This function run ever a request is done
    */
    public function __construct()
    {
        
        parent::__construct();
        
        /*
        * Verifying if the system is already installed
        */
        if ( !self::_isInstalled() ) :
        
            redirect(
                base_url('install')
            );
            
        endif; 
        
    }
    
    
    /*
     * Function : _isInstalled()
     * Description : Verify if the system is installed already
    */
    private function _isInstalled()
    {
            
        /* 
         * Verifying if install.json exists
        */ 
        $exists = is_file(FCPATH.APPPATH."install.json");
        return $exists;

    }
    
    
    /*
     * Function : _isLogged()
     * Description : Checks if the user is logged
    */
    public function _isLogged()
    {
        
        /* 
         * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'session'
            ) 
        );
        

        /* 
         * User is logged?
        */
        if( $this->session->userdata('user_logged_in')==null )
            return false;
            
        return true;
    
    }
    
    
    /*
     * Array with the system capabilities
    */    
    private $capabilities = array(
        
        'tg_view' => array(
            'administrator'
        ),
        
        'tg_create' => array(
            'administrator'
        ),
        
        'tg_update' => array(
            'administrator'
        )
        
    );
    
    /*
     * Function : _hasCapabilities( $user , $action )
     * Description : Check if user has capabilities to do an $action 
     *
     * $user : User [RedBean Object]
     * $action : Name of the action [String]
     *           Possible actions: 'tg_view'
     * return TRUE, if the user has capability. FALSE, otherwise.
    */
    public function  _hasCapabilities( $user , $action )
    {

        if ( array_key_exists( $action , $this->capabilities ) ) : 

            if ( in_array( $user->type , $this->capabilities[$action] ) ) : 
            
                return true;
            
            else :
             
                return false;
                
            endif;
        
        endif; 
        
        return false;
    
    }
    
    
    
}

/* End of file Controller.php */
/* Location: ./system/core/Controller.php */