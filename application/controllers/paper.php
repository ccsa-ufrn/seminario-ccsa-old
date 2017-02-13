<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paper extends Base 
{
    
    public function getSource() {
        $this->load->library(['rb']);
        
        $this->output->set_header('Content-Type: application/json');
        
        $page = $this->input->get('page');
        $search = $this->input->get('search');
        
        $papers = R::getAll('
            SELECT 
                id, title, authors, cernn, certgen 
            FROM 
                paper 
            WHERE 
                ( title LIKE ? 
                OR authors LIKE ? )
                AND cernn = "yes"
            ORDER BY 
                title ASC
            LIMIT 10 OFFSET '.($page-1)*10
        ,
            array('%'.$search.'%', '%'.$search.'%')
        );
        $papers = R::convertToBeans('paper', $papers );
            
        echo json_encode(
            array(
                'status' => 'success',
                'data' => R::exportAll($papers),
                'numberResults' => R::count('paper', ' (title LIKE ? OR authors LIKE ?) AND cernn = "yes" ', array('%'.$search.'%', '%'.$search.'%'))
            )
        );
        exit;
    }
    
    
    /*
     * Página para realizar download dos artigos
    */
    public function getPaper() {
        $this->load->library(['rb']);
        
        $papers = R::find('paper', 'evaluation = "accepted" ');
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
		$this->load->view(
            'dashboard/paper/getp',
            ['papers' => $papers]
        );
        $this->load->view('dashboard/footer');
    }
    

    /*
     * Function : submitView()
     * Description : View to submit papers
    */
    public function submitView()
    {
        
        /*
         * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'session',
                'rb'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url',
                'form',
                'date'
            ) 
        );

        
        /*
         * User is logged?
        */
        if ( !parent::_isLogged() ) : 
        
            redirect(
                base_url(
                    'dashboard'
                )
            );
        
        endif;

        
        /*
         * Getting max date to submit papers
        */
        $config = R::findOne(
            'configuration',
            ' name= "max_date_paper_submission" '
        );
        
        
        /*
         * Date is less or equal to max date?
        */
        $open = false;
        
        if ( dateleq( mdate('%Y-%m-%d') , $config->value ) ) :
        
            $open = true;
            
        endif;
            
            
        /*
         * Retrieving user
        */
        $user = R::findOne(
            'user','id=?',
            array(
                $this->session->userdata('user_id')
            )
        );
        
        
        /*
         * System needs payment to send works?
        */
        $needPayment = R::findOne(
            'configuration',
            ' name = "need_payment" '
        );
        
        $paid = true;
        
        if ( $needPayment->value == 'true' ) :
           
            if( $user->paid == 'no' ) : 
            
                $paid = false;    
            
            endif;
            
        endif;


        /*
         * Loading views
        */
        $this->load->view('dashboard/header');

        if( $this->session->userdata('user_type') == 'administrator') :
        
            $this->load->view('dashboard/template/menuAdministrator');
            
        elseif ( $this->session->userdata('user_type') == 'coordinator' ) :
        
            $this->load->view('dashboard/template/menuCoordinator');
            
        else :
        
            if ( $this->session->userdata('user_type')=='instructor' ) :
            
                $this->load->view(
                    'dashboard/template/menuInstructor',
                    array(
                        'active' => 'submit-paper'
                    )
                );
                
            else : 
            
                $this->load->view(
                    'dashboard/template/menuStudent',
                    array(
                        'active' => 'submit-paper'
                    )
                );
                
            endif;
                
        endif;

		$this->load->view(
            'dashboard/paper/submit',
            array(
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error'),
                'validation' => $this->session->flashdata('validation'),
                'popform' => $this->session->flashdata('popform'),
                'tgs' => R::find('thematicgroup',' is_listable = "Y" ORDER BY name ASC'),
                'papers' => R::find( 'paper','user_id=?',array($this->session->userdata('user_id') ) ),
                'paid' => $paid,
                'date_limit' => array( 'config' => $config , 'open' => $open )
            )
        );
        
        $this->load->view('dashboard/footer');
        
    }
    
    public function verifyingAuthors($str,$limit){
        
        if($limit==0) return TRUE;
        
        $result = explode('||',$str);
        
        if(count($result)>$limit)
            return FALSE;
        
        for($i=0;$i<count($result);++$i){
            $test = explode("[",$result[$i]);
            
            if($test[0]=='' || $test[1]=='')
                return FALSE;
            
        }
        
        // $limit == 0 then will not verify the quantity of authors, otherwise will verify
        return TRUE;
        
    }
    
	public function create(){
        
        $this->load->library( array('session','rb','form_validation', 'gomail') );
        $this->load->helper( array('url','form','date') );
        
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $userId = $this->session->userdata('user_id');
        
        /* ===========================================
            BEGIN - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
        $config = R::findOne('configuration','name=?',array('max_date_paper_submission'));
        
        if(!dateleq(mdate('%Y-%m-%d'),$config->value)){
            echo "Você não pode realizar esta operação. Está fora do limite de envio de trabalho. =D";
            exit;
        }      
        
        /* ===========================================
            END - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
        // Retrieving user
        $user = R::findOne('user','id=?',array($userId));

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
                'field' => 'thematicgroup',
                'label' => 'Grupo Temático',
                'rules' => 'required'
            ),
            array(
                'field' => 'authors',
                'label' => 'Autores',
                'rules' => 'required|callback_verifyingAuthors[5]'
            ),
            array(
                'field' => 'abstract',
                'label' => 'Resumo',
                'rules' => 'required'
            ),
            array(
                'field' => 'keywords',
                'label' => 'Palavras-chave',
                'rules' => 'required'
            ),
            array(
                'field' => 'paper',
                'label' => 'Artigo',
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
                            'thematicgroup' => form_error('thematicgroup'),
                            'authors' => form_error('authors'),
                            'abstract' => form_error('abstract'),
                            'keywords' => form_error('keywords'),
                            'paper' => form_error('paper')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'thematicgroup' => set_value('thematicgroup'),
                            'authors' => set_value('authors'),
                            'abstract' => set_value('abstract'),
                            'keywords' => set_value('keywords'),
                            'paper' => set_value('paper')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');            
            redirect(base_url('dashboard/paper/submit'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $title = $this->input->post('title');
        $tgid = $this->input->post('thematicgroup');
        $authors = $this->input->post('authors');
        $abstract = $this->input->post('abstract');
        $keywords = $this->input->post('keywords');
        $paper = $this->input->post('paper');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // Retrieving thematicgroup
        $tg = R::findOne('thematicgroup','id=?',array($tgid));

        $p = R::dispense('paper');
        
        $p['title'] = $title;
        $p['authors'] = $authors;
        $p['abstract'] = $abstract;
        $p['keywords'] = $keywords;
        $p['paper'] = $paper; 
        $p['avaliation1'] = 'pending';
        $p['avaliation1UserId'] = '';
        $p['avaliation2'] = 'pending';
        $p['avaliation2UserId'] = '';
        $p['evaluation'] = 'pending'; 
        $p['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $p['user'] = $user;
        $p['scheduled'] = 'no';
        $p['cernn'] = 'pending';
        $p['thematicgroup'] = $tg;

        $id = R::store($p);
        
        $this->session->set_flashdata('success','O artigo foi submetido para avalição com sucesso, você será notificado em breve.');
        redirect(base_url('dashboard/paper/submit'));
        exit;
        
	}
    
    public function uploadPaper(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('string') );
        
        $config['upload_path'] = './assets/upload/papers/';
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
    
    
    /*
     * EVALUATE VIEW
    */
    public function evaluateView(){
        
        
        /*
        * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'session',
                'rb',
                'email'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url',
                'form'
            ) 
        );
        
        
        /*
        * Loading user
        */        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        
        /*
        * Verifying user's capabilities
        */
        $type = 'coordinator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
        	redirect(base_url('dashboard'));
        $u = $user;
        if($u['type']!=$type)
            redirect(base_url('dashboard'));
            
        /*
        * Loading thematic groups
        */
        $tgs = $user
                ->withCondition( ' is_listable = "Y" ORDER BY name ASC ' )
                ->sharedThematicgroupList;
        
        
        /*
        * Loading views
        */
        $this->load->view('dashboard/header');

        if($this->session->userdata('user_type')=='administrator'){
            $this->load->view('dashboard/template/menuAdministrator');
        }else if($this->session->userdata('user_type')=='coordinator'){
            $this->load->view('dashboard/template/menuCoordinator');
        }else{
            $this->load->view('dashboard/template/menuParticipant');
        }
        
		$this->load->view(
            'dashboard/paper/evaluate',
            array(
                'tgs' => $tgs,
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error')
            )
        );
        
        $this->load->view('dashboard/footer');
        
    }
    
    
    
    public function retrievePaperDetailsView(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'coordinator';
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
        
        $paper = R::findOne('paper','id=?',array($id));
        
        $data = array(
                'paper' => $paper
            );

		$this->load->view('dashboard/paper/retrievePaperDetails',$data);
        
    }


    /*
     * ACCEPT DO
    */
    public function accept(){
        
        
        /*
        * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'session',
                'rb',
                'email',
                'gomail'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url'
            ) 
        );
        
        
        /*
        * Loading user
        */
        $user = R::findOne(
            'user',
            'id=?',
            array(
                $this->session->userdata('user_id')
            )
        );
        

        /*
        * Verifying user's capabilities
        */
        $type = 'coordinator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if($u['type']!=$type)
            redirect(base_url('dashboard'));


        /*
        * Loading paper
        */
        $paper = R::findOne(
            'paper',
            'id=?',
            array(
                $this->input->post('id')
            )
        );
        
        
        /*
        * User can't evaluate their own paper
        */
        if( $paper->user->id == $user->id ) {
            
            $this->session->set_flashdata('error', 'Você não pode avaliar o próprio artigo.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
            
        }


        /*
        * If the article was evaluated
        */
        if($paper->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O artigo já foi avaliado.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
        }
  

        /*
        * Two or one evaluators
        */
        $config = R::findOne(
            'configuration',
            'name=?',
            array(
                'two_avaliations_paper'
            )
        );
        
        $sendEmail = false;
        
        if( $config->value == "true" ) { // Two evaluators
        
            if( $paper->avaliation1 == 'pending'  ){

                $paper->avaliation1 = 'accepted';
                $paper->avaliation1User = $user;
                R::store($paper);
                
            } else if ( $paper->avaliation2 == 'pending' ) {
                
                
                /* 
                 * If the user already did the avaliation
                */
                if( $paper->avaliation2UserId == $user->id )
                {
                    
                    $this->session->set_flashdata('error', 'Você já fez a avaliação deste artigo anteriormente.');
                    redirect(base_url('dashboard/paper/evaluate'));
                    exit;
                    
                }
                
                $paper->avaliation2 = 'accepted';
                $paper->avaliation2User = $user;
                $paper->evaluation = 'accepted';
                R::store($paper);
                
                $sendEmail = true;
                
            }
            
        } else { // One evaluator
            
            $paper->evaluation = 'accepted';
            R::store($paper);
            
            $sendEmail = true;
            
        }
        
        
        if( $sendEmail ) {
            
            
            /*
            * Send a confirmation email
            */
            $msg = "<h1 style='font-weight:bold;'>Seu artigo foi aceito, parabêns!</h1>";
            $msg .= "<h3>Seu artigo, $paper->title , foi aceito.</h3>";
            $msg .= "<p>Acompanhe as datas das normas e as notícias dos seminário para os próximos passos.</p>";
            $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
            
            
            $status = false;

            try {
                $status = $this->gomail->send_email(
                    'seminario@ccsa.ufrn.br', 
                    'Seminário de Pesquisa do CCSA', 
                    $paper->user->email, 
                    '[Artigo Aceito] Seminário de Pesquisa do CCSA', 
                    emailMsg($msg)
                );
            } catch (Exception $e) {
                
            }

            if(!$status){
                $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
                redirect(base_url('dashboard/paper/evaluate'));
                exit;
            }
            
        }

        $this->session->set_flashdata('success', 'O artigo foi avaliado como <b>aceito</b> com sucesso.');
        redirect(base_url('dashboard/paper/evaluate'));

    }
    
    
    /*
    * DEPRECATED
    */
    public function acceptAsPoster(){

        $this->load->library( array('session','rb','email','gomail') );
        $this->load->helper( array('url') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'coordinator';
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

        $paper = R::findOne('paper','id=?',array($id));

        // If the article was evaluated
        if($paper->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O artigo já foi avaliado.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
        }
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        $msg = "<h1 style='font-weight:bold;'>Seu artigo não foi aceito, porém ele ainda pode ser apresentado como pôster!</h1>";
        $msg .= "<h3>Você precisa comunicar-nos se deseja apresentá-lo ou não</h3>";
        $msg .= "<p>Para definir se deseja apresentar ou rejeitar a apresentação de seu artigo ($paper->title) como pôster, basta acessar o sistema do Seminário de Pesquisa, entrar com seu login e sua senha, ir em <b>Artigo</b> / <b>Artigos Submetidos</b>, procurar o artigo referido e selecionar a opção desejada.</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $status = false;
        
        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br', 
                'Seminário de Pesquisa do CCSA', 
                $paper->user->email, 
                '[Artigo Aceito como Pôster] Seminário de Pesquisa do CCSA', 
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

        $paper->evaluation = 'asPoster';
        R::store($paper);
        
        $this->session->set_flashdata('success', 'O artigo foi avaliado como <b>pôster</b> com sucesso.');
        redirect(base_url('dashboard/paper/evaluate'));

    }


    /*
     * User can't evaluate their own paper
    */
    public function reject(){


        /*
        * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'session',
                'rb',
                'form_validation',
                'email',
                'gomail'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url'
            ) 
        );


        /*
        * Loading user
        */
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));


        /*
        * User has capabilitites?
        */
        $type = 'coordinator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if($u['type']!=$type)
            redirect(base_url('dashboard'));

        
        /*
        * Form validation rules
        */
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
        

        /*
        * Verifying validation error
        */
        if(!$this->form_validation->run())
        {
            
            $this->session->set_flashdata('error','Para rejeitar um artigo, você precisa preencher o campo "observação". Repita a operação.');       
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
            
        }
        
        
        /*
        * User can't evaluate their own paper
        */
        if( $paper->user->id == $user->id ) 
        {
            
            $this->session->set_flashdata('error', 'Você não pode avaliar o próprio artigo.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
            
        }


        /*
        * Getting post data
        */
        $id = $this->input->post('id');
        $evaluation_observation = $this->input->post('observation');


        /*
        * Loading paper
        */
        $paper = R::findOne('paper','id=?',array($id));


        /*
        * If the article was evaluated
        */
        if( $paper->evaluation!='pending' )
        {
            
            $this->session->set_flashdata('error', 'O artigo já foi avaliado.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
            
        }
        
        
        /*
        * Seding email
        */

        $msg = "<h1 style='font-weight:bold;'>Seu artigo não foi aceito!</h1>";
        $msg .= "<h3>Seu artigo, $paper->title, não foi aceito na avaliação.</h3>";
        $msg .= "<p>$paper->evaluationobservation</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $status = false;
        
        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br', 
                'Seminário de Pesquisa do CCSA', 
                $paper->user->email, 
                '[Artigo Rejeitado] Seminário de Pesquisa do CCSA', 
                emailMsg($msg)
            );
        } catch (Exception $e) {
            
        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/paper/evaluate'));
            exit;
        }
        

        /*
        * Rejecting the paper
        */
        $paper->evaluation = 'rejected';
        $paper->evaluationobservation = $evaluation_observation;
        R::store($paper);
        

        /*
        * Confirmation message
        */
        $this->session->set_flashdata('success', 'O artigo foi avaliado como <b>rejeitado</b> com sucesso.');
        redirect(base_url('dashboard/paper/evaluate'));

    }
    
    
    /*
    * DEPRECATED
    */
    public function userAcceptAsPoster(){
        
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */

        $id = $this->input->post('id');

        $paper = R::findOne('paper','id=?',array($id));

        // USER HAS CAPABILITIES TO CHANGE THIS PAPER?
        if($paper->user->id!=$this->session->userdata('user_id')){
            $this->session->set_flashdata('error', 'Você não tem autorização para realizar esta operação.');
            redirect(base_url('dashboard/paper/submit'));
            exit;
        }

        $paper->asposter = 'accepted';
        R::store($paper);

        $this->session->set_flashdata('success', 'Você aceitou apresentar o artigo como pôster.');
        redirect(base_url('dashboard/paper/submit'));
    
    }
    
    public function userRejectAsPoster(){
        
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }

        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */

        $id = $this->input->post('id');

        $paper = R::findOne('paper','id=?',array($id));

        // USER HAS CAPABILITIES TO CHANGE THIS PAPER?
        if($paper->user->id!=$this->session->userdata('user_id')){
            $this->session->set_flashdata('error', 'Você não tem autorização para realizar esta operação.');
            redirect(base_url('dashboard/paper/submit'));
            exit;
        }

        $paper->asposter = 'rejected';
        R::store($paper);

        $this->session->set_flashdata('success', 'Você não aceitou apresentar o artigo como pôster.');
        redirect(base_url('dashboard/paper/submit'));
        
    }
    
    
    
    public function cancelSubmission(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url') );

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
        
        $id = $this->input->post('id');

        $paper = R::findOne('paper','id=?',array($id));

        // If the article was evaluated
        if($paper->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O artigo já foi avaliado. Não se pode remover um artigo que já foi avaliado.');
            redirect(base_url('dashboard/paper/submit'));
            exit;
        }

        R::trash($paper);

        $this->session->set_flashdata('success', 'A submissão do artigo foi <b>cancelada</b> com sucesso.');
        redirect(base_url('dashboard/paper/submit'));
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */