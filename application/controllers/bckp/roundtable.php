<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RoundTable extends CI_Controller {

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

        $rt = R::findOne('roundtable','id=?',array($mcId));
        $rds = R::find('roundtabledayshift','ORDER BY date ASC');

        $data = array( 
            'rt' => $rt,
            'rds' => $rds
            );
        
        $this->load->view('dashboard/roundtable/retrieveEditInfo', $data);

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
                'rules' => 'numeric|required'
            ),
            array(
                'field' => 'local',
                'label' => 'Local',
                'rules' => 'required'
            ),
            array(
                'field' => 'dayshift',
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
            redirect(base_url('dashboard/roundtable/manage'));
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
        $dayshift = $this->input->post('dayshift');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // Retriving rountable
        $rt = R::findOne('roundtable','id=?',array($id));

        // Verifying if there is any day for the course
        if($this->input->post('dayshift')=="-1"){
            $this->session->set_flashdata('error','Não há dias/turnos disponíveis para esta mesa-redonda.');
            redirect(base_url('dashboard/roundtable/manage'));
            exit;
        }
        
        $rt['consolidatedvacancies'] = $vacancies;
        $rt['consolidatedlocal'] = $local;
        R::store($rt);
        
        // Relation with dayshift
        
        $ds = R::findOne('roundtabledayshift','id=?',array($dayshift));
        $ds->ownRoundtableList[] = $rt;
        R::store($ds);

        $this->session->set_flashdata('success','A mesa-redonda foi atualizada com sucesso.');
        redirect(base_url('dashboard/roundtable/manage'));
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

        $sdate = R::findOne('configuration','name=?',array('start_date_roundtable_inscription'));
        $edate = R::findOne('configuration','name=?',array('end_date_roundtable_inscription'));

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

        $mc = R::findOne('roundtable','id=?',array($id));
        
        $data = array( 
            'mc' => $mc,
            'status' => $status
            );
        
        $this->load->view('dashboard/roundtable/retrieveConfirmOperation', $data);

    }

	public function submitView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'instructor';
        $type2 = 'coordinator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if( !($u['type']==$type || $u['type']==$type2) )
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */ 
        
        $config = R::findOne('configuration','name=?',array('max_date_roundtable_submission'));
        
        if(dateleq(mdate('%Y-%m-%d'),$config->value))
            $open = true;
        else
            $open = false;
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'roundtables' => R::find('roundtable','user_id=?',array($this->session->userdata('user_id'))),
                    'active' => 'submit-roundtable',
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
        }
        
        $this->load->view('dashboard/roundtable/submit',$data);
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
        
        $roundtable = $this->input->post('roundtable');
        $lista = $this->input->post('list');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */
            
        /* PRINTING */    
        
        echo "<meta charset='UTF-8' />";
            
        $result = R::find('roundtable_user','roundtable_id=?', array($roundtable));
        $m = R::findOne('roundtable','id=?',array($roundtable));

        echo "<h2 style='text-transform:uppercase;'>".$m->title."</h2>";
        echo "<h4 style='text-transform:uppercase;'>".$m->consolidatedlocal." - ".$m->consolidatedvacancies." vagas</h4>";

        $arr = explode ('||', $m->debaters );
        $debaters = implode( ',' , $arr );

        echo "<p><b>Debatedor(es):</b> ".$debaters."</p>";
        echo "<p><b>Coordenador:</b> ".$m->coordinator."</p>";

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
        $roundtables = R::find('roundtable',' consolidated="yes" ORDER BY title ASC ');
        
        /* LIST OF FIELDS THAT CAN BE GENERATED WITH REPORT */
        
        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'roundtables' => $roundtables
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/roundtable/report',$data);
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
        
        $config = R::findOne('configuration','name=?',array('max_date_roundtable_submission'));
        
        if(!dateleq(mdate('%Y-%m-%d'),$config->value)){
            echo "Você não pode realizar esta operação. Está fora do limite de envio de trabalho. =D";
            exit;
        }      
        
        /* ===========================================
            END - CHECKING CONFIGURATIONS LIMITS
        ============================================ */
        

        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        
        /* =================================================
            BEGIN - CAPABILITIES SECURITY
        ================================================== */
        $type = 'instructor';
        $type2 = 'coordinator';
        $userLogged = $this->session->userdata('user_logged_in');
        if(!$userLogged)
            redirect(base_url('dashboard'));
        $u = $user;
        if( !($u['type']==$type || $u['type']==$type2) )
            redirect(base_url('dashboard'));
        /* =================================================
            END - CAPABILITIES SECURITY
        ================================================== */ 

        // Retrieving user
        $user = R::findOne('user','id=?',array($user->id));

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
                'field' => 'debaters',
                'label' => 'Debatedores',
                'rules' => 'required|callback_verifyingAuthors[3]'
            ),
            array(
                'field' => 'coordinator',
                'label' => 'Coordenador',
                'rules' => 'required|callback_verifyingAuthors[1]'
            ),
            array(
                'field' => 'proposal',
                'label' => 'Proposta',
                'rules' => 'required'
            ),
            array(
                'field' => 'shift',
                'label' => 'Turno',
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
                            'authors' => form_error('authors'),
                            'proposal' => form_error('proposal'),
                            'debaters' => form_error('debaters'),
                            'coordinator' => form_error('coordinator'),
                            'shift' => form_error('shift')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'authors' => set_value('authors'),
                            'proposal' => set_value('proposal'),
                            'debaters' => set_value('debaters'),
                            'coordinator' => set_value('coordinator'),
                            'shift' => set_value('shift')
                        )
                );
            
            $this->session->set_flashdata('error','Algum campo não foi preenchido corretamente, verifique o formulário.');   
            redirect(base_url('dashboard/roundtable/submit'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */

        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $title = $this->input->post('title');
        $authors = $this->input->post('authors');
        $proposal = $this->input->post('proposal');
        $debaters = $this->input->post('debaters');
        $coordinator = $this->input->post('coordinator');
        $shift = $this->input->post('shift');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $rt = R::dispense('roundtable');

        $rt['title'] = $title;
        $rt['authors'] = $authors;
        $rt['proposal'] = $proposal;
        $rt['debaters'] = $debaters;
        $rt['coordinator'] = $coordinator;
        $rt['shift'] = $shift;
        $rt['cernn'] = 'pending';
        $rt['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
        $rt['consolidated'] = 'no';
        $rt['scheduled'] = 'no';
        $rt['user'] = $user;

        $id = R::store($rt);

        $this->session->set_flashdata('success','A mesa redonda foi submetida para avalição com sucesso, você será notificado em breve.');
        redirect(base_url('dashboard/roundtable/submit'));
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
        
        $rtdss = R::find('roundtabledayshift','ORDER BY date ASC');
        
        if($filter=='consolidated'){
            $rts = R::find('roundtable',' consolidated="yes" ');
        }else if($filter=='noconsolidated'){
            $rts = R::find('roundtable',' consolidated="no" ');
        }else{
            $rts = R::find('roundtable');
        }
        
        $data = array( 
            'rtdss' => $rtdss,
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'rts' => $rts
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/roundtable/manage',$data);
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
            
            redirect(base_url('dashboard/roundtable/manage'));
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
            redirect(base_url('dashboard/roundtable/manage'));
            exit;
        }

        // Verifying that will not be any collision
        $v = R::find('roundtabledayshift','date=? AND shift=?', array($date,$shift));
        
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
            redirect(base_url('dashboard/roundtable/manage'));
            exit;
        }

        $rtds = R::dispense('roundtabledayshift');
        
        $rtds['date'] = $date;
        $rtds['shift'] = $shift;
        $rtds['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        R::store($rtds);

        $this->session->set_flashdata('success','Dia/Turno adicionado no calendário.');
        redirect(base_url('dashboard/roundtable/manage'));
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

        $rt = R::findOne('roundtable','id=?',array($mcId));

        // Retrieving days and shifts availables
        $dss = R::find('roundtabledayshift','ORDER BY date ASC');

        $data = array( 
            'rt' => $rt,
            'dss' => $dss
            );
        
        $this->load->view('dashboard/roundtable/retrieveConsolidation', $data);
        
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
                'rules' => 'numeric|required'
            ),
            array(
                'field' => 'local',
                'label' => 'Local',
                'rules' => 'required'
            ),
            array(
                'field' => 'dayshift',
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
            redirect(base_url('dashboard/roundtable/manage'));
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
        $dayshift = $this->input->post('dayshift');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */
        
        // Retriving rountable
        $rt = R::findOne('roundtable','id=?',array($id));
        
        // Can't continue if the roundtable is consolidated
        if($rt->consolidated=="yes"){
            $this->session->set_flashdata('error','Esta mesa-redonda já está consolidado.');
            redirect(base_url('dashboard/roundtable/manage'));
            exit;
        }

        // Verifying if there is any day for the course
        if($this->input->post('dayshift')=="-1"){
            $this->session->set_flashdata('error','Não há dias/turnos disponíveis para esta mesa-redonda.');
            redirect(base_url('dashboard/roundtable/manage'));
            exit;
        }
        
        $rt['consolidatedvacancies'] = $vacancies;
        $rt['consolidatedlocal'] = $local;
        $rt['consolidated'] = 'yes';
        R::store($rt);
        
        // Relation with dayshift
        
        $ds = R::findOne('roundtabledayshift','id=?',array($dayshift));
        $ds->ownRoundtableList[] = $rt;
        R::store($ds);

        $this->session->set_flashdata('success','A mesa-redonda foi consolidada com sucesso.');
        redirect(base_url('dashboard/roundtable/manage'));
        exit;
        
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
            $this->session->set_flashdata('error','Não é possível desconsolidar uma mesa-redonda quando o período de inscrições já se encerrou.');
            redirect(base_url('dashboard/roundtable/manage'));
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

        $rt = R::findOne('roundtable','id=?',array($id));
        
        $rt->consolidated = 'no';
        $rt->roundtabledayshift = NULL;
        R::store($rt);

        $this->session->set_flashdata('success','A mesa-redonda foi <b>desconsolidada</b> com sucesso.');
        redirect(base_url('dashboard/roundtable/manage'));
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
        
        $ds = R::findOne('roundtabledayshift','id=?',array($id));
        
        if(count($ds->ownRoundtableList)!=0){
            $this->session->set_flashdata('error','Você não pode remover um turno quando existem mesas-redondas alocadas para ele. Desconsolide as mesas-redondas para remover o turno.');
            redirect(base_url('dashboard/roundtable/manage'));
            exit;
        }
        
        R::trash($ds);
        
        $this->session->set_flashdata('success','O turno foi removido com successo.');
        redirect(base_url('dashboard/roundtable/manage'));
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

        $rt = R::findOne('roundtable','id=?',array($id));
        
        $data = array( 
            'rt' => $rt,
            );
        
        $this->load->view('dashboard/roundtable/retrieveDetails', $data);

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

        $rt = R::findOne('roundtable','id=?',array($id));
        
        $data = array( 
            'rt' => $rt,
            );
        
        $this->load->view('dashboard/roundtable/retrieveEnrollDetails', $data);
        
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
        $cdss = R::find('roundtabledayshift','ORDER BY date ASC');
        $cs = R::find('roundtable');
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_roundtable_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_roundtable_inscription'));
        
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
            'active' => 'roundtableenroll',
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
        $this->load->view('dashboard/roundtable/enroll',$data);
        $this->load->view('dashboard/footer');

    }
    
    public function enrolla(){
    
        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' , 'date' ) );
        
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
        
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_roundtable_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_roundtable_inscription'));
        
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
        
        // User paid?
        if( !($user->paid=='accepted' || $user->paid=='free') ){
            $this->session->set_flashdata('error','Você precisa realizar o pagamento para se inscrever em uma mesa-redonda.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
        
        $conf = R::findOne('roundtable','id=?', array($id) );
        
        // There are vacancies?
        if($conf->consolidatedvacanciesfilled >= $conf->consolidatedvacancies){
            $this->session->set_flashdata('error','Não há mais vagas disponíveis para esta mesa-redonda.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
        
        // Quantidade de registros excedeu?
        if(R::count('roundtableUser','user_id=?',array($user->id))>=3){
            $this->session->set_flashdata('error','Você pode se inscrever em no máximo 3 mesas-redondas.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
        
        // Já está inscrito em algum outra mesa-redonda no mesmo turno
        $mcl = $user->sharedRoundtableList;
        
        foreach($mcl as $m){
            if( ($m->roundtabledayshift->shift==$conf->roundtabledayshift->shift) && ($m->roundtabledayshift->date==$conf->roundtabledayshift->date) ){
                $this->session->set_flashdata('error','Você já está inscrito em um outra mesa-redonda no mesmo dia/turno. Você só pode se inscrever, no mesmo dia, em uma por turno.');
                redirect(base_url('dashboard/roundtable/enroll'));
                exit;
            }
        }
        
        // Já está registrado?
        if(R::count('roundtableUser','user_id = ? AND roundtable_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você já se inscreveu nesta mesa-redonda.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
        
        $conf->consolidatedvacanciesfilled = $conf->consolidatedvacanciesfilled + 1;
        $conf->sharedUserList[] = $user;
        R::store($conf);

        $this->session->set_flashdata('success','Você se inscreveu na mesa-redonda com sucesso.');
        redirect(base_url('dashboard/roundtable/enroll'));
        exit;
        
    }
    
     public function unroll(){
     
        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' , 'date' ) );
        
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
        
        $conf = R::findOne('roundtable','id=?', array($id) );
         
        $inscriptionStart = R::findOne('configuration','name=?',array('start_date_roundtable_inscription'));
        $inscriptionEnd = R::findOne('configuration','name=?',array('end_date_roundtable_inscription'));
         
        // The inscription period is open?
        if( !(dateleq(mdate('%Y-%m-%d'),$inscriptionEnd->value) && datebeq(mdate('%Y-%m-%d'),$inscriptionStart->value)) ){
            $this->session->set_flashdata('error','Não está no período de inscrições, você só pode fazer modificações em suas mesas-redondas no período referido.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
        
        // Está registrado nesta conferência?
        if(!R::count('roundtableUser','user_id = ? AND roundtable_id = ?',array($user->id,$id))){
            $this->session->set_flashdata('error','Você não está inscrito neste mesa-redonda.');
            redirect(base_url('dashboard/roundtable/enroll'));
            exit;
        }
         
        $conf->consolidatedvacanciesfilled = $conf->consolidatedvacanciesfilled - 1;
        R::store($conf); 
         
        $rel = R::findOne('roundtable_user','user_id = ? AND roundtable_id = ?',array($user->id,$id));
        R::trash($rel);

        $this->session->set_flashdata('success','Você não está mais inscrito na mesa-redonda escolhida.');
        redirect(base_url('dashboard/roundtable/enroll'));
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

        $roundtable = R::findOne('roundtable','id=?',array($id));

        // If the article was evaluated
        if($roundtable->consolidated!='no'){
            $this->session->set_flashdata('error', 'A mesa-redonda já foi consolidada. Não se pode remover uma mesa-redonda que já foi consolidada.');
            redirect(base_url('dashboard/roundtable/submit'));
            exit;
        }

        R::trash($roundtable);

        $this->session->set_flashdata('success', 'A submissão da mesa-redonda foi <b>cancelada</b> com sucesso.');
        redirect(base_url('dashboard/roundtable/submit'));
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */