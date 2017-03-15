<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coordinator extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
    }

    public function index($order = 'noset', $progs = 'ASC'){

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

        /* =================================================
            BEGIN - ORDERING ELEMENTS
        ================================================== */

        if($order=='name'){
            if($progs=='DESC')
                $coordinators = R::find('user','type = ? ORDER BY name DESC',array('coordinator'));
            else
                $coordinators = R::find('user','type = ? ORDER BY name ASC',array('coordinator'));
        }else{
            $coordinators = R::find('user','type=?',array('coordinator'));
        }
        /* =================================================
            END - ORDERING ELEMENTS
        ================================================== */

        $thematicGroups = R::find('thematicgroup','ORDER BY name ASC');

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'coordinators' => $coordinators,
                    'thematicGroups' => $thematicGroups,
                    'order' => array($order,$progs)
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/coordinator/manage',$data);
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

        $coordinator = R::findOne('user','id=?',array($id));
        $thematicgroups = R::find('thematicgroup','ORDER BY name ASC');

        $data = array(
                    'coordinator' => $coordinator,
                    'thematicgroups' => $thematicgroups
                );

        $this->load->view('dashboard/coordinator/retrieveDetails',$data);

	}

    public function create(){

        $this->load->library( array('rb', 'form_validation','session','email', 'gomail') );
        $this->load->helper( array('url','security','date') );

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
                'field' => 'thematicGroups',
                'label' => 'Grupos Temáticos',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        $thematicGroups = $this->input->post('thematicGroups');
        $tgs = array();

        foreach ($thematicGroups as $tgid) {
            $tgs[] = $tgid;
        }

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata(
                    'validation',
                    array(
                            'name' => form_error('name'),
                            'email' => form_error('email'),
                            'password' => form_error('password'),
                            'thematicGroups' => form_error('thematicGroups')
                        )
                );

             $this->session->set_flashdata(
                    'popform',
                    array(
                            'name' => set_value('name'),
                            'email' => set_value('email'),
                            'thematicGroups' => $tgs
                        )
                );

            redirect(base_url('dashboard/coordinator'));
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
        $thematicGroups = $this->input->post('thematicGroups');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $users = R::find('user','email=?',array($email));

        if(count($users)>0){
            $this->session->set_flashdata('error','Já existe um usuário com este email.');
            redirect(base_url('dashboard/coordinator'));
            exit;
        }

        $user = R::dispense('user');
        $user['name'] = $name;
        $user['email'] = $email;
        $user['type'] = 'coordinator';
        $user['password'] = do_hash($password,'md5');
        $user['phone'] = "00000000";
        $user['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        foreach ($thematicGroups as $tgid) {
            $tg = R::findOne('thematicgroup','id=?',array($tgid));
            $user->sharedThematicgroupList[] = $tg;
        }

        $id = R::store($user);

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */

        $msg = "<h1 style='font-weight:bold;'>Você foi cadastrado no sistema com sucesso</h1>";
        $msg .= "<h3>Você foi cadastrado como coordenador do seminário</h3>";
        $msg .= "<p>Você tem responsabilidade de avaliar artigos e pôsteres até a data prevista.</p>";
        $msg .= "<p>Você ficou responsável pelos seguintes grupos temáticos:</p><ul>";
        foreach($user->sharedThematicgroupList as $tg)
            $msg .= "<li>$tg->name</li>";
        $msg .= "</ul>";
        $msg .= "<p>Seus dados de acesso são os seguintes:</p><ul><li><b>Email:</b> $email</li><li><b>Senha:</b> $password</li></ul>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $email,
                '[Cadastro de Coordenador] Seminário de Pesquisa do CCSA',
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


        $this->session->set_flashdata('success','O coordenador foi adicionado com sucesso.');
        redirect(base_url('dashboard/coordinator'));
        exit;

    }

    public function user2coordinator() {

        $this->load->library( array('rb', 'form_validation','session','email', 'gomail') );
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
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você precisa preencher todos os campos obrigatórios para poder atualizar o coordenador. Repita a operação.');
            redirect(base_url('dashboard/coordinator'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $email = $this->input->post('email');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $user = R::findOne('user','email=?',array($email));

        if(!$user) {
            $this->session->set_flashdata('error','Este usuário não existe.');
            redirect(base_url('dashboard/coordinator'));
            exit;
        } else if($user->type === 'administrator') {
            $this->session->set_flashdata('error','Você não pode rebaixar um usuário administrador à um coordenador.');
            redirect(base_url('dashboard/coordinator'));
            exit;
        } else if($user->type === 'coordinator') {
            $this->session->set_flashdata('error','Este usuário já é coordenador.');
            redirect(base_url('dashboard/coordinator'));
            exit;
        }

        $user->type = 'coordinator';

        R::store($user);

        $this->session->set_flashdata('success','O usuário foi transformado em coordenador com sucesso.');
        redirect(base_url('dashboard/coordinator'));
        exit;

    }

    public function update(){

        $this->load->library( array('rb', 'form_validation','session','email', 'gomail') );
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
                'field' => 'thematicGroups',
                'label' => 'Grupo Temático',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você precisa preencher todos os campos obrigatórios para poder atualizar o coordenador. Repita a operação.');
            redirect(base_url('dashboard/coordinator'));
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

        $password = $this->input->post('password');
        $tgs = $this->input->post('thematicGroups');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $user = R::findOne('user','id=?',array($id));

        if($password!="")
            $user->password = do_hash($password,'md5');

        $user->sharedThematicgroupList = array();

        $ccretn = ""; // Construct return to multiple select

        foreach ($tgs as $tgid) {
            $tg = R::findOne('thematicgroup','id=?',array($tgid));
            $ccretn += $tg->id."=>".$tg->name."|";
            $user->sharedThematicgroupList[] = $tg;
        }

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */

        $msg = "<h1 style='font-weight:bold;'>Seu perfil de Coordenador foi atualizado.</h1>";
        $msg .= "<p>Você está responsável pelos seguintes grupos temáticos:</p><ul>";
        foreach($user->sharedThematicgroupList as $tg)
            $msg .= "<li>$tg->name</li>";
        $msg .= "</ul>";
        $msg .= "<p>Seus novos dados são os seguintes:</p><ul><li><b>Email:</b> ".$user->email."</li><li><b>Senha:</b> ".$password."</li></ul>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

        $status = false;

        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $user->email,
                '[Atualização de Coordenador] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );
        } catch (Exception $e) {

        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/coordinator'));
            exit;
        }

        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        R::store($user);

        $this->session->set_flashdata('success','O coordenador foi <b>atualizado</b> com sucesso.');
        redirect(base_url('dashboard/coordinator'));
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

        $this->session->set_flashdata('success','O coordenador foi <b>removido</b> com sucesso.');
        redirect(base_url('dashboard/coordinator'));
        exit;

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
