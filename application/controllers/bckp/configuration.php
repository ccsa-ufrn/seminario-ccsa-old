<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuration extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
        $this->load->library( array('session','rb') );

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'administrator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        if($u['type']!=$type)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */
    }

    public function retrieveEdit(){
     
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

        $id = $this->input->get('id');   

        $config = R::findOne('configuration','id=?',array($id));

        $data = array('config' => $config );

        $this->load->view('dashboard/configuration/retrieveedit',$data);

    }

    public function update(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

        $id = $this->input->post('id'); 
        $value = $this->input->post('value'); 

        $config = R::findOne('configuration','id=?',array($id));

        if($config->type=="date"){
            $val = explode ( '/' , $value );
            $config->value = $val[2]."-".$val[1]."-".$val[0];
        }else if($config->type=="text"){
            $config->value = $value;
        }else if($config->type=="boolean"){
            if(empty($value))
                $config->value = 'false';
            else
                $config->value = 'true';
        }
        
        // If it's a date, check if it's correct
        if( $config->type=="date" && !checkdate($val[1], $val[0], $val[2]) ){
            $this->session->set_flashdata('error','A data era inválida, não pôde ser atualizada.');
            redirect(base_url('dashboard/configuration'));
            exit;
        }

        R::store($config);

        $this->session->set_flashdata('success','O campo foi atualizado com sucesso.');
        redirect(base_url('dashboard/configuration'));
    }
    
    public function index(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $configs = R::find('configuration');
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'configs' => $configs,
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/configuration/configs',$data);
        $this->load->view('dashboard/footer');
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */