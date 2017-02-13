<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
    }

	public function manage(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

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

        $areas = R::find('area');

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'areas' => $areas
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
    	$this->load->view('dashboard/area/manage',$data);
        $this->load->view('dashboard/footer');

	}

    public function retrieveDetailsView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'administrator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if($u['type']!=$type)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */ 
        
        $id = $this->input->get('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }
        
        $area = R::findOne('area','id=?',array($id));

        $data = array(
                    'area' => $area
                );

        $this->load->view('dashboard/area/retrieveDetails',$data);

    }

    public function create(){

        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */

        $validation = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata(
                    'validation', 
                    array(
                            'name' => form_error('name')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'name' => set_value('name')
                        )
                );
            
            redirect(base_url('dashboard/area'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $name = $this->input->post('name');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $area = R::dispense("area");
        $area['name'] = $name;
        $id = R::store($area);

        $this->session->set_flashdata('success','A área foi adicionada com sucesso.');
        redirect(base_url('dashboard/area'));
        exit;

    }

    public function update(){

        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */

        $validation = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você precisa preencher todos os campos obrigatórios para poder atualizar a área. Repita a operação.');
            redirect(base_url('dashboard/area'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $id = $this->input->post('id');

        if(!is_numeric($id)){
            echo "The ID has to be numeric. Do not do that! :D";
            exit;
        }

        $name = $this->input->post('name');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $area = R::findOne('area','id=?', array($id) );
        $area['name'] = $name;
        R::store($area);

        $this->session->set_flashdata('success','A área foi <b>atualizada</b> com sucesso.');
        redirect(base_url('dashboard/area'));
        exit;

    }

    public function delete(){

        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $id = $this->input->post('id');

        if(!is_numeric($id)){
            echo "The ID has to be numeric. Do not do that! :D";
            exit;
        }
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $area = R::findOne('area','id=?', array($id) );
        R::trash($area);

        $this->session->set_flashdata('success','A área foi <b>removida</b> com sucesso.');
        redirect(base_url('dashboard/area'));
        exit;

    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */