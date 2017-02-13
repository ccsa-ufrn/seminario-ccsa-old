<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TeachingCases extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }
    
    public function submitView(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');

        if(!$userLogged)
        	redirect(base_url('dashboard/login'));
        
        $config = R::findOne('configuration','name=?',array('max_date_teachingcases_submission'));
        
        if(dateleq(mdate('%Y-%m-%d'),$config->value))
            $open = true;
        else
            $open = false;
            
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
            
        $paid = true;     
       
        if( $user->paid == 'no' )
            $paid = false;
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'tgs' => R::find('thematicgroup','ORDER BY name ASC'),
                    'teachingcases' => R::find('teachingcase','user_id=?',array($this->session->userdata('user_id'))),
                    'active' => 'submit-teaching-cases',
                    'date_limit' => array( 'config' => $config , 'open' => $open ),
                    'paid' => $paid
                );


        $this->load->view('dashboard/header');

        if($this->session->userdata('user_type')=='administrator'){
            $this->load->view('dashboard/template/menuAdministrator');
        }else{
            if($this->session->userdata('user_type')=='instructor')
                $this->load->view('dashboard/template/menuInstructor',$data);
            else if( $this->session->userdata('user_type') == 'student' || $this->session->userdata('user_type') == 'noacademic' )
                $this->load->view('dashboard/template/menuStudent',$data);
            else if($this->session->userdata('user_type')=='coordinator')
                $this->load->view('dashboard/template/menuCoordinator',$data);
            else{
                echo "No permission to access this page.";
                exit;
            }
        }

		$this->load->view('dashboard/teachingcases/submit',$data);
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
     
        $this->load->library( array('session','rb','email') );
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
        
        $tgs = $user->with( ' ORDER BY name ASC ' )->sharedThematicgroupList;
        
        $data = array(
                'tgs' => $tgs,
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
        
		$this->load->view('dashboard/teachingcases/evaluate',$data);
        $this->load->view('dashboard/footer');
        
    }
    
}

/* End of file teachingcases.php */