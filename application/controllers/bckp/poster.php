<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poster extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }
    
    public function uploadPoster(){
        
        $this->load->library( array('rb') );
        $this->load->helper( array('string') );
        
        $config['upload_path'] = './assets/upload/posters/';
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

	public function submitView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');

        if(!$userLogged)
        	redirect(base_url('dashboard/login'));
        
        $config = R::findOne('configuration','name=?',array('max_date_poster_submission'));
        
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
                    'posters' => R::find('poster','user_id=?',array($this->session->userdata('user_id'))),
                    'active' => 'submit-poster',
                    'date_limit' => array( 'config' => $config , 'open' => $open ),
                    'paid' => $paid
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

        $this->load->view('dashboard/poster/submit', $data);
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

        $this->load->library( array('session','rb', 'form_validation') );
        $this->load->helper( array('url','form','date') );
            
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
            
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $userId = $this->session->userdata('user_id');
        
        /* ===========================================
            BEGIN - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
        $config = R::findOne('configuration','name=?',array('max_date_poster_submission'));
        
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
                'field' => 'poster',
                'label' => 'Pôster',
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
                            'poster' => form_error('poster'),
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
                            'poster' => set_value('poster'),
                            'keywords' => form_error('keywords')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');   
            redirect(base_url('dashboard/poster/submit'));
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
        $poster = $this->input->post('poster');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // Retrieving thematicgroup
        $tg = R::findOne('thematicgroup','id=?',array($tgid));

        $p = R::dispense('poster');
        
        $p['title'] = $title;
        $p['authors'] = $authors;
        $p['abstract'] = $abstract;
        $p['keywords'] = $keywords;
        $p['poster'] = $poster;
        $p['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $p['user'] = $user;
        $p['evaluation'] = 'pending';
        $p['cernn'] = 'pending';
        $p['scheduled'] = 'no';
        $p['thematicgroup'] = $tg;

        $id = R::store($p);

        $this->session->set_flashdata('success','O pôster foi submetido para avalição com sucesso, você será notificado em breve.');
        redirect(base_url('dashboard/poster/submit'));
        exit;

    }

    public function evaluateView(){

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
        
        $this->load->view('dashboard/poster/evaluate',$data);
        $this->load->view('dashboard/footer');

    }

    public function retrievePosterDetailsView(){
        
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
        
        $poster = R::findOne('poster','id=?',array($id));
        
        $data = array(
                'poster' => $poster
            );

        $this->load->view('dashboard/poster/retrievePosterDetails',$data);
        
    }

    public function accept(){

        $this->load->library( array('session','rb','email') );
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

        $poster = R::findOne('poster','id=?',array($id));

        // If the article was evaluated
        if($poster->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O pôster já foi avaliado.');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        $this->email->from('seminario@ccsa.ufrn.br', 'Seminário de Pesquisa do CCSA');
        $this->email->to($poster->user->email); 

        $this->email->subject('[Pôster Aceito] Seminário de Pesquisa do CCSA');

        $msg = "<h1 style='font-weight:bold;'>Seu pôster foi aceito, parabêns!</h1>";
        $msg .= "<h3>Seu pôster, $poster->title , foi aceito.</h3>";
        $msg .= "<p>Acompanhe as datas das normas e as notícias dos seminário para os próximos passos.</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $this->email->message(emailMsg($msg)); // Simple message to test
        
        $status = false;
        
        try {
            $status = $this->email->send();
        } catch (Exception $e) {
            
        }

        if(!$status){
            $this->session->set_flashdata('error', 'O servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        
        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        $poster->evaluation = 'accepted';
        R::store($poster);

        $this->session->set_flashdata('success', 'O pôster foi avaliado como <b>aceito</b> com sucesso.');
        redirect(base_url('dashboard/poster/evaluate'));

    }

    public function reject(){

        $this->load->library( array('session','rb','form_validation','email') );
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
            $this->session->set_flashdata('error','Para reijeitar um pôster, você precisa preencher o campo "observação". Repita a operação.');       
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */

        $id = $this->input->post('id');
        $evaluation_observation = $this->input->post('observation');

        $poster = R::findOne('poster','id=?',array($id));

        // If the article was evaluated
        if($poster->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O pôster já foi avaliado.');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        
        /* ===========================================
            BEGIN - SENDING EMAIL CONFIRMATION
        ============================================ */
        
        $this->email->from('seminario@ccsa.ufrn.br', 'Seminário de Pesquisa do CCSA');
        $this->email->to($poster->user->email); 

        $this->email->subject('[Pôster Rejeitado] Seminário de Pesquisa do CCSA');

        $msg = "<h1 style='font-weight:bold;'>Seu pôster não foi aceito!</h1>";
        $msg .= "<h3>Seu pôster, $poster->title, não foi aceito na avaliação.</h3>";
        $msg .= "<p>$poster->evaluationobservation</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        
        $this->email->message(emailMsg($msg)); // Simple message to test
        
        $status = false;
        
        try {
            $status = $this->email->send();
        } catch (Exception $e) {
            
        }

        if(!$status){
            $this->session->set_flashdata('error', 'O servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        
        /* ===========================================
            END - SENDING EMAIL CONFIRMATION
        ============================================ */

        $poster->evaluation = 'rejected';
        $poster->evaluationobservation = $evaluation_observation;
        R::store($poster);

        $this->session->set_flashdata('success', 'O pôster foi avaliado como <b>rejeitado</b> com sucesso.');
        redirect(base_url('dashboard/poster/evaluate'));

    }
    
    public function cancelSubmission(){
        
        $this->load->library( array('session','rb','form_validation','email') );
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

        $poster = R::findOne('poster','id=?',array($id));

        // If the article was evaluated
        if($poster->evaluation!='pending'){
            $this->session->set_flashdata('error', 'O pôster já foi avaliado. Não se pode remover um pôster que já foi avaliado.');
            redirect(base_url('dashboard/poster/submit'));
            exit;
        }

        R::trash($poster);

        $this->session->set_flashdata('success', 'A submissão do pôster foi <b>cancelada</b> com sucesso.');
        redirect(base_url('dashboard/poster/submit'));
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */