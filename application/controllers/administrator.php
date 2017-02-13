<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrator extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
    }

    public function index(){

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
        

        $adms = R::find('user','type=?',array('administrator'));

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'adms' => $adms,
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/administrator/manage',$data);
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
        
        $administrator = R::findOne('user','id=?',array($id));

        $data = array(
                    'administrator' => $administrator
                );

        $this->load->view('dashboard/administrator/retrieveDetails',$data);

    }

    public function create(){

        $this->load->library( array('rb', 'form_validation','session','email', 'gomail') );
        $this->load->helper( array('url','security','date') );
        
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
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => 'Senha',
                'rules' => 'required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Telefone',
                'rules' => 'required|numeric'
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
                            'phone' => form_error('phone'),
                            'password' => form_error('password')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'name' => set_value('name'),
                            'email' => set_value('email'),
                            'phone' => set_value('phone')
                        )
                );
            
            redirect(base_url('dashboard/administrator'));
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
        $password = $this->input->post('password');
        $phone = $this->input->post('phone');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $users = R::find('user','email=?',array($email));

        if(count($users)>0){
            $this->session->set_flashdata('error','Já existe um usuário com este email.');
            redirect(base_url('dashboard/administrator'));
            exit;
        }

        $user = R::dispense('user');
        $user['name'] = $name;
        $user['email'] = $email;
        $user['type'] = 'administrator';
        $user['password'] = do_hash($password,'md5');
        $user['phone'] = $phone;
        $user['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        $id = R::store($user);

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        

        $this->email->subject();

        $msg = "<h1 style='font-weight:bold;'>Você foi cadastrado no sistema com sucesso</h1>";
        $msg .= "<h3>Você foi cadastrado como administrador do seminário</h3>";
        $msg .= "<p>Você pode realizar todas as operações de controle do sistema do seminário.</p>";
        $msg .= "<p>Seus dados de acesso são os seguintes:</p><ul><li><b>Email:</b> $email</li><li><b>Senha:</b> $password</li></ul>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $this->email->message(emailMsg($msg)); // Simple message to test
        
        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br', 
                'Seminário de Pesquisa do CCSA', 
                $email, 
                '[Cadastro de Administrador] Seminário de pesquisa do CCSA', 
                emailMsg($msg)
            );
        } catch (Exception $e) {
            
        }

        if(!$status){
            echo '<html><head><meta charset="utf-8">
</head><body><p>VOCÊ FOI CADASTRADO COM SUCESSO, porém o servidor de emails, neste momento parece estar fora do ar. Segue a mensagem que iria ser enviada para seu email:</p>'.$msg.'</body></html>';
            exit;
        }
        
        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */
        

        $this->session->set_flashdata('success','O administrador foi adicionado com sucesso.');
        redirect(base_url('dashboard/administrator'));
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

        // NO VALIDATION

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

        $password = $this->input->post('password');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $user = R::findOne('user','id=?',array($id));

        if($password!="")
            $user->password = do_hash($password,'md5');
        else
            exit;

        R::store($user);

        $this->session->set_flashdata('success','O administrador foi <b>atualizado</b> com sucesso.');
        redirect(base_url('dashboard/administrator'));
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

        $user = R::findOne('user','id=?', array($id) );
        R::trash($user);

        $this->session->set_flashdata('success','O administrador foi <b>removido</b> com sucesso.');
        redirect(base_url('dashboard/administrator'));
        exit;


    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */