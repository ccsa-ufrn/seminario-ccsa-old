<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Certificate extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
    }

    public function customView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/custom',$data);
        $this->load->view('dashboard/footer');

    }

    public function getCertificateView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
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
        
        $data = array( 
            'user' => $u,
            'active' => 'certificate'   
            );
        
        $this->load->view('dashboard/header');
        if($u->type=='student'||$u->type=='noacademic')
            $this->load->view('dashboard/template/menuStudent',$data);
        else if($u->type=='instructor')
            $this->load->view('dashboard/template/menuInstructor',$data);
        $this->load->view('dashboard/certificate/getCertificate',$data);
        $this->load->view('dashboard/footer');

    }

    public function generate(){
        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','text') );

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

        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $text = "";
        $certgen = "";

        if($type=='user'){

            $user = R::findOne('user','id=?',array($id));

            if(!isset($user->certgen)){
                $user->certgen = date("His").'u'.$user->id;
                R::store($user);
            }

            $certgen = $user->certgen;
            $text = "Certificamos que ".strtoupper($user->name)." participou do XX SEMINÁRIO DE PESQUISA DO CCSA, realizado no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte, no período de 4 a 8 de maio de 2015.";

        }else if($type=='minicourse'){

            $mu = R::findOne('minicourse_user',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'mc'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->minicourse->expositor);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($mu->user->name)." participou do minicurso ".strtoupper($mu->minicourse->title).", ministrado por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizado no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte, com carga horária de 6h/aula.";

        }else if($type=='roundtable'){

            $mu = R::findOne('roundtable_user',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'rt'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->roundtable->coordinator);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($mu->user->name)." participou da mesa-redonda ".strtoupper($mu->roundtable->title).", coordenada por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";

        }else if($type=='conference'){

            $mu = R::findOne('conference_user',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'cf'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->conference->lecturer);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($mu->user->name)." participou da conferência ".strtoupper($mu->conference->title).", apresentada por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";

        }else if($type=='workshop'){

            $mu = R::findOne('user_workshop',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'ws'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->workshop->expositor);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($mu->user->name)." participou da oficina ".strtoupper($mu->workshop->title).", exposta por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";

        }else if($type=='paper'){

            $mu = R::findOne('paper',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'pa'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->authors);
            $final = "";

            if(count($at)==1)
                $first = "apresentou";
            else if(count($at)>1)
                $first = "apresentaram";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($final)." ".$first." o artigo: \"".strtoupper($mu->title)."\" - ".strtoupper($mu->thematicgroup->name).", no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";

        }else if($type=='poster'){

            $mu = R::findOne('poster',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'po'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->authors);
            $final = "";

            if(count($at)==1)
                $first = "apresentou";
            else if(count($at)>1)
                $first = "apresentaram";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($final)." ".$first." o artigo: \"".strtoupper($mu->title)."\" - ".strtoupper($mu->thematicgroup->name).", no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";

        }else if($type=='pposter'){

            $mu = R::findOne('paper',' id = ? ',array($id));

            if(!isset($mu->certgen)){
                $mu->certgen = date("His").'pa'.$mu->user->id.$id;
                R::store($mu);
            }

            $at = explode('||',$mu->authors);
            $final = "";

            if(count($at)==1)
                $first = "apresentou";
            else if(count($at)>1)
                $first = "apresentaram";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $certgen = $mu->certgen;
            $text = "Certificamos que ".strtoupper($final)."</b> ".$first." o pôster: \"".strtoupper($mu->title)."\" - ".strtoupper($mu->thematicgroup->name).", no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";

        }

        // Filter

        $data = array(
                'text' => $text,
                'certgen' => $certgen
            );

        $this->load->view('dashboard/certificate/customcertificate',$data);

    }

    public function createCustom(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','text') );
        
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

        $cert = R::dispense('certificate');
        $cert->text = $this->input->post('text');
        $id = R::store($cert);
        $cert->certgen = date("His").'ctm'.$id;
        R::store($cert);

        $data = array( 
            'text' => $this->input->post('text'),
            'certgen' => $cert->certgen
        );
        
        $this->load->view('dashboard/certificate/customcertificate',$data);
    
    }

    public function minicourseView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => R::find('minicourse',' cernn IS NULL OR ( cernn="pending" ) ORDER BY title ASC '),
            'mcsa' => R::find('minicourse',' cernn="yes" OR cernn="no" ORDER BY title ASC ')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/minicourse',$data);
        $this->load->view('dashboard/footer');

    }

    public function revertMinicourse(){

        $this->load->library( array('session','rb','form_validation') );
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

        $minicourse = R::findOne('minicourse',' id=? ', array($id) );

        if( !( isset( $minicourse->cernn ) && $minicourse->cernn!='pending' ) ){

            $this->session->set_flashdata('error','O minicurso ainda não foi avaliado.');
            redirect(base_url('dashboard/certificate/minicourse'));
            exit;

        }

        $minicourse->cernn = 'pending';

        R::store($minicourse);

        $mus = R::find('minicourse_user',' minicourse_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            $m->cernn = 'pending';
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/minicourse'));
        exit;

    }

    public function retrieveAddParticipant(){

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

        // Retrieving content
        $id = $this->input->get('id');
        $type = $this->input->get('type');
        
        $data = array( 
            'id' => $id,
            'type' => $type
        );

        $this->load->view('dashboard/certificate/addparticipant',$data);

    }

    public function retrieveParticipantsList(){
        
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

        // Retrieving content
        $name = $this->input->get('name');
        $id = $this->input->get('id');
        $type = $this->input->get('type');

        $name = str_replace(" ","%",$name);

        $ps = R::find('user',' ( type = "student" OR type = "noacademic" OR type = "insctructor" ) AND name LIKE ? ORDER BY name ASC', array( '%'.$name.'%'));

        echo "<table class='table table-striped table-bordered table-condensed'>";

        echo "<thead><tr>";
        echo "<th>Nome</th>";
        echo "<th>Acrescentar</th>";
        echo "<tr></thead>";
                        
        foreach ($ps as $p) {
            echo "<tr>";
            echo "<td style='text-transform:uppercase;' >".$p->name."</td>";

            echo "<td style='width:20%;text-align:center;'>"; 

            echo "<a id='certificate-add-participant-button' style='cursor:pointer;' data-value='".$p->id."' >Adicionar</a>";
            echo form_open(base_url('dashboard/certificate/addparticipant'), array('id' => 'formCertificateAddParticipant-'.$p->id,'novalidate' => '','class' => 'waiting'));
            echo "<input style='display:none;' type='text' name='userid' value='".$p->id."' >";
            echo "<input style='display:none;' type='text' name='actid' value='".$id."' >";
            echo "<input style='display:none;' type='text' name='acttype' value='".$type."' >";
            echo "</form>";

            echo "</td>";

            echo "</tr>";
        }

        if(!count($ps))
            echo "<td colspan='2'>Nenhum usuário com esse nome.</td>";

        echo "</table>";

    }

    public function addParticipant(){

        $this->load->library( array('session','rb','form_validation') );
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

        $userid = $this->input->post('userid');
        $actid = $this->input->post('actid');
        $acttype = $this->input->post('acttype');

        $user = R::findOne('user','id=?',array($userid));

        if($acttype=="minicourse"){
            $mc = R::findOne('minicourse','id=?',array($actid));

            if(R::count('minicourse_user','user_id=? AND minicourse_id=?',array($userid,$actid))){
                $this->session->set_flashdata('error','Este usuário já está cadastrado neste minicurso, você não pode adicioná-lo novamente.');
                redirect(base_url('dashboard/certificate/minicourse'));
                exit;
            }

            $mc->sharedUserList[] = $user;
            R::store($mc);

            $ua = R::findOne('minicourse_user','user_id=? AND minicourse_id=?',array($userid,$actid));
            $ua->cernn = 'pending';
            R::store($ua);

        }else if($acttype=="roundtable"){
            $mc = R::findOne('roundtable','id=?',array($actid));

            if(R::count('roundtable_user','user_id=? AND roundtable_id=?',array($userid,$actid))){
                $this->session->set_flashdata('error','Este usuário já está cadastrado nesta mesa-redonda, você não pode adicioná-lo novamente.');
                redirect(base_url('dashboard/certificate/roundtable'));
                exit;
            }

            $mc->sharedUserList[] = $user;
            R::store($mc);

            $ua = R::findOne('roundtable_user','user_id=? AND roundtable_id=?',array($userid,$actid));
            $ua->cernn = 'pending';
            R::store($ua);

        }else if($acttype=="conference"){
            $mc = R::findOne('conference','id=?',array($actid));

            if(R::count('conference_user','user_id=? AND conference_id=?',array($userid,$actid))){
                $this->session->set_flashdata('error','Este usuário já está cadastrado nesta conferência, você não pode adicioná-lo novamente.');
                redirect(base_url('dashboard/certificate/conference'));
                exit;
            }

            $mc->sharedUserList[] = $user;
            R::store($mc);

            $ua = R::findOne('conference_user','user_id=? AND conference_id=?',array($userid,$actid));
            $ua->cernn = 'pending';
            R::store($ua);

        }else if($acttype=="workshop"){
            $mc = R::findOne('workshop','id=?',array($actid));

            if(R::count('user_workshop','user_id=? AND workshop_id=?',array($userid,$actid))){
                $this->session->set_flashdata('error','Este usuário já está cadastrado nesta oficina, você não pode adicioná-lo novamente.');
                redirect(base_url('dashboard/certificate/workshop'));
                exit;
            }

            $mc->sharedUserList[] = $user;
            R::store($mc);

            $ua = R::findOne('user_workshop','user_id=? AND workshop_id=?',array($userid,$actid));
            $ua->cernn = 'pending';
            R::store($ua);

        }else{
            echo "Esta atividade n[a]o existe.";
            exit;
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso');

        if($acttype=="minicourse")
            redirect(base_url('dashboard/certificate/minicourse'));
        else if($acttype=="roundtable")
            redirect(base_url('dashboard/certificate/roundtable'));
        else if($acttype=="conference")
            redirect(base_url('dashboard/certificate/conference'));
        else if($acttype=="workshop")
            redirect(base_url('dashboard/certificate/workshop'));
        exit;

    }

    public function retrieveAcceptMinicourse(){

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

        // Retrieving content
        $mcId = $this->input->get('id');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mc' => R::findOne('minicourse','id=?',array($mcId))
        );

        $this->load->view('dashboard/certificate/retrieveacceptminicourse',$data);

    }

    public function acceptMinicourse(){

        $this->load->library( array('session','rb','form_validation') );
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
        $users = $this->input->post('users');

        $minicourse = R::findOne('minicourse',' id=? ', array($id) );

        if( isset( $minicourse->cernn ) && $minicourse->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/minicourse'));
            exit;

        }

        $minicourse->cernn = 'yes';

        R::store($minicourse);

        $mus = R::find('minicourse_user',' minicourse_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            if(in_array($m->user->id,$users)){
                $m->cernn = 'yes';
            }else{
                $m->cernn = 'no';
            }
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/minicourse'));
        exit;

    }

    public function rejectMinicourse(){

        $this->load->library( array('session','rb','form_validation') );
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

        $minicourse = R::findOne('minicourse',' id=? ', array($id) );

        if( isset( $minicourse->cernn ) && $minicourse->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/minicourse'));
            exit;

        }

        $minicourse->cernn = 'no';

        R::store($minicourse);

        $list = R::find('minicourse_user','minicourse_id=?',array($id));

        foreach ($list as $l) {
            $l->cernn = 'no';
            R::store($l);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/minicourse'));
        exit;
        
    }

    public function roundtableView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => R::find('roundtable',' cernn IS NULL OR ( cernn="pending" ) ORDER BY title ASC '),
            'mcsa' => R::find('roundtable',' cernn="yes" OR cernn="no" ORDER BY title ASC ')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/roundtable',$data);
        $this->load->view('dashboard/footer');

    }

    public function revertRoundtable(){

        $this->load->library( array('session','rb','form_validation') );
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

        $rt = R::findOne('roundtable',' id=? ', array($id) );

        if( !( isset( $rt->cernn ) && $rt->cernn!='pending' ) ){

            $this->session->set_flashdata('error','A mesa-redonda ainda não foi avaliada.');
            redirect(base_url('dashboard/certificate/roundtable'));
            exit;

        }

        $rt->cernn = 'pending';

        R::store($rt);

        $mus = R::find('roundtable_user',' roundtable_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            $m->cernn = 'pending';
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/roundtable'));
        exit;

    }

    public function retrieveAcceptRoundtable(){

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

        // Retrieving content
        $mcId = $this->input->get('id');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mc' => R::findOne('roundtable','id=?',array($mcId))
        );

        $this->load->view('dashboard/certificate/retrieveacceptroundtable',$data);

    }

    public function acceptRoundtable(){

        $this->load->library( array('session','rb','form_validation') );
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
        $users = $this->input->post('users');

        $rt = R::findOne('roundtable',' id=? ', array($id) );

        if( isset( $rt->cernn ) && $rt->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/roundtable'));
            exit;

        }

        $rt->cernn = 'yes';

        R::store($rt);

        $mus = R::find('roundtable_user',' roundtable_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            if(in_array($m->user->id,$users)){
                $m->cernn = 'yes';
            }else{
                $m->cernn = 'no';
            }
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/roundtable'));
        exit;

    }

    public function rejectRoundtable(){

        $this->load->library( array('session','rb','form_validation') );
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

        $rt = R::findOne('roundtable',' id=? ', array($id) );

        if( isset( $rt->cernn ) && $rt->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/roundtable'));
            exit;

        }

        $rt->cernn = 'no';

        R::store($rt);

        $list = R::find('roundtable_user','roundtable_id=?',array($id));

        foreach ($list as $l) {
            $l->cernn = 'no';
            R::store($l);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/roundtable'));
        exit;
        
    }

    public function conferenceView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => R::find('conference',' cernn IS NULL OR ( cernn="pending" ) ORDER BY title ASC '),
            'mcsa' => R::find('conference',' cernn="yes" OR cernn="no" ORDER BY title ASC ')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/conference',$data);
        $this->load->view('dashboard/footer');

    }

    public function revertConference(){

        $this->load->library( array('session','rb','form_validation') );
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

        $rt = R::findOne('conference',' id=? ', array($id) );

        if( !( isset( $rt->cernn ) && $rt->cernn!='pending' ) ){

            $this->session->set_flashdata('error','A conferência ainda não foi avaliada.');
            redirect(base_url('dashboard/certificate/conference'));
            exit;

        }

        $rt->cernn = 'pending';

        R::store($rt);

        $mus = R::find('conference_user',' conference_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            $m->cernn = 'pending';
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/conference'));
        exit;

    }

    public function retrieveAcceptConference(){

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

        // Retrieving content
        $mcId = $this->input->get('id');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mc' => R::findOne('conference','id=?',array($mcId))
        );

        $this->load->view('dashboard/certificate/retrieveacceptconference',$data);

    }

    public function acceptConference(){

        $this->load->library( array('session','rb','form_validation') );
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
        $users = $this->input->post('users');

        $rt = R::findOne('conference',' id=? ', array($id) );

        if( isset( $rt->cernn ) && $rt->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/conference'));
            exit;

        }

        $rt->cernn = 'yes';

        R::store($rt);

        $mus = R::find('conference_user',' conference_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            if(in_array($m->user->id,$users)){
                $m->cernn = 'yes';
            }else{
                $m->cernn = 'no';
            }
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/conference'));
        exit;

    }

    public function rejectConference(){

        $this->load->library( array('session','rb','form_validation') );
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

        $rt = R::findOne('conference',' id=? ', array($id) );

        if( isset( $rt->cernn ) && $rt->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/conference'));
            exit;

        }

        $rt->cernn = 'no';

        R::store($rt);

        $list = R::find('conference_user','conference_id=?',array($id));

        foreach ($list as $l) {
            $l->cernn = 'no';
            R::store($l);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/conference'));
        exit;
        
    }

    public function workshopView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => R::find('workshop',' cernn IS NULL OR ( cernn="pending" ) ORDER BY title ASC '),
            'mcsa' => R::find('workshop',' cernn="yes" OR cernn="no" ORDER BY title ASC ')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/workshop',$data);
        $this->load->view('dashboard/footer');

    }

    public function revertWorkshop(){

        $this->load->library( array('session','rb','form_validation') );
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

        $rt = R::findOne('workshop',' id=? ', array($id) );

        if( !( isset( $rt->cernn ) && $rt->cernn!='pending' ) ){

            $this->session->set_flashdata('error','A oficina ainda não foi avaliada.');
            redirect(base_url('dashboard/certificate/workshop'));
            exit;

        }

        $rt->cernn = 'pending';

        R::store($rt);

        $mus = R::find('user_workshop',' workshop_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            $m->cernn = 'pending';
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/workshop'));
        exit;

    }

    public function retrieveAcceptWorkshop(){

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

        // Retrieving content
        $mcId = $this->input->get('id');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mc' => R::findOne('workshop','id=?',array($mcId))
        );

        $this->load->view('dashboard/certificate/retrieveacceptworkshop',$data);

    }

    public function acceptWorkshop(){

        $this->load->library( array('session','rb','form_validation') );
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
        $users = $this->input->post('users');

        $rt = R::findOne('workshop',' id=? ', array($id) );

        if( isset( $rt->cernn ) && $rt->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/workshop'));
            exit;

        }

        $rt->cernn = 'yes';

        R::store($rt);

        $mus = R::find('user_workshop',' workshop_id=? ',array($id));

        // Atualizando os alunos
        foreach ($mus as $m) {
            if(in_array($m->user->id,$users)){
                $m->cernn = 'yes';
            }else{
                $m->cernn = 'no';
            }
            R::store($m);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/workshop'));
        exit;

    }

    public function rejectWorkshop(){

        $this->load->library( array('session','rb','form_validation') );
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

        $rt = R::findOne('workshop',' id=? ', array($id) );

        if( isset( $rt->cernn ) && $rt->cernn!='pending' ){

            $this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
            redirect(base_url('dashboard/certificate/workshop'));
            exit;

        }

        $rt->cernn = 'no';

        R::store($rt);

        $list = R::find('user_workshop','workshop_id=?',array($id));

        foreach ($list as $l) {
            $l->cernn = 'no';
            R::store($l);
        }

        $this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/workshop'));
        exit;
        
    }

    public function paperView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'tgs' => R::find('thematicgroup')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/paper',$data);
        $this->load->view('dashboard/footer');

    }

    public function acceptPaper(){

    	$this->load->library( array('session','rb','form_validation') );
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

        $paper = R::findOne('paper',' id=? ', array($id) );

        if( isset( $paper->cernn ) && $paper->cernn!='pending' ){

        	$this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
        	redirect(base_url('dashboard/certificate/paper'));
        	exit;

        }

       	$paper->cernn = 'yes';

       	R::store($paper);

       	$this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/paper'));
        exit;

    }

    public function rejectPaper(){

    	$this->load->library( array('session','rb','form_validation') );
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

        $paper = R::findOne('paper',' id=? ', array($id) );

        if( isset( $paper->cernn ) && $paper->cernn!='pending' ){

        	$this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
        	redirect(base_url('dashboard/certificate/paper'));
        	exit;

        }

       	$paper->cernn = 'no';

       	R::store($paper);

       	$this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/paper'));
        exit;
        
    }

    public function posterView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'tgs' => R::find('thematicgroup')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/certificate/poster',$data);
        $this->load->view('dashboard/footer');

    }

    public function acceptPoster(){

    	$this->load->library( array('session','rb','form_validation') );
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
        $type = $this->input->post('type');

        if($type=="poster")
        	$p = R::findOne('poster',' id=? ', array($id) );
        else if($type=="paper")
			$p = R::findOne('paper',' id=? ', array($id) );

        if( isset( $p->cernn ) && $p->cernn!='pending' ){

        	$this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
        	redirect(base_url('dashboard/certificate/poster'));
        	exit;

        }

       	$p->cernn = 'yes';

       	R::store($p);

       	$this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/poster'));
        exit;

    }

    public function rejectPoster(){

    	$this->load->library( array('session','rb','form_validation') );
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
        $type = $this->input->post('type');

        if($type=="poster")
            $p = R::findOne('poster',' id=? ', array($id) );
        else if($type=="paper")
            $p = R::findOne('paper',' id=? ', array($id) );

        if( isset( $p->cernn ) && $p->cernn!='pending' ){

        	$this->session->set_flashdata('error','Esta operação não pode ser realizada, pois já ocorreu anteriormente.');
        	redirect(base_url('dashboard/certificate/poster'));
        	exit;

        }

       	$p->cernn = 'no';

       	R::store($p);

       	$this->session->set_flashdata('success','Operação realizada com sucesso.');
        redirect(base_url('dashboard/certificate/poster'));
        exit;
        
    }

    public function validateView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform')
        );
        
        $this->load->view('template/header');
        $this->load->view('dashboard/certificate/validate',$data);
        $this->load->view('template/footer');

    }

    public function validate(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','text') );

        $code = $this->input->post("code");
        $validated = "no";
        $cert;
        $text;
        $certgen;

        // Custom Certificate
        if(R::count('certificate','certgen=?',array($code))){

            $cert = R::findOne('certificate','certgen=?',array($code));
            $validated = "yes";
            $text = $cert->text;
            $certgen = $cert->certgen;

        }else if(R::count('user','certgen=?',array($code))){

            $cert = R::findOne('user','certgen=?',array($code));
            $validated = "yes";
            $text = "Certificamos que ".strtoupper($cert->name)." participou do XX SEMINÁRIO DE PESQUISA DO CCSA, realizado no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte, no período de 4 a 8 de maio de 2015.";
            $certgen = $cert->certgen;

        }else if(R::count('minicourse_user','certgen=?',array($code))){

            $cert = R::findOne('minicourse_user','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->minicourse->expositor);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($cert->user->name)." participou do minicurso ".strtoupper($cert->minicourse->title).", ministrado por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizado no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte, com carga horária de 6h/aula.";
            $certgen = $cert->certgen;

        }else if(R::count('roundtable_user','certgen=?',array($code))){

            $cert = R::findOne('roundtable_user','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->roundtable->coordinator);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($cert->user->name)." participou da mesa-redonda ".strtoupper($cert->roundtable->title).", coordenada por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";
            $certgen = $cert->certgen;

        }else if(R::count('conference_user','certgen=?',array($code))){

            $cert = R::findOne('conference_user','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->conference->lecturer);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($cert->user->name)." participou da conferência ".strtoupper($cert->conference->title).", apresentada por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";
            $certgen = $cert->certgen;

        }else if(R::count('user_workshop','certgen=?',array($code))){

            $cert = R::findOne('user_workshop','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->workshop->expositor);
            $final = "";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($cert->user->name)." participou da oficina ".strtoupper($cert->workshop->title).", exposta por ".strtoupper($final)." no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";
            $certgen = $cert->certgen;

        }else if(R::count('paper','certgen=? AND evaluation="accepted"',array($code))){

            $cert = R::findOne('paper','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->authors);
            $final = "";
            $first = "";

            if(count($at)==1)
                $first = "apresentou";
            else if(count($at)>1)
                $first = "apresentaram";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($final)." ".$first." o artigo: \"".strtoupper($cert->title)."\" - ".strtoupper($cert->thematicgroup->name).", no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";
            $certgen = $cert->certgen;

        }else if(R::count('poster','certgen=?',array($code))){

            $cert = R::findOne('poster','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->authors);
            $final = "";
            $first = "";

            if(count($at)==1)
                $first = "apresentou";
            else if(count($at)>1)
                $first = "apresentaram";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($final)." ".strtoupper($first)." o pôster: \"".strtoupper($cert->title)."\" - ".strtoupper($cert->thematicgroup->name).", no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";
            $certgen = $cert->certgen;

        }else if(R::count('paper','certgen=? AND evaluation="asPoster" ',array($code))){

            $cert = R::findOne('paper','certgen=?',array($code));
            $validated = "yes";

            $at = explode('||',$cert->authors);
            $final = "";
            $first = "";

            if(count($at)==1)
                $first = "apresentou";
            else if(count($at)>1)
                $first = "apresentaram";

            $i=0; foreach ($at as $a) {
                $temp = explode('[',$a);
                if($i!=0 && count($at)-1!=$i && $i < count($at))
                    $final .= ", ";
                else if($i!=0 && count($at)-1==$i)
                    $final .= " e ";
                $final .= $temp[0];
                $i++;
            }

            $text = "Certificamos que ".strtoupper($final)." ".$first." o pôster: \"".strtoupper($cert->title)."\" - ".strtoupper($cert->thematicgroup->name).", no XX SEMINÁRIO DE PESQUISA DO CCSA, realizada no Centro de Ciências Sociais Aplicadas da Universidade Federal do Rio Grande do Norte.";
            $certgen = $cert->certgen;

        }else{

            echo "<meta charset='utf-8'>";
            echo "<b style='color:red;font-size:22px;'>Este certificado não foi validado, ele não existe.</b>";
            exit;

        }

        $data = array( 
            'text' => $text,
            'certgen' => $certgen,
            'validated' => $validated
        );
        
        $this->load->view('dashboard/certificate/customcertificate',$data);

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */