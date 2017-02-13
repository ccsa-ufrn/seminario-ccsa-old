<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TeachingCases extends Base 
{
    
    public function getTeachingCase() {
        $this->load->library(['rb']);
        
        $teachingcases = R::find('teachingcase', 'evaluation = "accepted" ');
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
		$this->load->view(
            'dashboard/teachingcases/getp',
            ['teachingcases' => $teachingcases]
        );
        $this->load->view('dashboard/footer');
    }

    /*
     * Function : submitView()
     * Description : View to submit teaching cases
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
            ' name= "max_date_teachingcases_submission" '
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

        if ( $this->session->userdata('user_type') == 'administrator' ) :
        
            $this->load->view('dashboard/template/menuAdministrator');
            
        else : 
        
            if ( $this->session->userdata('user_type') == 'instructor' ) :
            
                $this->load->view(
                    'dashboard/template/menuInstructor',
                    array(
                        'active' => 'submit-teaching-cases'
                    )
                );
                
            elseif ( $this->session->userdata('user_type') == 'student' || $this->session->userdata('user_type') == 'noacademic' ) :
            
                $this->load->view(
                    'dashboard/template/menuStudent',
                    array(
                        'active' => 'submit-teaching-cases'
                    )
                );
                
            elseif ($this->session->userdata('user_type') == 'coordinator' ) :
            
                $this->load->view(
                    'dashboard/template/menuCoordinator',
                    array(
                        'active' => 'submit-teaching-cases'
                    )
                );
                
            else :
            
                echo "No permission to access this page.";
                exit;
                
            endif;
            
        endif;

		$this->load->view(
            'dashboard/teachingcases/submit',
            array(
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error'),
                'validation' => $this->session->flashdata('validation'),
                'popform' => $this->session->flashdata('popform'),
                'teachingcases' => R::find('teachingcase','user_id=?',array($this->session->userdata('user_id'))),
                'date_limit' => array( 'config' => $config , 'open' => $open ),
                'paid' => $paid
            )
        );
        
        $this->load->view('dashboard/footer');
        
    }
    
    public function uploadTeachingCase(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('string') );
        
        $config['upload_path'] = './assets/upload/teachingcases/';
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
    
    public function create(){
        
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $userId = $this->session->userdata('user_id');
        
        /* ===========================================
            BEGIN - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
        $config = R::findOne('configuration','name=?',array('max_date_teachingcases_submission'));
        
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
                'field' => 'teachingcase',
                'label' => 'Caso de Ensino',
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
                            'keywords' => form_error('keywords')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'thematicgroup' => set_value('thematicgroup'),
                            'authors' => set_value('authors'),
                            'abstract' => set_value('abstract'),
                            'keywords' => set_value('keywords')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');            
            redirect(base_url('dashboard/teachingcases/submit'));
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
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // Retrieving thematicgroup
        $tg = R::findOne('thematicgroup','id=?',array($tgid));

        $tc = R::dispense('teachingcase');
        
        $tc['title'] = $title;
        $tc['authors'] = $authors;
        $tc['abstract'] = $abstract;
        $tc['keywords'] = $keywords; 
        $tc['evaluation'] = 'pending'; 
        $tc['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $tc['user'] = $user;
        $tc['scheduled'] = 'no';
        $tc['cernn'] = 'pending';
        $tc['thematicgroup'] = $tg;

        $id = R::store($tc);
        
        $this->session->set_flashdata('success','O caso de ensino foi submetido para avalição com sucesso, você será notificado em breve.');
        redirect(base_url('dashboard/teachingcases/submit'));
        exit;
        
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

        $tc = R::findOne('teachingcase','id=?',array($id));

        // If the article was evaluated
        if($tc->evaluation != 'pending'){
            $this->session->set_flashdata('error', 'O caso de ensino já foi avaliado. Não se pode remover um caso de ensino que já foi avaliado.');
            redirect(base_url('dashboard/teachingcases/submit'));
            exit;
        }

        R::trash($tc);

        $this->session->set_flashdata('success', 'A submissão do caso de ensino foi <b>cancelado</b> com sucesso.');
        redirect(base_url('dashboard/teachingcases/submit'));
        
    }
    
    public function evaluateView(){
     
        $this->load->library( array('session','rb','email', 'gomail') );
        $this->load->helper( array('url','form') );
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        // Can evaluate?
        
        $canEvaluateTeachingcase = count(
            $user
            ->withCondition('name="Casos para Ensino"')
            ->sharedThematicgroupList 
        );
        
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
        
        $tcs = R::find('teachingcase',' user_id != ? AND evaluation="pending" ', array( $u->id ));
        
        $data = array(
                'tcs' => $tcs,
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error')
            );

        $this->load->view('dashboard/header');

        if($this->session->userdata('user_type')=='administrator'){
            $this->load->view('dashboard/template/menuAdministrator');
        }else if($this->session->userdata('user_type')=='coordinator'){
            $this->load->view('dashboard/template/menuCoordinator');
        }else{
            $this->load->view('dashboard/template/menuParticipant');
        }
        
        if ( ! $canEvaluateTeachingcase ) : 
        
            echo "<script> alert('Você não está autorizado para avaliar Casos para Ensino') </script>";
            
        else :
        
            $this->load->view('dashboard/teachingcases/evaluate',$data);
            
        endif;
		
        $this->load->view('dashboard/footer');
        
    }
    
    public function retrieveView(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        $canEvaluateTeachingcase = count(
            $user
            ->withCondition(' name = "Casos para Ensino" ')
            ->sharedThematicgroupList 
        );
        
        if( ! $canEvaluateTeachingcase ) : 
        
            echo "Você não tem autorização para realizar este procedimento.";
            exit;
        
        endif;
        
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
        
        $tc = R::findOne('teachingcase','id=?',array($id));
        
        $data = array(
                'tc' => $tc
            );

		$this->load->view('dashboard/teachingcases/retrieveTeachingCaseDetails',$data);
        
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
        
        $canEvaluateTeachingcase = count(
            $user
            ->withCondition(' name = "Casos para Ensino" ')
            ->sharedThematicgroupList 
        );
        
        if( ! $canEvaluateTeachingcase ) : 
        
            echo "Você não tem autorização para realizar este procedimento.";
            exit;
        
        endif;
        

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
        * Loading teaching case
        */
        $tc = R::findOne(
            'teachingcase',
            'id=?',
            array(
                $this->input->post('id')
            )
        );
        
        
        /*
        * User can't evaluate their own teaching case
        */
        if( $tc->user->id == $user->id ) {
            
            $this->session->set_flashdata('error', 'Você não pode avaliar o próprio caso para ensino.');
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
            
        }


        /*
        * If the teaching case was evaluated
        */
        if($tc->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O caso para ensino já foi avaliado.');
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
        }
        
        $tc->evaluation = 'accepted';
        R::store($tc);      
            
        /*
        * Send a confirmation email
        */
        $msg = "<h1 style='font-weight:bold;'>Seu caso para ensino foi aceito, parabêns!</h1>";
        $msg .= "<h3>Seu artigo, $tc->title , foi aceito.</h3>";
        $msg .= "<p>Acompanhe as datas das normas e as notícias dos seminário para os próximos passos.</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $status = false;
        
        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br', 
                'Seminário de Pesquisa do CCSA', 
                $tc->user->email, 
                '[Caso para Ensino Aceito] Seminário de Pesquisa do CCSA', 
                emailMsg($msg)
            );
        } catch (Exception $e) {
            
        }

        if(!$status){
            $this->session->set_flashdata('error', 'Parece que o servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
        }
            
        $this->session->set_flashdata('success', 'O caso para ensino foi avaliado como <b>aceito</b> com sucesso.');
        redirect(base_url('dashboard/teachingcases/evaluate'));

    }
    
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


        $canEvaluateTeachingcase = count(
            $user
            ->withCondition(' name = "Casos para Ensino" ')
            ->sharedThematicgroupList 
        );
        
        if( ! $canEvaluateTeachingcase ) : 
        
            echo "Você não tem autorização para realizar este procedimento.";
            exit;
        
        endif;
        
        
        /*
         * Checking capabilities
        */
        $type = 'coordinator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if($u['type']!=$type)
            redirect(base_url('dashboard'));

        
        /*
         * Validation rules
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
         * Validating
        */
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Para reijeitar um caso para ensino, você precisa preencher o campo "observação". Repita a operação.');       
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
        }


        $id = $this->input->post('id');
        $evaluation_observation = $this->input->post('observation');

        $tc = R::findOne('teachingcase','id=?',array($id));
        
        /*
         * Verifying if it's not a user teaching case
        */
        if( $tc->user->id == $tc->id ){
            
            $this->session->set_flashdata('error', 'Você não pode avaliar o próprio caso para ensino');
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
            
        }

        /*
         * If the article was teaching case
        */
        if($tc->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O caso para ensino já foi avaliado.');
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
        }
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        $msg = "<h1 style='font-weight:bold;'>Seu caso para ensino não foi aceito!</h1>";
        $msg .= "<h3>Seu pôster, $tc->title, não foi aceito na avaliação.</h3>";
        $msg .= "<p>$tc->evaluationobservation</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $status = false;
        
        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br', 
                'Seminário de Pesquisa do CCSA', 
                $tc->user->email, 
                '[Caso para Ensino Rejeitado] Seminário de Pesquisa do CCSA', 
                emailMsg($msg)
            );
        } catch (Exception $e) {
            
        }

        if(!$status){
            $this->session->set_flashdata('error', 'O servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/teachingcases/evaluate'));
            exit;
        }
        
        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        $tc->evaluation = 'rejected';
        $tc->evaluationobservation = $evaluation_observation;
        R::store($tc);

        $this->session->set_flashdata('success', 'O caso para ensino foi avaliado como <b>rejeitado</b> com sucesso.');
        redirect(base_url('dashboard/teachingcases/evaluate'));

    }
    
    
}

/* End of file teachingcases.php */