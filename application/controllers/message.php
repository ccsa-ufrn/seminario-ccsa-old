<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Message extends CI_Controller {

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

    public function sendView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error')
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/message/send',$data);
        $this->load->view('dashboard/footer');

    }

    public function doSend(){
        
        $this->load->library( array('session','rb','email','form_validation', 'gomail') );
        $this->load->helper( array('url','form','typography') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'filter',
                'label' => 'Filtro',
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
            $this->session->set_flashdata('error','Você não preencheu os dados corretamente, repita a operação.');
            redirect(base_url('dashboard/message/send'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $filter = $this->input->post('filter');
        $message = $this->input->post('message');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */

        $list = array();

        if($filter=='insnopay'){

            $qry = R::find('user',' paid = "no" AND ( type = "student" OR type = "noacademic" OR type = "instructor" ) ');

            foreach ($qry as $item) {
                
                $list[] = $item->email;
                
            }

        }

        $this->email->from($user->email, 'Seminário de Pesquisa do CCSA');
        $this->email->bcc($list);
        //$this->email->to($list); 

        $this->email->subject("[Mensagem da Administração] Seminário de Pesquisa do CCSA");

        $msg = "<h1 style='font-weight:bold;'>Mensagem da Administração</h1>";
        $msg .= auto_typography($message);
        
        $this->email->message(emailMsg($msg));
        
        try {
            $status = $this->email->send();
        } catch (Exception $e) {
            
        }

        if(!$status){
            echo '<html><head><meta charset="utf-8">
</head><body><p>O servidor de emails, neste momento, parece estar fora do ar. Tente novamente mais tarde.</p></body></html>';
            exit;
        }

        $this->session->set_flashdata('success','A mensagem foi enviada com sucesso.');
        redirect(base_url('dashboard/message/send'));
        exit;

    }
    
    public function index($filter = 'all'){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        if($filter=='answered')
            $msgs = R::find('message','answered = "yes" order by created_at DESC');
        else if($filter=='noanswered')
            $msgs = R::find('message','answered = "no" order by created_at DESC');
        else
            $msgs = R::find('message','order by created_at DESC');
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'msgs' => $msgs
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/message/list',$data);
        $this->load->view('dashboard/footer');
        
    }
    
    public function retrieveDetailsView(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );
        
        $id = $this->input->get('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        $msg = R::findOne('message','id=?',array($id));

        $data = array(
                    'msg' => $msg,
                    'user' => $user
                );

        $this->load->view('dashboard/message/retrievedetails',$data);
        
    }
    
    public function reply(){
        
        $this->load->library( array('session','rb','email','form_validation', 'gomail') );
        $this->load->helper( array('url','form','typography') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }

        //echo 'hello';
        //exit;
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'to',
                'label' => 'Para',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => 'Assunto',
                'rules' => 'required'
            ),
            array(
                'field' => 'reply',
                'label' => 'Mensagem',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você não preencheu os dados corretamente, repita a operação.');
            redirect(base_url('dashboard/message'));
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
        
        $to = $this->input->post('to');
        $subject = $this->input->post('subject');
        $message = $this->input->post('reply');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        /*$this->email->from($user->email, 'Seminário de Pesquisa do CCSA');
        $this->email->to($to); 

        $this->email->subject($subject);

        $msg = "<h1 style='font-weight:bold;'>Reposta</h1>";
        $msg .= "<p style='color:red;'> Caso você responda este email e ocorra uma demora no retorno, envie novamente a mensagem com o histório da conversa pela página <a href='".base_url('contact')."'>contato</a> do sistema do seminário.</p>";
        $msg .= auto_typography($message);
        
        $this->email->message(emailMsg($msg));*/

        $msg = "<h1 style='font-weight:bold;'>Reposta</h1>";
        $msg .= "<p style='color:red;'> Caso você responda este email e ocorra uma demora no retorno, envie novamente a mensagem com o histório da conversa pela página <a href='".base_url('contact')."'>contato</a> do sistema do seminário.</p>";
        $msg .= auto_typography($message);
        
        try {
            $status = $this->gomail->send_email($user->email, 'Seminário de Pesquisa do CCSA', $to, $subject, emailMsg($msg));
        } catch (Exception $e) {
            
        }

        if(!$status){
            echo '<html><head><meta charset="utf-8">
</head><body><p>O servidor de emails, neste momento, parece estar fora do ar. Tente novamente mais tarde.</p></body></html>';
            exit;
        }
        
        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        $msga = R::findOne('message','id=?',array($id));        
        $msga['answered'] = 'yes';
        R::store($msga);

        $this->session->set_flashdata('success','A mensagem foi enviada com sucesso.');
        redirect(base_url('dashboard/message'));
        exit;
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */