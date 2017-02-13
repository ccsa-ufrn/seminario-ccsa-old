<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }

    public function generalView($id){

        $this->load->library( array('rb','session') );
        $this->load->helper( array('url','form') );

        $sbv = R::findOne('subevent','id=?',array($id));

        $data = array(
                    'sbv' => $sbv
                );

        $this->load->view('template/header');
        $this->load->view('subgen',$data);
        $this->load->view('template/footer');

    }

    public function anaisWorksView(){

        $this->load->library( array('rb') );
        $this->load->helper( array('url') );

        $mcs = R::find('minicourse',' cernn="yes" ');
        $wss = R::find('workshop',' cernn="yes" ');
        $cfs = R::find('conference',' cernn="yes" ');
        $rts = R::find('roundtable',' cernn="yes" ');

        $data = array(
                    'mcs' => $mcs,
                    'wss' => $wss,
                    'cfs' => $cfs,
                    'rts' => $rts
                );

        $this->load->view('template/header');
        $this->load->view('anaisworks',$data);
        $this->load->view('template/footer');

    }

    public function activitiesView(){

        $this->load->library( array('rb','session') );
        $this->load->helper( array('url','form') );

        $sbvs = R::find('subevent','ORDER BY title');

        $data = array(
                    'sbvs' => $sbvs
                );

        $this->load->view('template/header');
        $this->load->view('activities',$data);
        $this->load->view('template/footer');

    }

    public function anaisView(){

        $this->load->library( array('rb','session') );
        $this->load->helper( array('url','form') );

        $tgs = R::find('thematicgroup','ORDER BY name');

        $data = array(
                    'tgs' => $tgs
                );

        $this->load->view('template/header');
        $this->load->view('anais',$data);
        $this->load->view('template/footer');

    }

    public function shortFilmsView(){
        $this->load->helper( array('url') );
        
        $this->load->view('template/header');
        $this->load->view('shortfilms');
        $this->load->view('template/footer');
    }

    public function conversationCircleView(){
        $this->load->helper( array('url') );
        
        $this->load->view('template/header');
        $this->load->view('conversationcircle');
        $this->load->view('template/footer');
    }

    public function index(){

        $this->load->library( array('rb','session') );
        $this->load->helper( array('url','text','form') );
        
        $areas = R::find('area','ORDER BY name ASC');
        $newt = R::find('news','ORDER BY created_at DESC LIMIT 4');
        $news = array();
        foreach($newt as $n)
            $news[] = $n;

        $data = array(
            'news' => $news,
            'areas' => $areas
        );
        
        $this->load->view('template/header');
        $this->load->view('index',$data);
        $this->load->view('template/footer');

	}

    public function debatescycle(){

        $this->load->library( array('rb','form_validation','session') );
        $this->load->helper( array('url','form') );
        
        $this->load->view('template/header');
        $this->load->view('debatescycle');
        $this->load->view('template/footer');

    }
    
    public function retrieveDetailGT(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('url') );
        
        $data = array(
                    'gt' => R::findOne('thematicgroup','id=?',array($this->input->get('id')))
                );
        
        $this->load->view('retrievegt',$data);
        
    }
    
    public function retrieveStandard(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('url') );
        
        $id = $this->input->get('id');
        
        if($id==1){
            $this->load->view('standards/papers');
        }else if($id==2){
            $this->load->view('standards/posters');
        }else if($id==3){
            $this->load->view('standards/minicourse');
        }else if($id==4){
            $this->load->view('standards/roundtable');
        }
        
        
        
    }
    
    public function registerView(){

        $this->load->library( array('rb','form_validation','session') );
        $this->load->helper( array('url','form') );

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform')
                );
        
        $this->load->view('template/header');
        $this->load->view('register',$data);
        $this->load->view('template/footer');

	}
    
    public function contactView(){

        $this->load->library( array('rb','form_validation','session') );
        $this->load->helper( array('url','form') );

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform')
                );
        
        $this->load->view('template/header');
        $this->load->view('contact',$data);
        $this->load->view('template/footer');

	}
    
    public function submitMessage(){

		$this->load->library( array('rb', 'form_validation', 'session') );
		$this->load->helper( array('form' , 'url' , 'date' , 'security', 'utility' ) );
        
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
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => 'Assunto',
                'rules' => 'required'
            ),
            array(
                'field' => 'message',
                'label' => 'Mensagem',
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
                            'email' => form_error('email'),
                            'subject' => form_error('subject'),
                            'message' => form_error('message')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'name' => set_value('name'),
                            'email' => set_value('email'),
                            'subject' => set_value('subject'),
                            'message' => set_value('message')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');
            redirect(base_url('contact'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $name = $this->input->post('name');
		$email = $this->input->post('email');
		$subject = $this->input->post('subject');
		$message = $this->input->post('message');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

		$msg = R::dispense("message");
		$msg['name'] = $name;
		$msg['email'] = $email;
		$msg['subject'] = $subject;
		$msg['message'] = $message;
		$msg['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $msg['answered'] =  'no';
		R::store($msg);

		$this->session->set_flashdata('success','Sua mensagem foi enviada com sucesso.');
        redirect(base_url('contact'));
		exit;

	}
    
    public function scheduleView(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('url') );

        
        $this->load->view('template/header');
        $this->load->view('schedule');
        $this->load->view('template/footer');
        
    }
    
    function workshopsView(){

        $this->load->library( array('rb') );
        $this->load->helper( array('url') );
        
        $this->load->view('template/header');
        $this->load->view('workshops');
        $this->load->view('template/footer');

    }

    public function minicoursesView(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('url') );

        $mdsm = R::findOne('minicoursedayshift',' shift = "matutino" ');
        $mdsv = R::findOne('minicoursedayshift',' shift = "vespertino" ');
        $mdsn = R::findOne('minicoursedayshift',' shift = "noturno" ');

        $data = array(
                'mdsm' => $mdsm,
                'mdsv' => $mdsv,
                'mdsn' => $mdsn
            );
        
        $this->load->view('template/header');
        $this->load->view('minicourses',$data);
        $this->load->view('template/footer');
        
    }
    
    public function roundtablesView(){
        
        $this->load->library( array('rb','session') );
        $this->load->helper( array('url','form') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        $cdss = R::find('roundtabledayshift','ORDER BY date ASC');
        $cs = R::find('roundtable');

        $data = array( 
            'cdss' => $cdss,
            'cs' => $cs,
            'user' => $user
        );
        
        $this->load->view('template/header');
        $this->load->view('roundtables',$data);
        $this->load->view('template/footer');
        
    }

    public function conferencesView(){
        
        $this->load->library( array('rb','session') );
        $this->load->helper( array('url','form') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        $cdss = R::find('conferencedayshift','ORDER BY date ASC');
        $cs = R::find('conference');

        $data = array( 
            'cdss' => $cdss,
            'cs' => $cs,
            'user' => $user
        );
        
        $this->load->view('template/header');
        $this->load->view('conferences',$data);
        $this->load->view('template/footer');
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */