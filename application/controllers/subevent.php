<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subevent extends CI_Controller {

    public function __construct(){
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

    public function retrieveAddActivityForm(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $id =  $this->input->get('id');
        $idSub = $this->input->get('subid');
        $type =  $this->input->get('type');

        if($type=="minicourse"){
            $ac = R::findOne('minicourse','id=?',array($id));
        }else if($type=="conference"){
            $ac = R::findOne('conference','id=?',array($id));
        }else if($type=="workshop"){
            $ac = R::findOne('workshop','id=?',array($id));
        }else if($type=="roundtable"){
            $ac = R::findOne('roundtable','id=?',array($id));
        }

        $data = array(
                'ac' => $ac,
                'type' => $type,
                'subevent' => R::findOne('subevent',' id=?', array($idSub))
            );

        $this->load->view('dashboard/subevent/retrieveaddactivityform',$data);

    }

    public function doExeAddActivity(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $id =  $this->input->get('id');
        $idSub = $this->input->get('subid');
        $type =  $this->input->get('type');


        // PAREI AQUI ================================================

        $this->load->library( array('session', 'rb', 'form_validation') );
        $this->load->helper( array('url', 'form', 'date') );

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */

        $validation = array(
            
            array(
                'field' => 'description',
                'label' => 'Descrição',
                'rules' => 'required'
            )   

        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            
            $this->session->set_flashdata('error','Você não preencheu todos os campos necessários para cotinuar a ação.');
            redirect(base_url('dashboard/subevent/manage'));
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */

        $acid = $this->input->post('acid');
        $subid = $this->input->post('subid');
        $type = $this->input->post('type');
        $description = $this->input->post('description');

        $sub = R::dispense('subeventitem');
        
        if($type=="minicourse")
            $sub->minicourse = R::findOne('minicourse','id=?',array($acid));
        else if($type=="conference")
            $sub->conference = R::findOne('conference','id=?',array($acid));
        else if($type=="workshop")
            $sub->workshop = R::findOne('workshop','id=?',array($acid));
        else if($type=="roundtable")
            $sub->roundtable = R::findOne('roundtable','id=?',array($acid));

        $sub->description = $description;
        $sub->type = $type;

        $s = R::findOne('subevent',' id=? ', array($subid));
        $s->ownProductList[] = $sub;
        R::store($s);

        $this->session->set_flashdata('success','A atividade foi adicionada com sucesso ao subevento.');
        redirect(base_url('dashboard/subevent/manage'));
        exit;

    }

    public function retrieveEditView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $id =  $this->input->get('id');

        $data = array(
                'subevent' => R::findOne('subevent',' id=?', array($id))
            );

        $this->load->view('dashboard/subevent/retrieveedit',$data);

    }

    public function removeSubeventActivity(){

        $this->load->library( array('session', 'rb', 'form_validation') );
        $this->load->helper( array('url', 'form', 'date') );

        $id = $this->input->post('id');

        $sub = R::findOne('subeventitem','id=?',array($id));
        R::trash($sub);

        $this->session->set_flashdata('success','A atividade do subevento foi removido com sucesso.');
        redirect(base_url('dashboard/subevent/manage'));
        exit;

    }

    public function doUpdateActivity(){

        $this->load->library( array('session', 'rb', 'form_validation') );
        $this->load->helper( array('url', 'form', 'date') );

        $id = $this->input->post('id');
        $description = $this->input->post('description');

        $sub = R::findOne('subeventitem','id=?',array($id));
        $sub->description = $description;
        R::store($sub);

        $this->session->set_flashdata('success','A atividade foi atualizada com sucesso.');
        redirect(base_url('dashboard/subevent/manage'));
        exit;

    }

    public function retrieveUpdateActivity(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $id =  $this->input->get('id');

        $data = array(
                'si' => R::findOne('subeventitem',' id=?', array($id))
            );

        $this->load->view('dashboard/subevent/updateactivity',$data);

    }

    public function remove(){

        $this->load->library( array('session', 'rb', 'form_validation') );
        $this->load->helper( array('url', 'form', 'date') );

        $id = $this->input->post('id');

        $sub = R::findOne('subevent','id=?',array($id));
        R::trash($sub);

        $this->session->set_flashdata('success','O subevento foi removido com sucesso.');
        redirect(base_url('dashboard/subevent/manage'));
        exit;

    }

    public function addCustomActivity(){

        $this->load->library( array('session', 'rb', 'form_validation') );
        $this->load->helper( array('url', 'form', 'date') );

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
                'field' => 'description',
                'label' => 'Descrição',
                'rules' => 'required'
            )

        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            
            $this->session->set_flashdata('error','Você não preencheu todos os campos necessários para cotinuar a ação.');
            redirect(base_url('dashboard/subevent/manage'));
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $description = $this->input->post('description');

        $sub = R::dispense('subeventitem');
        $sub->name = $name;
        $sub->description = $description;
        $sub->type = 'custom';

        $s = R::findOne('subevent',' id=? ', array($id));
        $s->ownProductList[] = $sub;
        R::store($s);

        $this->session->set_flashdata('success','A atividade foi adicionada com sucesso ao subevento.');
        redirect(base_url('dashboard/subevent/manage'));
        exit;

    }

    public function manageView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $data = array(
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error'),
                'validation' => $this->session->flashdata('validation'),
                'popform' => $this->session->flashdata('popform'),
                'subs' => R::find('subevent')
            );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/subevent/manage',$data);
        $this->load->view('dashboard/footer');

    }

    public function create(){

        $this->load->library( array('session', 'rb', 'form_validation') );
        $this->load->helper( array('url', 'form', 'date') );

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */

        $validation = array(
            array(
                'field' => 'title',
                'label' => 'Título',
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
                            'title' => form_error('title')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');
            redirect(base_url('dashboard/subevent/manage'));
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */

        $title = $this->input->post('title');

        $sub = R::dispense('subevent');
        $sub->title = $title;
        R::store($sub);

        $this->session->set_flashdata('success','O subevento foi criado com sucesso, agora você pode gerenciá-lo.');
        redirect(base_url('dashboard/subevent/manage'));
        exit;

    }

    public function retrieveAddActivityView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $id =  $this->input->get('id');

        $data = array(
                'subevent' => R::findOne('subevent',' id=?', array($id))
            );

        $this->load->view('dashboard/subevent/retrieveaddactivity',$data);

    }

    public function retrieveActivitiesResults(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $filter = $this->input->get('filter');
        $search = $this->input->get('search');
        $subevent = $this->input->get('subevent');

        $finalsearch = explode(' ',$search);
        $finalsearch = implode('%',$finalsearch);

        $minicoursesResult = R::find('minicourse',' title LIKE ? ', array('%'.$finalsearch.'%'));
        $conferencesResult = R::find('conference',' title LIKE ? ', array('%'.$finalsearch.'%'));
        $workshopsResult = R::find('workshop',' title LIKE ? ', array('%'.$finalsearch.'%'));
        $roundtablesResult = R::find('roundtable',' title LIKE ? ', array('%'.$finalsearch.'%'));

        if($filter=='minicourses'){
            $conferencesResult = array();
            $workshopsResult = array();
            $roundtablesResult = array();
        }else if($filter=='conferences'){
            $minicoursesResult = array();
            $workshopsResult = array();
            $roundtablesResult = array();
        }else if($filter=='workshops'){
            $minicoursesResult = array();
            $conferencesResult = array();
            $roundtablesResult = array();
        }else if($filter=='roundtables'){
            $minicoursesResult = array();
            $conferencesResult = array();
            $workshopsResult = array();
        }else if($filter!='all'){
            $minicoursesResult = array();
            $conferencesResult = array();
            $workshopsResult = array();
            $roundtablesResult = array();
        }

        $data = array(
                'minicoursesResult' => $minicoursesResult,
                'conferencesResult' => $conferencesResult,
                'workshopsResult' => $workshopsResult,
                'roundtablesResult' => $roundtablesResult,
                'subevent' => R::findOne('subevent','id=?',array($subevent))
            );

        $this->load->view('dashboard/subevent/retrieveactivitiesresults',$data);

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */