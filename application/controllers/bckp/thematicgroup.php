<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ThematicGroup extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }

    public function index($order = 'noset', $progs = 'ASC'){

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
        
        
        /* =================================================
            BEGIN - ORDERING ELEMENTS
        ================================================== */ 
        if($order=='area'){
            if($progs=='DESC')
                $sql = 'SELECT tg.* FROM thematicgroup AS tg JOIN area AS a ON tg.area_id=a.id ORDER BY a.name DESC';
            else
                $sql = 'SELECT tg.* FROM thematicgroup AS tg JOIN area AS a ON tg.area_id=a.id ORDER BY a.name ASC';
            $rows = R::getAll($sql);
            $thematicgroups = R::convertToBeans( 'thematicgroup', $rows );
        }else if($order=='name'){
            if($progs=='DESC')
                $thematicgroups = R::find('thematicgroup','ORDER BY name DESC');
            else
                $thematicgroups = R::find('thematicgroup','ORDER BY name ASC');
        }else{
            $thematicgroups = R::find('thematicgroup');
        }
        /* =================================================
            END - ORDERING ELEMENTS
        ================================================== */
            
        $areas = R::find('area','ORDER BY name ASC');

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'thematicgroups' => $thematicgroups,
                    'areas' => $areas,
                    'order' => array($order,$progs)
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/thematicgroup/manage',$data);
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
        
        $tg = R::findOne('thematicgroup','id=?',array($id));
            
        $areas = R::find('area','ORDER BY name ASC');

        $data = array(
                    'tg' => $tg,
                    'areas' => $areas
                );

        $this->load->view('dashboard/thematicgroup/retrieveDetails',$data);

	}

    public function create(){

        $this->load->library( array('rb', 'form_validation', 'session') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            ),
            array(
                'field' => 'area',
                'label' => 'Área',
                'rules' => 'required'
            ),
            array(
                'field' => 'syllabus',
                'label' => 'Ementa',
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
                            'name' => form_error('name'),
                            'area' => form_error('area'),
                            'syllabus' => form_error('syllabus')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'name' => set_value('name'),
                            'area' => set_value('area'),
                            'syllabus' => set_value('syllabus')
                        )
                );
            
            redirect(base_url('dashboard/thematicgroup'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $name = $this->input->post('name');
        $area = $this->input->post('area');
        $syllabus = $this->input->post('syllabus');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        $a = R::findOne('area','id=?', array($area));

        $tg = R::dispense("thematicgroup");
        $tg['name'] = $name;
        $tg['syllabus'] = $syllabus;
        
        $a->ownThematicgroupList[] = $tg;
        R::store($a);
        
        $this->session->set_flashdata('success','O grupo temático foi adicionado com sucesso.');
        redirect(base_url('dashboard/thematicgroup'));
        exit;

    }

    public function update(){

        $this->load->library( array('rb', 'form_validation', 'session') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            ),
            array(
                'field' => 'area',
                'label' => 'Área',
                'rules' => 'required'
            ),
            array(
                'field' => 'syllabus',
                'label' => 'Ementa',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
             $this->session->set_flashdata('error','Você precisa preencher todos os campos obrigatórios para poder atualizar um grupo temático. Repita a operação.');
            
            redirect(base_url('dashboard/thematicgroup'));
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
        $syllabus = $this->input->post('syllabus');
        $area = $this->input->post('area');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $area = R::findOne('area','id=?', array($area) );
        $tg = R::findOne('thematicgroup','id=?', array($id) );
        $tg->area = $area;
        $tg->name = $name;
        $tg->syllabus = $syllabus;
        R::store($tg);

        $this->session->set_flashdata('success','O grupo temático foi <b>atualizado</b> com sucesso.');
        redirect(base_url('dashboard/thematicgroup'));
        exit;

    }
    
    public function delete(){

        $this->load->library( array('rb', 'session', 'form_validation') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        $tg = R::findOne('thematicgroup','id=?', array($id) );
        R::trash($tg);

        $this->session->set_flashdata('success','O grupo temático foi <b>removido</b> com sucesso.');
        redirect(base_url('dashboard/thematicgroup'));
        exit;

    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */