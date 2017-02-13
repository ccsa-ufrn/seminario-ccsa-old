<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Issue extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
    }

    public function createView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','text') );

        // User logged in?
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'instructor';
        $type2 = 'coordinator';
        $type3 = 'student';
        $type4 = 'noacademic';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if(!($u['type']==$type || $u['type']==$type2 || $u['type']==$type3 || $u['type']==$type4))
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */ 

        $issues = R::find('issue','user_id=? AND status="open" ', array($user->id));
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'issues' => $issues,
                    'active' => 'issue'
                );

        $this->load->view('dashboard/header');

        if($this->session->userdata('user_type')=='administrator'){
            $this->load->view('dashboard/template/menuAdministrator');
        }else if($this->session->userdata('user_type')=='coordinator'){
            $this->load->view('dashboard/template/menuCoordinator');
        }else{
            if($this->session->userdata('user_type')=='instructor')
                $this->load->view('dashboard/template/menuInstructor',$data);
            else
                $this->load->view('dashboard/template/menuStudent',$data);
        }
        
        $this->load->view('dashboard/issue/create',$data);
        $this->load->view('dashboard/footer');

	}
    
    public function create(){
        
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        // User logged in?
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'instructor';
        $type2 = 'coordinator';
        $type3 = 'student';
        $type4 = 'noacademic';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if(!($u['type']==$type || $u['type']==$type2 || $u['type']==$type3 || $u['type']==$type4))
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */ 

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'title',
                'label' => 'Título',
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
            $this->session->set_flashdata(
                    'validation', 
                    array(
                            'title' => form_error('title'),
                            'description' => form_error('description')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'description' => set_value('description')
                        )
                );
            
            redirect(base_url('dashboard/issue/create'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $title = $this->input->post('title');
        $description = $this->input->post('description');
        $image = $this->input->post('image');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $i = R::dispense('issue');
        
        $i['title'] = $title;
        $i['description'] = $description;
        if($image!="")
            $i['image'] = $image;
        else
            $i['image'] = NULL;
        $i['status'] = 'open';
        $i['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $i['user'] = $user;

        $id = R::store($i);

        $this->session->set_flashdata('success','O chamado foi aberto, você pode acompanhá-lo nesta página ou através do email.');
        redirect(base_url('dashboard/issue/create'));
        exit;
    }
    
    public function uploadImage(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('string') );
        
        $config['upload_path'] = './assets/upload/issues/';
        $config['file_name'] = random_string('unique');
        $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
        
        $this->load->library('upload', $config); // upload
        
        if ( ! $this->upload->do_upload() ){
            
            $log = R::dispense('log');
            $log['msg'] = (string) $this->upload->display_errors();
            R::store($log);
            
            /* ================================================
                BEGIN - PREAPERING TO RETURN JSON OF ERROR
            ================================================ */
            
            // If there is any error, then prop 'error' will be 'true', and message will be set
            $info = new StdClass;
            $info->error = true;
            $info->message = (string) $this->upload->display_errors();
            
            echo json_encode(array("file" => $info));
            
            /* ================================================
                END - PREAPERING TO RETURN JSON OF ERROR
            ================================================ */
            
            exit;
            
        }else{
            
            /* ================================================
                BEGIN - PREAPERING TO RETURN JSON OF FILE
            ================================================ */
            
            $data = $this->upload->data();
            
            $info = new StdClass;
            $info->name = $data['file_name'];
            $info->size = $data['file_size'] * 1024;
            $info->type = $data['file_type'];
            $info->url = $config['upload_path'] . $data['file_name'];
            $info->thumbnailUrl = "";
            $info->deleteUrl = "";
            $info->deleteType = 'DELETE';
            $info->error = null;
            
            echo json_encode(array("file" => $info));
            
            /* ================================================
                END - PREAPERING TO RETURN JSON OF FILE
            ================================================ */
            
            exit;
        }
        
    }
    
    public function manageView($filter = 'all'){
    
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
        
        
        
        if($filter=='open'){
            $issues = R::find('issue',' status="open" ORDER BY created_at DESC ');
        }else if($filter=='closed'){
            $issues = R::find('issue',' status="closed" ORDER BY created_at DESC ');
        }else{
            $issues = R::find('issue','ORDER BY created_at DESC');
        }
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'issues' => $issues
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/issue/manage',$data);
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
        
        $issue = R::findOne('issue','id=?',array($id));
        $issue_records = $issue->with('ORDER BY created_at DESC')->ownIssuereplyList;
        
        $data = array(
                    'issue' => $issue,
                    'issue_records' => $issue_records
                );

        $this->load->view('dashboard/issue/retrieveDetails',$data);
        
    }
    
    public function userRetrieveDetailsView(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */ 
        
        $id = $this->input->get('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }
        
        $issue = R::findOne('issue','id=?',array($id));
        $issue_records = $issue->with('ORDER BY created_at DESC')->ownIssuereplyList;
        
        $data = array(
                    'issue' => $issue,
                    'issue_records' => $issue_records
                );

        $this->load->view('dashboard/issue/userRetrieveDetails',$data);
        
    }
    
    
    public function reply(){
    
        $this->load->library( array('session','rb','form_validation','email') );
        $this->load->helper( array('url','form','date','typography') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        // User logged in?
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
                'field' => 'reply',
                'label' => 'Resposta',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você precisa preencher os dados corretamente para enviar uma resposta. Repita a operação.');
            redirect(base_url('dashboard/issue/manage'));
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
            echo "Don't do this. ID has to be numeric! :D";
            exit;
        }
        
        $reply = $this->input->post('reply');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        $i = R::findOne('issue','id=?',array($id));
        $ir = R::dispense('issuereply');
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        $this->email->from('seminario@ccsa.ufrn.br', 'Seminário de Pesquisa do CCSA');
        $this->email->to($i->user->email); 

        $this->email->subject('[Resposta do Chamado] Seminário de Pesquisa do CCSA');

        $msg = "<h1 style='font-weight:bold;'>Uma resposta foi adicionada ao chamado</h1>";
        $msg .= "<p>".auto_typography($reply)."</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $this->email->message(emailMsg($msg)); // Simple message to test
        
        $status = false;
        
        try {
            $status = $this->email->send();
        } catch (Exception $e) {
            
        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/issue/manage'));
            exit;
        }
        
        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        $ir->reply = auto_typography($reply);
        $ir->createdAt = mdate('%Y-%m-%d %H:%i:%s');
        $ir->user = $user;
        $ir->issue = $i;
        
        R::store($ir);

        $this->session->set_flashdata('success','A nova resposta foi adicionada ao chamado.');
        redirect(base_url('dashboard/issue/manage'));
        exit;
    
    }
    
    public function userReply(){
    
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date','typography') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        // User logged in?
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'reply',
                'label' => 'Resposta',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você precisa preencher os dados corretamente para enviar uma resposta. Repita a operação.');
            redirect(base_url('dashboard/issue/create'));
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
            echo "Don't do this. ID has to be numeric! :D";
            exit;
        }
        
        $reply = $this->input->post('reply');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        $i = R::findOne('issue','id=?',array($id));
        $ir = R::dispense('issuereply');
        
        $ir->reply = auto_typography($reply);
        $ir->createdAt = mdate('%Y-%m-%d %H:%i:%s');
        $ir->user = $user;
        $ir->issue = $i;
        
        R::store($ir);

        $this->session->set_flashdata('success','A nova resposta foi adicionada ao chamado.');
        redirect(base_url('dashboard/issue/create'));
        exit;
    
    }
    
    public function close(){
    
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date','typography') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        // User logged in?
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
            echo "Don't do this. ID has to be numeric! :D";
            exit;
        }
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        $i = R::findOne('issue','id=?',array($id));
        $i->status = 'closed';
        R::store($i);

        $this->session->set_flashdata('success','O chamado foi fechado com sucesso.');
        redirect(base_url('dashboard/issue/manage'));
        exit;
    
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */