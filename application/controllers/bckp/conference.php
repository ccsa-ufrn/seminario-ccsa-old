<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Conference extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
    }

    public function manageView(){

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
        
        $cdss = R::find('conferencedayshift','ORDER BY date ASC');
        $cs = R::find('conference');
        
        $data = array( 
            'cdss' => $cdss,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'cs' => $cs
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/conference/manage',$data);
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
        
        $conference = $this->input->post('conference');
        $lista = $this->input->post('list');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */
            
        /* PRINTING */    
        
        echo "<meta charset='UTF-8' />";
            
        $result = R::find('conference_user','conference_id=?', array($conference));
        $m = R::findOne('conference','id=?',array($conference));

        echo "<h2 style='text-transform:uppercase;'>".$m->title."</h2>";
        echo "<h4 style='text-transform:uppercase;'>".$m->local." - ".$m->vacancies." vagas</h4>";

        echo "<p><b>Expositor:</b> ".$m->lecturer."</p>";
            
        echo "<h4>LISTAGEM DE INSCRITOS</h4>";

        echo "<table border='1' style='width:100%'>";   

        echo "<tr>";   
        if($lista!='list')
            echo "<th>ID</th>";
        echo "<th>Nome</th>";
        if($lista!='list'){
            echo "<th>Email</th>";
            echo "<th>Telefone</th>";
        }else{
           echo "<th style='width:40%;'>Assinatura</th>"; 
        }
        echo "</tr>";

        foreach ($result as $item) {
            echo "<tr>"; 
            if($lista!='list')
                echo "<td>".$item->user->id."</td>";  
            echo "<td style='text-transform:uppercase;'>".$item->user->name."</td>";
            if($lista!='list'){
                echo "<td>".$item->user->email."</td>";
                echo "<td>".$item->user->phone."</td>";
            }else{
                echo "<td></td>";
            }
            
            echo "</tr>";   
        }
        
        echo "</table>";   
        
        echo "<br/><b>".count($result)." registros encontrados</b>";

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
        $conferences = R::find('conference',' ORDER BY title ASC ');
        
        /* LIST OF FIELDS THAT CAN BE GENERATED WITH REPORT */
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'conferences' => $conferences
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/conference/report',$data);
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
                'rules' => 'numeric|greater_than[0]|less_than[13]|required'
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
                            'month' => form_error('month'),
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
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar Dia/Turno, verifique o formulário.');
            redirect(base_url('dashboard/conference'));
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
            redirect(base_url('dashboard/conference'));
            exit;
        }

        // Verifying that will not be any collision
        $v = R::find('conferencedayshift','date=? AND shift=?', array($date,$shift));
        
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
            redirect(base_url('dashboard/conference'));
            exit;
        }

        $rtds = R::dispense('conferencedayshift');
        
        $rtds['date'] = $date;
        $rtds['shift'] = $shift;
        $rtds['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        R::store($rtds);

        $this->session->set_flashdata('success','Dia/Turno adicionado no calendário.');
        redirect(base_url('dashboard/conference'));
        exit;
        
    }
    
    public function create(){

        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        /* ===========================================
            BEGIN - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
        $config = R::findOne('configuration','name=?',array('max_date_conference_consolidation'));
        
        if(!dateleq(mdate('%Y-%m-%d'),$config->value)){
            echo "Você não pode realizar esta operação. Está fora do limite de consolidação de conferências.";
            exit;
        }      
        
        /* ===========================================
            END - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
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
                'field' => 'title',
                'label' => 'Título',
                'rules' => 'required'
            ),
            array(
                'field' => 'lecturer',
                'label' => 'Conferencista',
                'rules' => 'required|callback_verifyingAuthors[1]'
            ),
            array(
                'field' => 'dayshift',
                'label' => 'Dia/Turno',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'vacancies',
                'label' => 'Quantidade de Vagas',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'proposal',
                'label' => 'Proposta',
                'rules' => 'required'
            ),
            array(
                'field' => 'local',
                'label' => 'Local',
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
                            'lecturer' => form_error('lecturer'),
                            'dayshift' => form_error('dayshift'),
                            'vacancies' => form_error('vacancies'),
                            'proposal' => form_error('proposal'),
                            'local' => form_error('local')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'lecturer' => set_value('lecturer'),
                            'dayshift' => set_value('dayshift'),
                            'vacancies' => set_value('vacancies'),
                            'proposal' => set_value('proposal'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Existem dados que não foram preenchidos corretamente para o cadastro de Conferência, verifique o formulário.');
            redirect(base_url('dashboard/conference'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $title = $this->input->post('title');
        $lecturer = $this->input->post('lecturer');
        $dayshift = $this->input->post('dayshift');
        $vacancies = $this->input->post('vacancies');
        $proposal = $this->input->post('proposal');
        $local = $this->input->post('local');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // VERIFYING IF DAY/SHIFT IS NOT -1 OR IF IT REALLY EXISTS
        
        if( $dayshift!=-1 )
            $cds = R::findOne('conferencedayshift','id=?',array($dayshift));
        
        if( $dayshift==-1 || !isset($cds) ){
            
            $this->session->set_flashdata(
                'popform', 
                array(
                        'title' => set_value('title'),
                        'lecturer' => set_value('lecturer'),
                        'dayshift' => set_value('dayshift'),
                        'vacancies' => set_value('vacancies'),
                        'proposal' => set_value('proposal'),
                        'local' => set_value('local')
                    )
            );
            $this->session->set_flashdata('error','Este dia/turno para consolidação da Conferência não existe.');
            redirect(base_url('dashboard/conference'));
            exit;
        }
        
        

        $c = R::dispense('conference');

        $c->title = $title;
        $c->lecturer = $lecturer;
        $c->vacancies = $vacancies;
        $c->vacanciesfilled = 0;
        $c->proposal = $proposal;
        $c->local = $local;
        $c->created_at = mdate('%Y-%m-%d %H:%i:%s');
        $c->user = $user;
        $c->cernn = 'pending';
        $c->scheduled = 'no';
        $c->conferencedayshift = $cds;

        $id = R::store($c);

        $this->session->set_flashdata('success','A conferência foi cadastrada e consolidada no calendário com sucesso.');
        redirect(base_url('dashboard/conference'));
        exit;

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
        
        $ds = R::findOne('conferencedayshift','id=?',array($id));
        
        if(count($ds->ownConferenceList)!=0){
            $this->session->set_flashdata('error','Você não pode remover um turno quando existem conferências alocadas para ele. Remova as conferências para remover o turno.');
            redirect(base_url('dashboard/conference'));
            exit;
        }
        
        R::trash($ds);
        
        $this->session->set_flashdata('success','O turno foi removido com successo.');
        redirect(base_url('dashboard/conference'));
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

        $sdate = R::findOne('configuration','name=?',array('start_date_conference_inscription'));

        // If the inscriptions start date began
        if(datebeq(mdate('%Y-%m-%d'),$sdate->value)){
            $this->session->set_flashdata('error','Não é possível desconsolidar uma conferência quando já se inicou o período de inscrições.');
            redirect(base_url('dashboard/conference/manage'));
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
        
        $conference = R::findOne('conference','id=?', array($id) );
        R::trash($conference);

        $this->session->set_flashdata('success','A conferência foi removida com sucesso.');
        redirect(base_url('dashboard/conference'));
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

        $c = R::findOne('conference','id=?',array($id));
        
        $data = array( 
            'c' => $c,
            );
        
        $this->load->view('dashboard/conference/retrieveDetails', $data);
        
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

        $c = R::findOne('conference','id=?',array($id));
        
        $data = array( 
            'c' => $c,
            );
        
        $this->load->view('dashboard/conference/retrieveEnrollDetails', $data);
        
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
        $cdss = R::find('conferencedayshift','ORDER BY date ASC');
        $cs = R::find('conference');
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_conference_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_conference_inscription'));
        
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
            'active' => 'conferenceenroll',
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
        $this->load->view('dashboard/conference/enroll',$data);
        $this->load->view('dashboard/footer');

    }
    
    public function enrolla(){
    
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
        
        $conf = R::findOne('conference','id=?', array($id) );
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_conference_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_conference_inscription'));
        
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }
        
        // User paid?
        if( !($user->paid=='accepted' || $user->paid=='free') ){
            $this->session->set_flashdata('error','Você precisa realizar o pagamento para se inscrever em uma conferência.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }
        
        // There are vacancies?
        if($conf->vacanciesfilled >= $conf->vacancies){
            $this->session->set_flashdata('error','Não há mais vagas disponíveis para esta conferência.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }
        
        // Quantidade de registros excedeu?
        /*if(R::count('conferenceUser','user_id=?',array($user->id))>=3){
            $this->session->set_flashdata('error','Você pode se inscrever em no máximo 3 conferências.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }*/
        
        // Já está inscrito em algum outra conferências no mesmo turno
        $mcl = $user->sharedConferenceList;
        
        foreach($mcl as $m){
            if( ($m->conferencedayshift->shift==$conf->conferencedayshift->shift)  && ($m->conferencedayshift->date==$conf->conferencedayshift->date) ){
                $this->session->set_flashdata('error','Você já está inscrito em um outra conferência no mesmo dia/turno. Você só pode se inscrever, no mesmo dia, em uma por turno.');
                redirect(base_url('dashboard/conference/enroll'));
                exit;
            }
        }
        
        // Já está registrado?
        if(R::count('conferenceUser','user_id = ? AND conference_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você já se inscreveu nesta conferência.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }
        
        $conf->vacanciesfilled = $conf->vacanciesfilled + 1;
        $conf->sharedUserList[] = $user;
        R::store($conf);

        $this->session->set_flashdata('success','Você se cadastrou na conferência com sucesso.');
        redirect(base_url('dashboard/conference/enroll'));
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
        
        $conf = R::findOne('conference','id=?', array($id) );
         
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_conference_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_conference_inscription'));
         
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições, você só pode fazer modificações em suas conferências no período referido.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }
        
        // Está registrado nesta conferência?
        if(!R::count('conferenceUser','user_id = ? AND conference_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você não está inscrito nesta conferência.');
            redirect(base_url('dashboard/conference/enroll'));
            exit;
        }
         
        $conf->vacanciesfilled = $conf->vacanciesfilled - 1;
        R::store($conf); 
         
        $rel = R::findOne('conference_user','user_id = ? AND conference_id = ?',array($user->id,$id));
        R::trash($rel);

        $this->session->set_flashdata('success','Você não está mais inscrito na Conferência escolhida.');
        redirect(base_url('dashboard/conference/enroll'));
        exit;
         
     }

    public function retrieveEditView(){

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

        $c = R::findOne('conference','id=?',array($id));
        $cdss = R::find('conferencedayshift','ORDER BY date ASC');
        
        $data = array( 
            'c' => $c,
            'cdss' => $cdss
            );
        
        $this->load->view('dashboard/conference/retrieveEdit', $data);

    }

    public function update(){

    	$this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        /* ===========================================
            END - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        
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
                'field' => 'title',
                'label' => 'Título',
                'rules' => 'required'
            ),
            array(
                'field' => 'lecturer',
                'label' => 'Conferencista',
                'rules' => 'required|callback_verifyingAuthors[1]'
            ),
            array(
                'field' => 'dayshift',
                'label' => 'Dia/Turno',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'vacancies',
                'label' => 'Quantidade de Vagas',
                'rules' => 'required|numeric'
            ),
            array(
                'field' => 'proposal',
                'label' => 'Proposta',
                'rules' => 'required'
            ),
            array(
                'field' => 'local',
                'label' => 'Local',
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
                            'lecturer' => form_error('lecturer'),
                            'dayshift' => form_error('dayshift'),
                            'vacancies' => form_error('vacancies'),
                            'proposal' => form_error('proposal'),
                            'local' => form_error('local')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'lecturer' => set_value('lecturer'),
                            'dayshift' => set_value('dayshift'),
                            'vacancies' => set_value('vacancies'),
                            'proposal' => set_value('proposal'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Existem dados que não foram preenchidos corretamente para a atualização de Conferência, repita a operação.');
            redirect(base_url('dashboard/conference'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $id = $this->input->post('id');
        $title = $this->input->post('title');
        $lecturer = $this->input->post('lecturer');
        $dayshift = $this->input->post('dayshift');
        $vacancies = $this->input->post('vacancies');
        $proposal = $this->input->post('proposal');
        $local = $this->input->post('local');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // VERIFYING IF DAY/SHIFT IS NOT -1 OR IF IT REALLY EXISTS
        
        if( $dayshift!=-1 )
            $cds = R::findOne('conferencedayshift','id=?',array($dayshift));
        
        if( $dayshift==-1 || !isset($cds) ){
            
            $this->session->set_flashdata(
                'popform', 
                array(
                        'title' => set_value('title'),
                        'lecturer' => set_value('lecturer'),
                        'dayshift' => set_value('dayshift'),
                        'vacancies' => set_value('vacancies'),
                        'proposal' => set_value('proposal'),
                        'local' => set_value('local')
                    )
            );
            $this->session->set_flashdata('error','Este dia/turno para consolidação da Conferência não existe.');
            redirect(base_url('dashboard/conference'));
            exit;
        }

        
        $c = R::findOne('conference','id=?',array($id));

        if( $vacancies < $c->vacanciesfilled ){
            $this->session->set_flashdata('error','A quantidade de inscritos supera o nova quantidade de vagas, isto não é permitido.');
            redirect(base_url('dashboard/conference'));
            exit;
        }

        $c->title = $title;
        $c->lecturer = $lecturer;
        $c->vacancies = $vacancies;
        $c->proposal = $proposal;
        $c->local = $local;
        $c->conferencedayshift = $cds;

        $id = R::store($c);

        $this->session->set_flashdata('success','A conferência foi atualizada com sucesso.');
        redirect(base_url('dashboard/conference'));
        exit;

    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */