<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Workshop extends CI_Controller {

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

        $mc = R::findOne('workshop','id=?',array($mcId));
        $dss = R::find('workshopdayshift','ORDER BY date ASC');

        $data = array( 
            'mc' => $mc,
            'dss' => $dss
            );
        
        $this->load->view('dashboard/workshop/retrieveEditInfo', $data);

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
            redirect(base_url('dashboard/workshop/manage'));
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
        $mc = R::findOne('workshop','id=?',array($id));

        // Verifying if there is any day for the course
        if(count($dayshifts)==0 || $dayshifts[0]=="-1"){
            $this->session->set_flashdata('error','Não há dias/turnos disponíveis para esta oficina.');
            redirect(base_url('dashboard/minicourse/manage'));
            exit;
        }
        
        if($mc['consolidatedvacanciesfilled'] > $vacancies){
            $this->session->set_flashdata('error','Não foi possível atualizar, pois a quantidade de inscrições supera a nova quantidade de vagas. Contacte o administrador.');
            redirect(base_url('dashboard/workshop/manage'));
            exit;
        }

        $mc['consolidatedvacancies'] = $vacancies;
        $mc['consolidatedlocal'] = $local;
        $mc['consolidated'] = 'yes';
        $mc->sharedWorkshopdayshiftList = array(); // Errado

        $id = R::store($mc);
        
        // Relation with dayshift

        for($i=0;$i<count($dayshifts);++$i){

            $ds = R::findOne('workshopdayshift','id=?',array($dayshifts[$i]));
            $ds->sharedWorkshopList[] = $mc;
            R::store($ds);

        }

        $this->session->set_flashdata('success','A oficina foi atualizado com sucesso.');
        redirect(base_url('dashboard/workshop/manage'));
        exit;

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
        
        $wsdss = R::find('workshopdayshift','ORDER BY date ASC');
        
        if($filter=='consolidated'){
            $wss = R::find('workshop',' consolidated="yes" ');
        }else if($filter=='noconsolidated'){
            $wss = R::find('workshop',' consolidated="no" ');
        }else{
            $wss = R::find('workshop');
        }
        
        
        $data = array( 
            'wsdss' => $wsdss,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'wss' => $wss
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/workshop/manage',$data);
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
            
            redirect(base_url('dashboard/workshop/manage'));
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
            redirect(base_url('dashboard/workshop/manage'));
            exit;
        }

        // Verifying that will not be any collision
        $v = R::find('workshopdayshift','date=? AND shift=?', array($date,$shift));
        
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
            redirect(base_url('dashboard/workshop/manage'));
            exit;
        }

        $mcds = R::dispense('workshopdayshift');
        
        $mcds['date'] = $date;
        $mcds['shift'] = $shift;
        $mcds['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        $id = R::store($mcds);

        $this->session->set_flashdata('success','Dia/Turno adicionado no calendário.');
        redirect(base_url('dashboard/workshop/manage'));
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

        $mc = R::findOne('workshop','id=?',array($mcId));

        // Retrieving days and shifts availables
        $dss = R::find('workshopdayshift','ORDER BY date ASC');

        $data = array( 
            'ws' => $mc,
            'dss' => $dss
            );
        
        $this->load->view('dashboard/workshop/retrieveConsolidation', $data);
        
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
            redirect(base_url('dashboard/workshop/manage'));
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
        $mc = R::findOne('workshop','id=?',array($id));
        
        // Can't continue if the minicourse is consolidated
        if($mc->consolidated=="yes"){
            $this->session->set_flashdata('error','Esta oficina já está consolidada.');
            redirect(base_url('dashboard/workshop/manage'));
            exit;
        }

        // Verifying if there is any day for the course
        if(count($dayshifts)==0 || $dayshifts[0]=="-1"){
            $this->session->set_flashdata('error','Não há dias/turnos disponíveis para esta oficina.');
            redirect(base_url('dashboard/workshop/manage'));
            exit;
        }
        
        $mc['consolidatedvacancies'] = $vacancies;
        $mc['consolidatedlocal'] = $local;
        $mc['consolidated'] = 'yes';
        $id = R::store($mc);
        
        // Relation with dayshift

        for($i=0;$i<count($dayshifts);++$i){

            $ds = R::findOne('workshopdayshift','id=?',array($dayshifts[$i]));
            $ds->sharedWorkshopList[] = $mc;
            R::store($ds);

        }

        $this->session->set_flashdata('success','A oficina foi consolidada com sucesso.');
        redirect(base_url('dashboard/workshop/manage'));
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

        $sdate = R::findOne('configuration','name=?',array('start_date_workshop_inscription'));
        $edate = R::findOne('configuration','name=?',array('end_date_workshop_inscription'));

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

        $mc = R::findOne('workshop','id=?',array($id));
        
        $data = array( 
            'mc' => $mc,
            'status' => $status
            );
        
        $this->load->view('dashboard/workshop/retrieveConfirmOperation', $data);

    }

    public function deallocate(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','date') );

        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }

        $edate = R::findOne('configuration','name=?',array('end_date_workshop_inscription'));

        // If inscriptions has finished
        if(!dateleq(mdate('%Y-%m-%d'),$edate->value)){
            $this->session->set_flashdata('error','Não é possível desconsolidar uma oficina quando o período de inscrições já se encerrou.');
            redirect(base_url('dashboard/workshop/manage'));
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

        $mc = R::findOne('workshop','id=?',array($id));
        
        $mc->consolidated = 'no';
        $mc->sharedWorkshopdayshiftList = array();
        R::store($mc);

        $this->session->set_flashdata('success','A oficina foi <b>desconsolidado</b> com sucesso.');
        redirect(base_url('dashboard/workshop/manage'));
        exit;

    }

    public function retrieveWorkshopDetails(){

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

        $mc = R::findOne('workshop','id=?',array($id));
        
        $data = array( 
            'ws' => $mc,
            );
        
        $this->load->view('dashboard/workshop/retrieveDetails', $data);

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
        
        $ds = R::findOne('workshopdayshift','id=?',array($id));
        
        if(count($ds->sharedWorkshopList)!=0){
            $this->session->set_flashdata('error','Você não pode remover um turno quando existem oficinas alocadas para ele. Desconsolide as oficinas para remover o turno.');
            redirect(base_url('dashboard/workshop/manage'));
            exit;
        }
        
        R::trash($ds);
        
        $this->session->set_flashdata('success','O turno foi removido com successo.');
        redirect(base_url('dashboard/workshop/manage'));
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

        $ws = R::findOne('workshop','id=?',array($id));

        // If the article was evaluated
        if($ws->consolidated!='no'){
            $this->session->set_flashdata('error', 'A oficina já foi consolidado. Não se pode remover uma oficina que já foi consolidado.');
            redirect(base_url('dashboard/workshop/submit'));
            exit;
        }

        R::trash($ws);

        $this->session->set_flashdata('success', 'A submissão da oficina foi <b>cancelada</b> com sucesso.');
        redirect(base_url('dashboard/workshop/submit'));
        
    }

    public function submitView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );
        
        // User logged in?
        $userLogged = $this->session->userdata('user_logged_in');
        
        if(!$userLogged)
            redirect(base_url('dashboard/login'));
        
        $config = R::findOne('configuration','name=?',array('max_date_workshop_submission'));
        
        if(dateleq(mdate('%Y-%m-%d'),$config->value))
            $open = true;
        else
            $open = false;
        
        $data = array(
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'workshops' => R::find('workshop','user_id=?',array($this->session->userdata('user_id'))),
            'active' => 'submit-workshop',
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

        $this->load->view('dashboard/workshop/submit',$data);
        $this->load->view('dashboard/footer');

    }

    public function uploadProgram(){

        $this->load->library( array('rb') );
        $this->load->helper( array('string') );
        
        $config['upload_path'] = './assets/upload/workshop/';
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
        
        $config = R::findOne('configuration','name=?',array('max_date_workshop_submission'));
        
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
            redirect(base_url('dashboard/workshop/submit'));
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

        $mc = R::dispense('workshop');
        
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

        $this->session->set_flashdata('success','A oficina foi submetida para avalição com sucesso, você será notificado em breve.');
        redirect(base_url('dashboard/workshop/submit'));
        exit;

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
        $ws = R::find('workshop','consolidated = "yes" ORDER BY title ASC');

        /* TODO: Update start/end date inscription for Workshop (below) */        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_workshop_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_workshop_inscription'));
        
        if( dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value) && ( $user->paid=='accepted' || $user->paid=='free' ) )
            $open = true;
        else
            $open = false;
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'ws' => $ws,
            'active' => 'workshopenroll',
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
        $this->load->view('dashboard/workshop/enroll',$data);
        $this->load->view('dashboard/footer');

    }

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
            utf8_decode( 'Lista de Oficina' ), 
            0, 
            0,
            'C'
        );
        
        /* *********************************************************
        * END - HEADER
        ********************************************************* */
        
        $workshop = $this->input->post('workshop');
        $lista = $this->input->post('list');
        
        $workshop = R::findOne('workshop', 'id = ?', array($workshop));
        
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
            utf8_decode( strtoupper ( $workshop->title ) ), 
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
            utf8_decode($workshop->consolidatedlocal.' - '.$workshop->consolidatedvacancies.' vagas '), 
            'TBR',
            0,
            'L',
            false
        ); 
        
        $pdf->Ln(7);
        
        /* Gerando String de Expositores */
        $conj = $workshop->expositor;
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

        $result = $workshop->with('ORDER BY name ASC')->sharedUserList;
        
        if($lista === 'list') : 
        
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

    public function reportView(){

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
        $workshops = R::find('workshop','ORDER BY title ASC ');
        
        /* LIST OF FIELDS THAT CAN BE GENERATED WITH REPORT */
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'workshops' => $workshops
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/workshop/report',$data);
        $this->load->view('dashboard/footer');

    }

    function retrieveEnrollDetails(){

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

        $ws = R::findOne('workshop','id=?',array($id));
        
        $data = array( 
            'ws' => $ws,
            );
        
        $this->load->view('dashboard/workshop/retrieveEnrollDetails', $data);

    }

    function enrolla(){

        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' ,'date' ) );
        
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
        
        $conf = R::findOne('workshop','id=?', array($id) );
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_conference_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_conference_inscription'));
        
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições.');
            redirect(base_url('dashboard/workshop/enroll'));
            exit;
        }
        
        // User paid?
        if( !($user->paid=='accepted' || $user->paid=='free') ){
            $this->session->set_flashdata('error','Você precisa realizar o pagamento para se inscrever em uma oficina.');
            redirect(base_url('dashboard/workshop/enroll'));
            exit;
        }
        
        // There are vacancies?
        if($conf->vacanciesfilled >= $conf->vacancies){
            $this->session->set_flashdata('error','Não há mais vagas disponíveis para esta oficina.');
            redirect(base_url('dashboard/workshop/enroll'));
            exit;
        }
        
        // Já está inscrito em algum outra conferências no mesmo turno
        $mcl = $user->sharedWorkshopList;
        
        foreach($mcl as $m){
            if( ($m->shift==$conf->shift)  && ($m->date==$conf->date) ){
                $this->session->set_flashdata('error','Você já está inscrito em um outra oficina no mesmo dia/turno. Você só pode se inscrever, no mesmo dia, em uma por turno.');
                redirect(base_url('dashboard/workshop/enroll'));
                exit;
            }
        }
        
        // Já está registrado?
        if(R::count('userWorkshop','user_id = ? AND workshop_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você já se inscreveu nesta conferência.');
            redirect(base_url('dashboard/workshop/enroll'));
            exit;
        }
        
        $conf->vacanciesfilled = $conf->vacanciesfilled + 1;
        $conf->sharedUserList[] = $user;
        R::store($conf);

        $this->session->set_flashdata('success','Você se cadastrou na oficina com sucesso.');
        redirect(base_url('dashboard/workshop/enroll'));
        exit;


    }

    function unroll(){

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
        
        $conf = R::findOne('workshop','id=?', array($id) );
         
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_conference_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_conference_inscription'));
         
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições, você só pode fazer modificações em suas conferências no período referido.');
            redirect(base_url('dashboard/workshop/enroll'));
            exit;
        }
        
        // Está registrado nesta conferência?
        if(!R::count('userWorkshop','user_id = ? AND workshop_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você não está inscrito nesta oficina.');
            redirect(base_url('dashboard/workshop/enroll'));
            exit;
        }
         
        $conf->vacanciesfilled = $conf->vacanciesfilled - 1;
        R::store($conf); 
         
        $rel = R::findOne('user_workshop','user_id = ? AND workshop_id = ?',array($user->id,$id));
        R::trash($rel);

        $this->session->set_flashdata('success','Você não está mais inscrito na oficina escolhida.');
        redirect(base_url('dashboard/workshop/enroll'));
        exit;

    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */