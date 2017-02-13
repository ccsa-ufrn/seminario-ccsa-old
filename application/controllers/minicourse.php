<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Minicourse extends CI_Controller {
    
    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }

    public function retrieveEditInfo(){

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

        // Retrieving content
        $mcId = $this->input->get('id');
        
        if(!is_numeric($mcId)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $mc = R::findOne('minicourse','id=?',array($mcId));
        $dss = R::find('minicoursedayshift','ORDER BY date ASC');

        $data = array( 
            'mc' => $mc,
            'dss' => $dss
            );
        
        $this->load->view('dashboard/minicourse/retrieveEditInfo', $data);

    }

    public function updateConsolidated(){

        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
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
        
        // Retrieving user
        $user = $u;

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'vacancies',
                'label' => 'Quantidade de Vagas',
                'rules' => 'numeric|is_natural_no_zero|required'
            ),
            array(
                'field' => 'local',
                'label' => 'Local',
                'rules' => 'required'
            ),
            array(
                'field' => 'dayshifts',
                'label' => 'Dia/Turno',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Todos os campos precisam estar preenchidos e corretos.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $id = $this->input->post('id');
        $vacancies = $this->input->post('vacancies');
        $local = $this->input->post('local');
        $dayshifts = $this->input->post('dayshifts');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Retriving minicourse
        $mc = R::findOne('minicourse','id=?',array($id));

        // Verifying if there is any day for the course
        if(count($dayshifts)==0 || $dayshifts[0]=="-1"){
            $this->session->set_flashdata('error','Não há dias/turnos disponíveis para este minicurso.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        if($mc['consolidatedvacanciesfilled'] > $vacancies){
            $this->session->set_flashdata('error','Não foi possível atualizar, pois a quantidade de inscrições supera a nova quantidade de vagas. Contacte o administrador.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }

        $mc['consolidatedvacancies'] = $vacancies;
        $mc['consolidatedlocal'] = $local;
        $mc['consolidated'] = 'yes';
        $mc->sharedMinicoursedayshiftList = array(); // Errado

        $id = R::store($mc);
        
        // Relation with dayshift

        for($i=0;$i<count($dayshifts);++$i){

            $ds = R::findOne('minicoursedayshift','id=?',array($dayshifts[$i]));
            $ds->sharedMinicourseList[] = $mc;
            R::store($ds);

        }

        $this->session->set_flashdata('success','O minicurso foi atualizado com sucesso.');
        redirect(base_url('dashboard/minicourse/manage'));
        exit;

    }
    
    public function index(){
        
        // Manage Minicourses

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $this->load->view('dashboard/header');

        if($this->session->userdata('user_type')=='administrator'){
            $this->load->view('dashboard/template/menuAdministrator');
        }else if($this->session->userdata('user_type')=='coordinator'){
            $this->load->view('dashboard/template/menuCoordinator');
        }else{
            $this->load->view('dashboard/template/menuParticipant');
        }

        $this->load->view('dashboard/createMiniCourse');
        $this->load->view('dashboard/footer');

	}


    /* 
     * Function : createReport()
     * Description : Create a report
    */
    public function createReport()
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
            utf8_decode( 'Lista de Minicurso' ), 
            0, 
            0,
            'C'
        );
        
        /* *********************************************************
        * END - HEADER
        ********************************************************* */
        
        $minicourseid = $this->input->post('minicourse');
        $lista = $this->input->post('list');
        
        /* 
         * Loading User
        */
        $minicourse = R::findOne(
            'minicourse',
            ' id = ? ',
            array( 
                $minicourseid
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
            utf8_decode( strtoupper ( $minicourse->title ) ), 
            'LRT',
            0,
            'C',
            true
        ); 

        
        $pdf->Ln(10);
        
        $pdf->SetFillColor(255,255,255);
        
        $pdf->Cell(
            26, 
            7, 
            utf8_decode( 'Local/Vagas: ' ), 
            'LTB',
            0,
            'L',
            false
        ); 
        
        $pdf->Cell(
            251, 
            7, 
            utf8_decode($minicourse->consolidatedlocal.' - '.$minicourse->consolidatedvacancies.' vagas '), 
            'TBR',
            0,
            'L',
            false
        ); 
        
        $pdf->Ln(7);
        
        /* Gerando String de Expositores */
        $conj = $minicourse->expositor;
        $conj = explode('||', $conj);
        $conj = implode(', ', $conj);
        
        $pdf->MultiCell(
            277, 
            7, 
            utf8_decode('Expositores: '.$conj), 
            'LBR',
            'L',
            false
        ); 

        $pdf->Ln(10);
        
        
        /* 
         * Students
        */
        $pdf->Ln(10);
        
        $result = $minicourse->with('ORDER BY name ASC')->sharedUserList;
        
        if ( $lista === 'list' ) : 
        
            /*
             * HEADER
            */
            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(244,244,244);
            
            $pdf->Cell(
                140, 
                10, 
                utf8_decode( 'Nome' ), 
                'LRTB',
                0,
                'C',
                true
            ); 
            
            $pdf->Cell(
                137, 
                10, 
                utf8_decode( 'Assinatura' ), 
                'LRTB',
                0,
                'C',
                true
            ); 
            
           foreach ( $result as $i ) : 
           
                $pdf->Ln(10);
           
                $pdf->SetDrawColor(170,170,170);
                
                $pdf->Cell(
                    140, 
                    10, 
                    utf8_decode( titleCase($i->name) ), 
                    'LRTB',
                    0,
                    'C',
                    false
                ); 
                
                $pdf->Cell(
                    137, 
                    10, 
                    utf8_decode(''), 
                    'LRTB',
                    0,
                    'C',
                    false
                ); 
            
            endforeach; 
        
        else : 
        
            /*
             * HEADER
            */
            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(244,244,244);
            
            $pdf->Cell(
                130, 
                10, 
                utf8_decode( 'Nome' ), 
                'LRTB',
                0,
                'C',
                true
            ); 
            
            $pdf->Cell(
                90, 
                10, 
                utf8_decode( 'Email' ), 
                'LRTB',
                0,
                'C',
                true
            ); 
            
            $pdf->Cell(
                57, 
                10, 
                utf8_decode( 'Telefone' ), 
                'LRTB',
                0,
                'C',
                true
            ); 
            
           foreach ( $result as $i ) : 
           
                $pdf->Ln(10);
           
                $pdf->SetDrawColor(170,170,170);
                
                $pdf->Cell(
                    130, 
                    10, 
                    utf8_decode( titleCase($i->name) ), 
                    'LRTB',
                    0,
                    'C',
                    false
                ); 
                
                $pdf->Cell(
                    90, 
                    10, 
                    utf8_decode( $i->email ), 
                    'LRTB',
                    0,
                    'C',
                    false
                ); 
                
                
                $pdf->Cell(
                    57, 
                    10, 
                    utf8_decode( $i->phone), 
                    'LRTB',
                    0,
                    'C',
                    false
                ); 
            
            endforeach;
        
        endif;
        
        $pdf->Output();

    }

    public function myMinicoursesView()
    {
        $this->load->library(array('session','rb'));
        $this->load->helper(array('url','form'));
        
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
            
        $user = R::findOne('user', 'id = ?', array($this->session->userdata('user_id')));
            
        $minicourses = $user->with('ORDER BY title ASC')->ownMinicourseList;
        $workshops = $user->with('ORDER BY title ASC')->ownWorkshopList;
        $roundtables = $user->with('ORDER BY title ASC')->ownRoundtableList;
            
        $data = array(
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'minicourses' => $minicourses,
            'workshops' => $workshops,
            'roundtables' => $roundtables,
            'active' => 'my-minicourses'
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
                $this->load->view('dashboard/template/menuStudent', $data);
        }
        
        $this->load->view('dashboard/minicourse/myMinicourses', $data);
        
        $this->load->view('dashboard/footer');
        
    }
    

    public function reportView()
    {
    
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
        $minicourses = R::find('minicourse',' consolidated="yes" ORDER BY title ASC ');
        
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
                    'minicourses' => $minicourses
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/minicourse/report',$data);
        $this->load->view('dashboard/footer');

    }

	public function submitView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );
        
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $config = R::findOne('configuration','name=?',array('max_date_minicourse_submission'));
        
        if(dateleq(mdate('%Y-%m-%d'),$config->value))
            $open = true;
        else
            $open = false;
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'minicourses' => R::find('minicourse','user_id=?',array($this->session->userdata('user_id'))),
                    'active' => 'submit-minicourse',
                    'date_limit' => array( 'config' => $config , 'open' => $open )
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

        $this->load->view('dashboard/minicourse/submit',$data);
        $this->load->view('dashboard/footer');

	}
    
    public function create(){
        
        $this->load->library( array('session','rb', 'form_validation') );
        $this->load->helper( array('url','form','date') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $userId = $this->session->userdata('user_id');
        
        /* ===========================================
            BEGIN - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
        $config = R::findOne('configuration','name=?',array('max_date_minicourse_submission'));
        
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
                'field' => 'shift',
                'label' => 'Turno',
                'rules' => 'required'
            ),
            array(
                'field' => 'syllabus',
                'label' => 'Ementa',
                'rules' => 'required'
            ),
            array(
                'field' => 'objectives',
                'label' => 'Objetivos',
                'rules' => 'required'
            ),
            array(
                'field' => 'resources',
                'label' => 'Recursos',
                'rules' => 'required'
            ),
            array(
                'field' => 'program',
                'label' => 'Programa',
                'rules' => 'required'
            ),
            array(
                'field' => 'vacancies',
                'label' => 'Quantidade de Vagas',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'authors',
                'label' => 'Expositor(es)',
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
                            'shift' => form_error('shift'),
                            'syllabus' => form_error('syllabus'),
                            'objectives' => form_error('objectives'),
                            'resources' => form_error('resources'),
                            'program' => form_error('program'),
                            'vacancies' => form_error('vacancies'),
                            'authors' => form_error('authors')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'shift' => set_value('shift'),
                            'syllabus' => set_value('syllabus'),
                            'objectives' => set_value('objectives'),
                            'resources' => set_value('resources'),
                            'program' => set_value('program'),
                            'vacancies' => set_value('vacancies'),
                            'authors' => set_value('authors')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');   
            redirect(base_url('dashboard/minicourse/submit'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $title = $this->input->post('title');
        $shift = $this->input->post('shift');
        $syllabus = $this->input->post('syllabus'); // ementa
        $objectives = $this->input->post('objectives');
        $resources = $this->input->post('resources');
        $program = $this->input->post('program');
        $vacancies = $this->input->post('vacancies');
        $expositors = $this->input->post('authors');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $mc = R::dispense('minicourse');
        
        $mc['title'] = $title;
        $mc['shift'] = $shift;
        $mc['syllabus'] = $syllabus;
        $mc['objectives'] = $objectives;
        $mc['resources'] = $resources;
        $mc['program'] = $program; 
        $mc['vacancies'] = $vacancies;
        $mc['expositor'] = $expositors;
        $mc['cernn'] = 'pending';
        $mc['consolidated'] = 'no';
        $mc['scheduled'] = 'no';
        $mc['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $mc['user'] = $user;

        $id = R::store($mc);

        $this->session->set_flashdata('success','O minicurso foi submetido para avalição com sucesso, você será notificado em breve.');
        redirect(base_url('dashboard/minicourse/submit'));
        exit;

	}
    
    public function uploadProgram(){

        $this->load->library( array('rb') );
        $this->load->helper( array('string') );
        
        $config['upload_path'] = './assets/upload/';
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

        //echo "Em manutenção para correção de alguns detalhes, disponível novamente em 18/03.";
        //exit;
        
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
        
        $mcdss = R::find('minicoursedayshift','ORDER BY date ASC');
        
        if($filter=='consolidated'){
            $mcs = R::find('minicourse',' consolidated="yes" ');
        }else if($filter=='noconsolidated'){
            $mcs = R::find('minicourse',' consolidated="no" ');
        }else{
            $mcs = R::find('minicourse');
        }
        
        
        $data = array( 
            'mcdss' => $mcdss,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => $mcs
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/minicourse/manage',$data);
        $this->load->view('dashboard/footer');

    }
    
    public function createDayShift(){
        
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
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
                'field' => 'day',
                'label' => 'Dia',
                'rules' => 'numeric|is_natural_no_zero|greater_than[0]|less_than[32]|required'
            ),
            array(
                'field' => 'shift',
                'label' => 'Turno',
                'rules' => 'required'
            ),
            array(
                'field' => 'month',
                'label' => 'Mês',
                'rules' => ''
            ),
            array(
                'field' => 'year',
                'label' => 'Ano',
                'rules' => 'numeric|is_natural_no_zero|exact_length[4]|required'
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
                            'day' => form_error('day'),
                            'year' => form_error('year')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'day' => set_value('day'),
                            'month' => set_value('month'),
                            'shift' => set_value('shift'),
                            'year' => set_value('year')
                        )
                );
            
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $day = $this->input->post('day');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $shift = $this->input->post('shift');
        $date = $year."-".sprintf("%02s", $month)."-".sprintf("%02s", $day);

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Verifying if it's a valid date
        if(!checkdate($month,$day,$year)){
            $this->session->set_flashdata('error','Data não válida.');
            $this->session->set_flashdata(
                'popform', 
                array(
                        'day' => set_value('day'),
                        'month' => set_value('month'),
                        'shift' => set_value('shift'),
                        'year' => set_value('year')
                    )
            );
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }

        // Verifying that will not be any collision
        $v = R::find('minicoursedayshift','date=? AND shift=?', array($date,$shift));
        
        if(count($v)){
            $this->session->set_flashdata('error','Combinação de dia e turno já existe no calendário.');
            $this->session->set_flashdata(
                'popform', 
                array(
                        'day' => set_value('day'),
                        'month' => set_value('month'),
                        'shift' => set_value('shift'),
                        'year' => set_value('year')
                    )
            );
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }

        $mcds = R::dispense('minicoursedayshift');
        
        $mcds['date'] = $date;
        $mcds['shift'] = $shift;
        $mcds['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        $id = R::store($mcds);

        $this->session->set_flashdata('success','Dia/Turno adicionado no calendário.');
        redirect(base_url('dashboard/minicourse/manage'));
        exit;
        
    }
    
    public function retrieveConsolidationView(){
        
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

        // Retrieving content
        $mcId = $this->input->get('id');
        
        if(!is_numeric($mcId)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $mc = R::findOne('minicourse','id=?',array($mcId));

        // Retrieving days and shifts availables
        $dss = R::find('minicoursedayshift','ORDER BY date ASC');

        $data = array( 
            'mc' => $mc,
            'dss' => $dss
            );
        
        $this->load->view('dashboard/minicourse/retrieveConsolidation', $data);
        
    }
    
    public function consolidate(){
        
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
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
        
        // Retrieving user
        $user = $u;

        /* ===========================================
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'vacancies',
                'label' => 'Quantidade de Vagas',
                'rules' => 'numeric|is_natural_no_zero|required'
            ),
            array(
                'field' => 'local',
                'label' => 'Local',
                'rules' => 'required'
            ),
            array(
                'field' => 'dayshifts',
                'label' => 'Dia/Turno',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata('error','Você precisa preencher todos os dados corretamente. Repita a operação.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $id = $this->input->post('id');
        $vacancies = $this->input->post('vacancies');
        $local = $this->input->post('local');
        $dayshifts = $this->input->post('dayshifts');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Retriving minicourse
        $mc = R::findOne('minicourse','id=?',array($id));
        
        // Can't continue if the minicourse is consolidated
        if($mc->consolidated=="yes"){
            $this->session->set_flashdata('error','Este minicurso já está consolidado.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }

        // Verifying if there is any day for the course
        if(count($dayshifts)==0 || $dayshifts[0]=="-1"){
            $this->session->set_flashdata('error','Não há dias/turnos disponíveis para este minicurso.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        $mc['consolidatedvacancies'] = $vacancies;
        $mc['consolidatedlocal'] = $local;
        $mc['consolidated'] = 'yes';
        $id = R::store($mc);
        
        // Relation with dayshift

        for($i=0;$i<count($dayshifts);++$i){

            $ds = R::findOne('minicoursedayshift','id=?',array($dayshifts[$i]));
            $ds->sharedMinicourseList[] = $mc;
            R::store($ds);

        }

        $this->session->set_flashdata('success','O minicurso foi consolidado com sucesso.');
        redirect(base_url('dashboard/minicourse/manage'));
        exit;
        
    }

    public function retrieveConfirmOperation(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','utility','date') );
        
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

        $status = 'ok';

        $sdate = R::findOne('configuration','name=?',array('start_date_minicourse_inscription'));
        $edate = R::findOne('configuration','name=?',array('end_date_minicourse_inscription'));

        if(!dateleq(mdate('%Y-%m-%d'),$edate->value)){ // Maior ou igual que a data final das inscrições
            // Não permite desconsolidação
            echo 'Não é possível <b>desconsolidar</b> quando o prazo de inscrições já se encerrou.';
            exit;

        }else if(datebeq(mdate('%Y-%m-%d'),$sdate->value)){ // Caso seja maior ou igual a data do inicio das inscrições e menor ou igual a data final
            // Permite desconsolidação com aviso
            $status = 'warning';
        }
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $mc = R::findOne('minicourse','id=?',array($id));
        
        $data = array( 
            'mc' => $mc,
            'status' => $status
            );
        
        $this->load->view('dashboard/minicourse/retrieveConfirmOperation', $data);

    }
    
    public function deallocate(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','date') );

        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }

        $edate = R::findOne('configuration','name=?',array('end_date_minicourse_inscription'));

        // If inscriptions has finished
        if(!dateleq(mdate('%Y-%m-%d'),$edate->value)){
            $this->session->set_flashdata('error','Não é possível desconsolidar um minicurso quando o período de inscrições já se encerrou.');
            redirect(base_url('dashboard/minicourse/manage'));
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

        $id = $this->input->post('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $mc = R::findOne('minicourse','id=?',array($id));
        
        $mc->consolidated = 'no';
        $mc->sharedMinicoursedayshiftList = array();
        R::store($mc);

        $this->session->set_flashdata('success','O minicurso foi <b>desconsolidado</b> com sucesso.');
        redirect(base_url('dashboard/minicourse/manage'));
        exit;

    }
    
    public function deleteDayShift(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url') );

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
        
        $id = $this->input->post('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }
        
        $ds = R::findOne('minicoursedayshift','id=?',array($id));
        
        if(count($ds->sharedMinicourseList)!=0){
            $this->session->set_flashdata('error','Você não pode remover um turno quando existem minicursos alocados para ele. Desconsolide os minicursos para remover o turno.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        R::trash($ds);
        
        $this->session->set_flashdata('success','O turno foi removido com successo.');
        redirect(base_url('dashboard/minicourse/manage'));
        exit;
        
    }
    
    public function retrieveDetailsView(){

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

        $mc = R::findOne('minicourse','id=?',array($id));
        
        $data = array( 
            'mc' => $mc,
            );
        
        $this->load->view('dashboard/minicourse/retrieveDetails', $data);

    }
    
    public function retrieveEnrollDetailsView(){
        
        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','utility') );
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */
        
        $id = $this->input->get('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }

        $mc = R::findOne('minicourse','id=?',array($id));
        
        $data = array( 
            'mc' => $mc,
            );
        
        $this->load->view('dashboard/minicourse/retrieveEnrollDetails', $data);
        
    }
    
    public function enrollView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */
        
        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        $cdss = R::find('minicoursedayshift');
        $cs = R::find('minicourse','consolidated="yes" ORDER BY title ASC');
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_minicourse_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_minicourse_inscription'));
        
        if( dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value) && ( $user->paid=='accepted' || $user->paid=='free' ) )
            $open = true;
        else
            $open = false;
        
        $data = array( 
            'cdss' => $cdss,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'cs' => $cs,
            'active' => 'minicourseenroll',
            'user' => $user,
            'date_limit' => array( 'inscriptionStart' => $inscriptionStart , 'inscriptionEnd' => $inscriptionEnd , 'open' => $open )
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
        $this->load->view('dashboard/minicourse/enroll',$data);
        $this->load->view('dashboard/footer');

    }
    
    public function enrolla(){
    
        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' , 'date') );
        
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
        $u = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */
        
        $user = $u;

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
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_minicourse_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_minicourse_inscription'));
        
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
        
        // User paid?
        if( !($user->paid=='accepted' || $user->paid=='free') ){
            $this->session->set_flashdata('error','Você precisa realizar o pagamento para se inscrever em um minicurso.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
        
        $conf = R::findOne('minicourse','id=?', array($id) );
        
        // There are vacancies?
        if($conf->consolidatedvacanciesfilled >= $conf->consolidatedvacancies){
            $this->session->set_flashdata('error','Não há mais vagas disponíveis para este minicurso.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
        
        // Quantidade de registros excedeu?
        if(R::count('minicourseUser','user_id=?',array($user->id))>=3){
            $this->session->set_flashdata('error','Você pode se inscrever em no máximo 3 minicursos.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
        
        // Já está inscrito em algum outro minicurso no mesmo turno
        $mcl = $user->sharedMinicourseList;

        $confshift = '';

        foreach($conf->sharedMinicoursedayshiftList as $cmds){
            $confshift = $cmds->shift;
            break;
        }
        
        foreach($mcl as $m){
            foreach ($m->sharedMinicoursedayshiftList as $mds) {
                if($mds->shift==$confshift){
                    $this->session->set_flashdata('error','Você já está inscrito em um outro minicurso no mesmo turno. Você só pode se inscrever em um por turno.');
                    redirect(base_url('dashboard/minicourse/enroll'));
                    exit;
                }
            }
            
        }
        
        // Já está registrado?
        if(R::count('minicourseUser','user_id = ? AND minicourse_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você já se inscreveu neste minicurso.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
        
        $conf->consolidatedvacanciesfilled = $conf->consolidatedvacanciesfilled + 1;
        $conf->sharedUserList[] = $user;
        R::store($conf);

        $this->session->set_flashdata('success','Você se inscreveu no minicurso com sucesso.');
        redirect(base_url('dashboard/minicourse/enroll'));
        exit;
        
    }
    
     public function unroll(){
     
        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' , 'date') );
        
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
        $u = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */
        
        $user = $u;

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
         
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_minicourse_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_minicourse_inscription'));
         
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições, você só pode fazer modificações em seus minicursos no período referido.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
        
        $conf = R::findOne('minicourse','id=?', array($id) );
        
        // Está registrado nesta conferência?
        if(!R::count('minicourseUser','user_id = ? AND minicourse_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você não está inscrito neste minicurso.');
            redirect(base_url('dashboard/minicourse/enroll'));
            exit;
        }
         
        $conf->consolidatedvacanciesfilled = $conf->consolidatedvacanciesfilled - 1;
        R::store($conf); 
         
        $rel = R::findOne('minicourse_user','user_id = ? AND minicourse_id = ?',array($user->id,$id));
        R::trash($rel);

        $this->session->set_flashdata('success','Você não está mais inscrito no minicurso escolhido.');
        redirect(base_url('dashboard/minicourse/enroll'));
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

        $minicourse = R::findOne('minicourse','id=?',array($id));

        // If the article was evaluated
        if($minicourse->consolidated!='no'){
            $this->session->set_flashdata('error', 'O minicurso já foi consolidado. Não se pode remover um minicurso que já foi consolidado.');
            redirect(base_url('dashboard/minicourse/submit'));
            exit;
        }

        R::trash($minicourse);

        $this->session->set_flashdata('success', 'A submissão do minicurso foi <b>cancelada</b> com sucesso.');
        redirect(base_url('dashboard/minicourse/submit'));
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */