<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));

    }

	public function index(){

        $this->load->library(array('session','rb'));
        $this->load->helper(array('url','text'));

        // User logged in?
        $usera = $this->session->userdata('user_logged_in');

        if(!$usera)
        	redirect(base_url());

        $data = array(
            'news' => R::find('news','ORDER BY created_at DESC LIMIT 10'),
            'user' => R::findOne('user','id=?',array($this->session->userdata('user_id'))),
            'active' => 'home'
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

        if($this->session->userdata('user_type')=='administrator'){
            $this->load->view('dashboard/home');
        }else if($this->session->userdata('user_type')=='coordinator'){
            $this->load->view('dashboard/home_coordinator',$data);
        }else{
            $this->load->view('dashboard/home_participant',$data);
        }

        $this->load->view('dashboard/footer');

	}

    public function paymentView(){

        $this->load->library(array('session','rb'));
        $this->load->helper(array('url','form'));

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'instructor';
        $type2 = 'student';
        $type3 = 'noacademic';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if(!( $u['type']!=$type || $u['type']!=$type2 || $u['type']!=$type3 ))
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */

        $data = array(
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error'),
                'validation' => $this->session->flashdata('validation'),
                'user' => $user,
                'active' => 'payment'
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

        $this->load->view('dashboard/user/payment',$data);
        $this->load->view('dashboard/footer');

    }

    public function paymentUpload(){

        $this->load->library( array('rb') );
        $this->load->helper( array('string') );

        $config['upload_path'] = './assets/upload/payment/';
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

    public function submitPayment(){

        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'instructor';
        $type2 = 'student';
        $type3 = 'noacademic';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if( !($u['type']!=$type || $u['type']!=$type2 || $u['type']!='noacademic') )
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */

        $validation = array(
            array(
                'field' => 'payment',
                'label' => 'Comprovante de Pagamento',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('validation', array('payment' => form_error('payment')));
            redirect(base_url('dashboard/user/payment'));
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $payment = $this->input->post('payment');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $user->paid = 'pendent';
        $user->payment = $payment;

        R::store($user);

        $this->session->set_flashdata('success', 'O comprovante de pagamento foi enviado com sucesso.');
        redirect(base_url('dashboard/user/payment'));

    }

	public function loginView(){

        $this->load->library( array('rb','form_validation','session') );
        $this->load->helper( array('url','form') );

		// User already logged in?
		$user = $this->session->userdata('user_logged_in');
		if($user)
			redirect(base_url('dashboard'));

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform')
                );

        $this->load->view('template/header');
        $this->load->view('signin',$data);
        $this->load->view('template/footer');

	}

	public function login(){

        $this->load->library( array('rb', 'session', 'email', 'gomail') );
		$this->load->helper( array('form' , 'url' , 'date' , 'security', 'utility' ) );

        $this->output->set_header('Content-Type: application/json');

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $email = $this->input->post('email');
		$password = $this->input->post('password');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $users = R::find('user', "email = '".$email."'");

		// Is there any user with this email?
		if(!count($users)){

            echo json_encode(
                array(
                    'status' => 'error',
                    'msg' => 'Email não encontrado em nossos registros.'
                )
            );
            exit;

		}

		foreach ($users as $user) {

			// Password is not correct
			if( $user->password!=do_hash($password,'md5') ){

				echo json_encode(
                    array(
                        'status' => 'error',
                        'msg' => 'A senha não está correta'
                    )
                );
                exit;

			}else{

                $this->session->set_userdata(
                    array(
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'user_type' => $user->type,
                        'user_logged_in' =>  mdate('%Y-%m-%d %H:%i:%s')
                        )
                );

                echo json_encode(
                    array(
                        'status' => 'success'
                    )
                );
                exit;
;
			}

		}

	}

	public function createUser(){

		$this->load->library( array('rb', 'form_validation', 'session', 'email', 'gomail') );
		$this->load->helper( array('form' , 'url' , 'date' , 'security', 'utility' ) );

        $this->output->set_header('Content-Type: application/json');

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
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ),
            array(
                'field' => 'category',
                'label' => 'Categoria',
                'rules' => 'required'
            ),
            array(
                'field' => 'institution',
                'label' => 'Instituição',
                'rules' => 'required'
            ),
            array(
                'field' => 'phone',
                'label' => 'Telefone',
                'rules' => 'required'
            ),
            array(
                'field' => 'password',
                'label' => 'Senha',
                'rules' => 'required'
            ),
            array(
                'field' => 'rpassword',
                'label' => 'Repetir Senha',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){

            echo json_encode(
                array(
                    'status' => 'error',
                    'msg' => 'Existe algum erro no preenchimento do formulário, verifique.'
                )
            );
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
        $cpf = $this->input->post('cpf');
		$phone = $this->input->post('phone');
		$password = $this->input->post('password');
        $rpassword = $this->input->post('rpassword');
        $institution = $this->input->post('institution');

        $category = $this->input->post('category');

        if( $rpassword != $password ){

            echo json_encode(
                array(
                    'status' => 'error',
                    'msg' => 'Senha e Repetir Senha precisam ser iguais'
                )
            );
            exit;

        }

        if($category=="instructor")
            $category  = "instructor";
        else if($category=="student")
            $category  = "student";
        else if($category=='noacademic')
            $category = 'noacademic';
        else{

            echo json_encode(
                array(
                    'status' => 'error',
                    'msg' => 'Somente Estudante, Docente ou Sem Vínculo são permitidos'
                )
            );
            exit;

        }

        /* ===========================================
            END - PREPARING DATA
        ============================================ */


		// Verifying if user already exists ( the key email can change )
		$users = R::find('user',"email = '".$email."'");

		if(count($users)){ // This user already exists

            echo json_encode(
                array(
                    'status' => 'error',
                    'msg' => 'Este email já está cadastrado. Clique em "Entrar no Sistema" e em "Recuperar Senha". '
                )
            );
            exit;

		}

		$user = R::dispense("user");
		$user['name'] = $name;
		$user['email'] = $email;
        $user['cpf'] = $cpf;
		$user['phone'] = $phone;
        $user['institution'] = $institution;
		$user['password'] = do_hash($password,'md5');
		$user['type'] = $category;

        $user['payment'] = '';
        $user['paid'] = 'no';
        $user['authorizedinevent'] = 'no';
		$user['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
		R::store($user);

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        $msg = "<h1 style='font-weight:bold;'>Você foi cadastrado no sistema com sucesso</h1>";
        $msg .= "<h3>Porém sua inscrição no evento ainda não está efetivada, você precisa realizar o pagamento.</h3>";
        $msg .= "<p>Na nova versão, o sistema do Seminário de Pesquisa do CCSA oferece todas as funcionalidades de inscrição, submissão de trabalhos e submissão de propostas de atividades (minicursos, mesas redondas, conferências, em conformidade com as respectivas normas) diretamente pelo próprio sistema! Além disso, o inscrito encontrará instruções para o pagamento da Taxa de Inscrição e campo próprio para o envio do comprovante de pagamento, que validará a sua inscrição.</p>";
        $msg .= "<p>Seus dados de acesso são os seguintes:</p><ul><li><b>Email:</b> $email</li><li><b>Senha:</b> $password</li></ul>";
        if(R::count('user','type = "instructor" OR type = "student" OR type = "noacademic" ')>1500){
            $msg .= "<p><b>AVISO</b> Informamos que diante do grande número de inscritos no Seminário a entrega de material (pasta com programação, bloco, caneta) está limitada a 1500 participantes.  Assim, o sistema irá contabilizando as inscrições efetivamente concluídas até este número para efeito de entrega de material. Após este número, as inscrições poderão ser feitas mas o participante fica ciente de que não receberá o material do evento. No entanto, isso não impedirá a participação nas atividades programadas nem a sua certificação.</p>";
        }
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $email,
                '[Cadastro no Sistema] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );
        } catch (Exception $e) {

        }

        if(!$status){
            echo '<html><head><meta charset="utf-8">
</head><body><p>VOCÊ FOI CADASTRADO COM SUCESSO, porém o servidor de emails, neste momento, parece estar fora do ar. Segue a mensagem que iria ser enviada para seu email:</p>'.$msg.' <p><a href=\''.base_url('dashboard').'\'>Clique aqui para entrar no sistema</a></p></body></html>';
            exit;
        }

        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        /*

            GAMBIARRA DE PRIMEIRA KKKKKKKKKKKK

        $javascript = "";

        if(R::count('user','type = "instructor" OR type = "student" ')>1500)
            $javascript = "Informamos que diante do grande número de inscritos no Seminário a entrega de material (pasta com programação, bloco, caneta) está limitada a 1500 participantes.  Assim, o sistema irá contabilizando as inscrições efetivamente concluídas até este número para efeito de entrega de material. Após este número, as inscrições poderão ser feitas mas o participante fica ciente de que não receberá o material do evento. No entanto, isso não impedirá a participação nas atividades programadas nem a sua certificação.";

        */

		echo json_encode(
            array(
                'status' => 'success',
                'type' => 'logic',
                'msg' => 'Você foi cadastrado com sucesso.'
            )
        );
        exit;

	}

	public function resetPasswordView(){

		$this->load->library('session');
		$this->load->helper( array('form','url','string') );

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform')
                );


		$this->load->view('template/header');
        $this->load->view('resetpassword',$data);
        $this->load->view('template/footer');

	}

	public function doLogout(){

		$this->load->library('session');

		$this->session->sess_destroy();

		redirect(base_url(''));

	}

	public function resetpassword(){

		$this->load->library( array('rb', 'form_validation','email','session', 'gomail') );
        $this->load->helper( array('url','security','date','string') );

        $this->output->set_header('Content-Type: application/json');

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $email = $this->input->post('email');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

		$users = R::find('user', "email = '".$email."'");

		// Is there any user with this email?
		if(!count($users)){

			echo json_encode(
                array(
                    'status' => 'error',
                    'msg' => 'Email não encontrado em nossos registros.'
                )
            );
            exit;

		}

		foreach ($users as $user) {

			$newPass = random_string('alnum',8);
			$encNewPass = do_hash($newPass,'md5');

			$status = false;

            $msg = "<h1>Recuperação de Senha</h1>";
            $msg .= "<p>Olá, sua nova senha é: ".$newPass."</p>";
            $msg .= "<p>Acesse <a href='".base_url()."'>o seu painel</a> utilizando a nova senha, vá em <b>Meus dados</b> na parte superior direita, e escolha sua nova senha.</p> ";

			$status = $this->gomail->send_email(
                'assessoriatecnica@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $email,
                '[Recuperando senha] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );

			if(!$status){

                echo json_encode(
                    array(
                        'status' => 'error',
                        'msg' => 'O servidor de emails parece estar fora do ar, tente novamente mais tarde. Caso persista o problema, entre em contato conosco.'
                    )
                );
                exit;

			}

			$user->password = $encNewPass;
            $user->retrievepass = 'yes';

			R::store($user);

            echo json_encode(
                array(
                    'status' => 'success',
                    'msg' => 'Uma nova senha foi gerada e enviada para o seu email.'
                )
            );
            exit;

		}

	}

    public function myInformation(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');

        if(!$userLogged)
        	redirect(base_url('dashboard/login'));

        $user = R::findOne('user','id=?', array($this->session->userdata('user_id')));

        $data = array(
        		'user' => $user,
                'active' => 'my-information'
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

		$this->load->view('dashboard/myInformation',$data);
        $this->load->view('dashboard/footer');

	}

	public function updateUser(){

		$this->load->library( array('rb', 'form_validation', 'session') );
		$this->load->helper( array( 'security' ) );

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */

        $validation = array(
            array(
                'field' => 'phone',
                'label' => 'Telefone',
                'rules' => 'required'
            ),
            array(
                'field' => 'newPass',
                'label' => 'Nova Senha',
                'rules' => 'matches[newRpass]'
            ),
            array(
                'field' => 'newRPass',
                'label' => 'Repetir Nova Senha',
                'rules' => ''
            ),
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            ),
            array(
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '<br/>');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            echo "0,".validation_errors();
			exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

		$phone = $this->input->post('phone');
		$oldPass = $this->input->post('oldPass');
		$newPass = $this->input->post('newPass');
		$newRpass = $this->input->post('newRpass');
        $name = $this->input->post('name');
        $cpf = $this->input->post('cpf');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

		$id = $this->session->userdata('user_id');

		// Retrieving the user
		$user = R::findOne('user','id=?', array($id) );

		// If new pass is set but the old password do not match, then...
		if( ( $newPass!="" || $newRpass!="" )
			&&  do_hash($oldPass,'md5')!=$user->password){
			echo "1";
			exit;
		}

		// If the new pass and the new pass confirmation are different
		if($newPass!=$newRpass){
			echo "2";
			exit;
		}

		$user['phone'] = $phone;
        if(!isset($user->certgen))
            $user['name'] = $name;
		if($newPass!=""){
            $user['password'] = do_hash($newPass,'md5');
            $user['retrievepass'] = 'no';
        }

        $user->cpf = $cpf;

		R::store($user);

		echo "3";
		exit;

	}

    public function myActivitiesView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */

        $data = array(
                    'user' => $user,
                    'active' => 'myactivities'
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuStudent',$data);
        $this->load->view('dashboard/user/myactivities',$data);
        $this->load->view('dashboard/footer');

    }

    public function reportInscriptionView(){

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

        /* GETTING PARAMETERS */
        // http://www.codeigniter.com/user_guide/helpers/url_helper.html
        // http://stackoverflow.com/questions/2728978/codeigniter-passing-variables-via-url-alternatives-to-using-get
        // http://stackoverflow.com/questions/5809774/manipulate-a-url-string-by-adding-get-parameters
        // http://www.codeigniter.com/user_guide/libraries/uri.html
        $params = $this->uri->uri_to_assoc(2);

        /* MANUALLY PAGINATION */
        // Pagination start in page 1 and so on
        $page = 1;
        if(isset($params['page']))
            $page = $params['page'];
        if($page<1)
            $page = 1;

        $limit = 10;

        /* SELECTING USERS */

        $sqla = "";
        $arra = Array();

        if(isset($params['search'])){
            $sqla .= ' AND name LIKE ? ';
            $searchf = str_replace("%20", "%",$params['search']);
            $arra[] = '%'.$searchf.'%';
        }

        $users = R::find('user',' ( type="student" OR type="instructor" OR type="noacademic" ) '.$sqla.' LIMIT ? , ? ',array_merge($arra,array(($page-1)*$limit,$limit)));
        $userscount = R::count('user',' ( type="student" OR type="instructor" OR type="noacademic" ) '.$sqla, $arra );

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'users' => $users,
                    'pagination' => array('pages' => ceil($userscount/$limit),'page' => $page, 'limit' => $limit, 'records' => $userscount)
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/user/reportinscription',$data);
        $this->load->view('dashboard/footer');

    }


    public function createReportInscription()
    {

        /*
         * Loading libraries and helpers
        */
        $this->load->library(
            array(
                'rb',
                'fpdfgen',
                'session'
            )
        );

        $this->load->helper(
            array(
                'text'
            )
        );

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


        /*
         * Creating PDF
        */
        $pdf = new FPDI();

        $pdf->addPage('L');

        /* *********************************************************
         * BEGIN  - HEADER
         ********************************************************* */

        $pdf->image(
            asset_url().'img/logopdf.png',
            132,
            5
        );

        $pdf->ln(14);
        $pdf->SetFont('Courier','B',12);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'SEMINÁRIO DE PESQUISA DO CCSA' ),
            0,
            0,
            'C'
        );

        $pdf->Ln(7);
        $pdf->SetFont('Courier','',9);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'Relatório de Inscrito' ),
            0,
            0,
            'C'
        );

        /* *********************************************************
        * END - HEADER
        ********************************************************* */

        $params = $this->uri->uri_to_assoc(2);

        /*
         * Loading User
        */
        $user = R::findOne(
            'user',
            ' id = ? ',
            array(
                $params['id']
            )
        );


        /*
         * General Info
        */
        $pdf->Ln(6);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( $user->name ),
            'LRT',
            0,
            'C',
            true
        );


        $pdf->Ln(10);

        $pdf->SetFillColor(255,255,255);

        $pdf->Cell(
            20,
            10,
            utf8_decode( ' Email : ' ),
            'LT',
            0,
            'L',
            true
        );

        $pdf->Cell(
            120,
            10,
            utf8_decode($user->email),
            'T',
            0,
            'L',
            true
        );

        $pdf->Cell(
            26,
            10,
            utf8_decode( ' Telefone : ' ),
            'T',
            0,
            'L',
            true
        );

        $pdf->Cell(
            111,
            10,
            utf8_decode($user->phone),
            'RT',
            0,
            'L',
            true
        );

        $pdf->Ln(10);

        $pdf->Cell(
            30,
            10,
            utf8_decode( ' Instituição : ' ),
            'LB',
            0,
            'L',
            true
        );

        $pdf->Cell(
            110,
            10,
            utf8_decode($user->institution),
            'B',
            0,
            'L',
            true
        );

        $pdf->Cell(
            26,
            10,
            utf8_decode( ' Pagamento : ' ),
            'B',
            0,
            'L',
            true
        );

        // Verifying

        $pag = "";

        if( $user->paid == 'accepted' )
            $pag =  "Realizado";
        else if($user->paid=='pendent')
            $pag =  "Esperando avaliação do pagamento";
        else if($user->paid=='rejected')
            $pag =  "Comprovante de pagamento rejeitado, esperando novo envio de comprovante";
        else if($user->paid=='no')
            $pag =  "Ainda não enviou nenhum comprovante";
        else if($user->paid=='free')
            $pag =  "Isento";

        $pdf->Cell(
            111,
            10,
            utf8_decode( $pag ),
            'RB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);


        /*
         * Papers
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'ARTIGOS SUBMETIDOS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->ownPaperList as $p) :

            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;


        /*
         * Posters
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'PÔSTERES SUBMETIDOS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->ownPosterList as $p) :

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;


        /*
         * RoundTables
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'MESAS-REDONDAS SUBMETIDAS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->ownRoundtableList as $p) :

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;


        /*
         * Minicourse Inscriptions
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'INSCRIÇÕES EM MINICURSOS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->sharedMinicourseList as $p) :

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;


        /*
         * Minicourse Conferences
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'INSCRIÇÕES EM CONFERÊNCIAS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->sharedConferenceList as $p) :

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;

        /*
         * Round Table Inscription
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'INSCRIÇÕES EM MESAS-REDONDAS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->sharedRoundtableList as $p) :

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;


        /*
         * Minicourse Round Table
        */

        $pdf->Ln(10);

        $pdf->SetDrawColor(170,170,170);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( 'INSCRIÇÕES EM OFICINAS' ),
            'LRTB',
            0,
            'C',
            true
        );

        $pdf->Ln(10);

        foreach ($user->with('ORDER BY title ASC')->sharedWorkshopList as $p) :

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(255,255,255);

            $pdf->MultiCell(
                0,
                10,
                utf8_decode( ' '.$p->title ),
                'LRB',
                'L',
                false
            );

        endforeach;


        $pdf->Output();

    }

    public function report(){

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

        /* LIST OF FIELDS THAT CAN BE GENERATED WITH REPORT */

        $fields = array(
            'name' => 'Nome',
            'id' => 'ID',
            'email' => 'Email',
            'phone' => 'Telefone',
            'type' => 'Tipo',
            'paid' => 'Pagamento',
            'institution' => 'Instituição',
            'created_at' => 'Data de Cadastro'
        );

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'fields' => $fields,
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/user/report',$data);
        $this->load->view('dashboard/footer');

	}

    public function createReport(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

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

        $filter1 = $this->input->post('filter1');
		$filter2 = $this->input->post('filter2');
		$order = $this->input->post('order');
		$fields = $this->input->post('fields');
        $sepreg = $this->input->post('sepreg');
        $sepfields = $this->input->post('sepfields');
        $generate = $this->input->post('generate');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Fields were selected?
        if(!isset($fields[0])){
            echo "Por favor, selecione algum campo para ser exibido.";
            exit;
        }

        $sqla = "";

        /* FILTER 2 */
        if($filter2=='all')
            $sqla .= ' ( type="student" OR type="instructor" OR type="noacademic") ';
        else if($filter2=='student')
            $sqla .= ' ( type="student" ) ';
        else if($filter2=='student')
            $sqla .= ' ( type="instructor" ) ';
        else if($filter2=='noacademic')
            $sqla .= ' ( type="noacademic" )';

        /* FILTER 1 */
        if($filter1=='no')
            $sqla .= ' AND ( paid="no" ) ';
        else if($filter1=='accepted')
            $sqla .= ' AND ( paid="accepted" ) ';
        else if($filter1=='free')
            $sqla .= ' AND ( paid="free" ) ';
        else if($filter1=='enrolled')
            $sqla .= ' AND ( paid="accepted" OR paid="free" ) ';

        /* ORDER */
        if($order=='nameasc')
            $sqla .= ' ORDER BY name ASC ';
        else if($filter2=='namedesc')
            $sqla .= ' ORDER BY name DESC ';

        if($sepfields=='')
            $sepfields=' ';
        else
            $sepfields= ' '.$sepfields.' ';

        /* PRINTING */

        echo "<meta charset='UTF-8' />";

        $result = R::find('user',$sqla);

        if($generate=='html') echo "<table border='1' style='width:100%'>";

        foreach($result as $user){
            if($generate=='html') echo "<tr>";
            $i = 1;
            foreach($fields as $field){
                if($generate=='html') echo "<td>";
                if($field=='email')
                    echo $user[$field];
                else
                    echo strtoupper($user[$field]);
                if($generate=='html') echo "</td>";
                if($i++!=count($fields))
                    echo ' '.$sepfields;
            }
            if($generate=='html') echo "</tr>";
            else echo "<br/>";

        }

        if($generate=='html') echo "</table>";

        echo "<br/><b>".count($result)." registros encontrados</b>";

    }

    public function manage(){

        $this->load->library( array('session','rb','pagination','uri') );
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

        /* GETTING PARAMETERS */
        // http://www.codeigniter.com/user_guide/helpers/url_helper.html
        // http://stackoverflow.com/questions/2728978/codeigniter-passing-variables-via-url-alternatives-to-using-get
        // http://stackoverflow.com/questions/5809774/manipulate-a-url-string-by-adding-get-parameters
        // http://www.codeigniter.com/user_guide/libraries/uri.html
        $params = $this->uri->uri_to_assoc(2);

        /* MANUALLY PAGINATION */
        // Pagination start in page 1 and so on
        $page = 1;
        if(isset($params['page']))
            $page = $params['page'];
        if($page<1)
            $page = 1;

        $limit = 10;

        /* SELECTING USERS */

        $sqla = "";
        $arra = Array();

        if(isset($params['search'])){
            $sqla .= ' AND name LIKE ? ';
            $searchf = str_replace("%20", "%",$params['search']);
            $arra[] = '%'.$searchf.'%';
        }

        if(isset($params['filter'])){
            if($params['filter']=='no')
                $sqla .= ' AND ( paid <> "pendent" AND paid <> "free" AND paid <> "accepted" ) ';
            if($params['filter']=='pendent')
                $sqla .= ' AND ( paid = "pendent" ) ';
            if($params['filter']=='accepted')
                $sqla .= ' AND ( paid = "accepted" ) ';
            if($params['filter']=='free')
                $sqla .= ' AND ( paid = "free" ) ';
            if($params['filter']=='enrolled')
                $sqla .= ' AND ( paid = "free" OR paid= "accepted" ) ';
        }

        if(isset($params['order'])){
            if($params['order']=='cresc')
                $sqla .= ' ORDER BY name ASC ';
            if($params['order']=='desc')
                $sqla .= ' ORDER BY name DESC ';
        }

        $users = R::find('user',' ( type="student" OR type="instructor" OR type="noacademic" ) '.$sqla.' LIMIT ? , ? ',array_merge($arra,array(($page-1)*$limit,$limit)));
        $userscount = R::count('user',' ( type="student" OR type="instructor" OR type="noacademic" ) '.$sqla, $arra );

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'users' => $users,
                    'pagination' => array('pages' => ceil($userscount/$limit),'page' => $page, 'limit' => $limit, 'records' => $userscount)
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/user/manage',$data);
        $this->load->view('dashboard/footer');

	}

    function manageRetrieveDetails(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','utility') );

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

        $id = $this->input->get('id');

        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $user = R::findOne('user','id=?',array($id));

        $data = array(
            'user' => $user,
            );

        $this->load->view('dashboard/user/manage/retrieveDetails', $data);

    }

    function manageRetrieveEvaluatePayment(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','utility') );

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

        $id = $this->input->get('id');

        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $user = R::findOne('user','id=?',array($id));

        $data = array(
            'user' => $user,
            );

        $this->load->view('dashboard/user/manage/retrieveEvaluatePayment', $data);

    }

    function acceptPayment(){

        $this->load->library( array('session','rb','email', 'gomail') );
        $this->load->helper( array('url') );

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

        $id = $this->input->post('id');

        $user = R::findOne('user','id=?',array($id));

        // If the user's payment was evaluated
        if($user->paid!='pendent'){
            $this->session->set_flashdata('error', 'O pagamento já foi avaliado.');
            redirect(base_url('dashboard/user/manage/filter/pendent'));
            exit;
        }

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        $msg = "<h1 style='font-weight:bold;'>Você está confirmado para participar do seminário, parabêns!</h1>";
        $msg .= "<p>Em data prevista, você poderá se inscrever nas atividades (minicursos, mesas-redondas, etc ) pelo sistema, pois sua inscrição já está confirmada.</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

        $status = false;

        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $user->email,
                '[Pagamento Aceito] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );
        } catch (Exception $e) {

        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/user/manage/filter/pendent'));
            exit;
        }

        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        $user->paid = 'accepted';
        R::store($user);

        $this->session->set_flashdata('success', 'O pagamento foi avaliado como <b>aceito</b> com sucesso.');
        redirect(base_url('dashboard/user/manage/filter/pendent'));

    }

    function rejectPayment(){

        $this->load->library( array('session','rb','email','form_validation', 'gomail') );
        $this->load->helper( array('url') );

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
                'field' => 'observation',
                'label' => 'Observação',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);

        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique se você preencheu o campo Observação, pois ele é obrigatório para reijeitar um pagamento.');
            redirect(base_url('dashboard/user/manage/filter/pendent'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        $id = $this->input->post('id');
        $observation = $this->input->post('observation');

        $user = R::findOne('user','id=?',array($id));

        // If the user's payment was evaluated
        if($user->paid!='pendent'){
            $this->session->set_flashdata('error', 'O pagamento já foi avaliado.');
            redirect(base_url('dashboard/user/manage/filter/pendent'));
            exit;
        }

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        $msg = "<h1 style='font-weight:bold;'>Olá, seu comprovante de pagamento foi rejeitado!</h1>";
        $msg .= "<p>Seu comprovante não foi aceito como válido, envie novamente o comprovante pelo sistema. Qualquer dúvida, você pode entrar no painel do seminário e abrir um chamado em 'suporte' para saber o que aconteceu.</p>";
        $msg .= "<p><b>Motivo: </b>".$observation."</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

        $status = false;

        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $user->email,
                '[Pagamento Rejeitado] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );
        } catch (Exception $e) {

        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/user/manage/filter/pendent'));
            exit;
        }

        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        $user->paid = 'rejected';
        R::store($user);

        $this->session->set_flashdata('success', 'O pagamento foi avaliado como <b>rejeitado</b> com sucesso.');
        redirect(base_url('dashboard/user/manage/filter/pendent'));

    }

    function freePayment(){

        $this->load->library( array('session','rb','email','gomail') );
        $this->load->helper( array('url') );

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

        $id = $this->input->post('id');

        $user = R::findOne('user','id=?',array($id));

        // If the user's payment was evaluated
        if($user->paid!='no'){
            $this->session->set_flashdata('error', 'O pagamento já foi avaliado, ou está esperando avaliação. Isenção só é permitida para aqueles que ainda não enviaram o comprovante.');
            redirect(base_url('dashboard/user/manage'));
            exit;
        }

        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        $msg = "<h1 style='font-weight:bold;'>Você está confirmado para participar do seminário através da isenção do pagamento, parabéns!</h1>";
        $msg .= "<p>Nas datas previstas, você poderá submeter trabalhos ( artigos, pôsteres, casos de ensino, etc ) e se inscrever nas atividades (minicursos, mesas-redondas, etc ) pelo sistema, pois sua inscrição já está confirmada.</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

        $status = false;

        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $user->email,
                '[Isenção] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );
        } catch (Exception $e) {

        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
        }

        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        $user->paid = 'free';
        R::store($user);

        $this->session->set_flashdata('success', 'O pagamento foi avaliado como <b>isento</b> com sucesso.');
        redirect(base_url('dashboard/user/manage'));

    }

    function retrieveLinkSearchByName(){

        $link = $this->input->get('link');
        $value = $this->input->get('value');

        $upd = uri_string();
        $r = explode('/',$upd);
        $i = array_search('retrieveLinkSearchByName',$r);
        unset($r[$i]);
        $upd = implode('/',$r);

        // Code here
        $final = $link.insertGetParameter($upd,'search',$value);

        echo $final;

    }

    function retrieveLinkSearchByNameReport(){

        $link = $this->input->get('link');
        $value = $this->input->get('value');

        $upd = uri_string();
        $r = explode('/',$upd);
        $i = array_search('retrieveLinkSearchByName',$r);
        unset($r[$i]);
        $upd = implode('/',$r);

        // Code here
        $final = $link.insertGetParameter($upd,'search',$value);

        echo $final;

    }

    function submitReporterView() {
      $this->load->library(array('session','rb'));
      $this->load->helper(array('url','text'));

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

      $minicourses = R::find('minicourse');
      $roundtables = R::find('roundtable');
      $workshops = R::find('workshop');

      $this->load->view('dashboard/header');
      $this->load->view('dashboard/template/menuAdministrator');
      $this->load->view('dashboard/user/submiterReport', array(
        'minicourses' => $minicourses,
        'roundtables' => $roundtables,
        'workshops' => $workshops
      ));
      $this->load->view('dashboard/footer');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
