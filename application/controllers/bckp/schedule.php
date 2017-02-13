<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }

	public function paperManageView(){

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

        $papers = R::find('paper','evaluation="accepted" AND (scheduled IS NULL OR scheduled="no") ORDER BY title ');
        $tgs = R::find('thematicgroup','ORDER BY name');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'papers' => $papers,
            'tgs' => $tgs,
            'records' => R::find('schedulerecord','type="gtpaper" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/paper',$data);
        $this->load->view('dashboard/footer');

	}

	public function paperNewRecord(){

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
                'field' => 'thematicgroup',
                'label' => 'Grupo Temático',
                'rules' => 'numeric|required'
            ),array(
                'field' => 'dateday',
                'label' => 'Dia',
                'rules' => 'numeric|is_natural_no_zero|greater_than[0]|less_than[32]|required'
            ),
            array(
                'field' => 'datemonth',
                'label' => 'Mês',
                'rules' => 'numeric|greater_than[0]|less_than[13]|required'
            ),
            array(
                'field' => 'dateyear',
                'label' => 'Ano',
                'rules' => 'numeric|is_natural_no_zero|exact_length[4]|required'
            ),
            array(
                'field' => 'starthour',
                'label' => 'Horário de Início',
                'rules' => 'required'
            ),
            array(
                'field' => 'endhour',
                'label' => 'Horário de Término',
                'rules' => 'required'
            ),
            array(
                'field' => 'shift',
                'label' => 'Turno',
                'rules' => 'required'
            ),
            array(
                'field' => 'papers',
                'label' => 'Artigos',
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

        $pps = $this->input->post('papers');
        $papers = array();
        
        foreach ($pps as $pid) {
            $papers[] = $pid;
        }
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata(
                    'validation', 
                    array(
                    		'thematicgroup' => form_error('thematicgroup'),
                            'dateday' => form_error('dateday'),
                            'datemonth' => form_error('datemonth'),
                            'dateyear' => form_error('dateyear'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'shift' => form_error('shift'),
                            'papers' => form_error('papers'),
                            'local' => form_error('local')
                        )
                );
            
	        $this->session->set_flashdata(
	                'popform', 
	                array(
	                		'thematicgroup' => set_value('thematicgroup'),
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'shift' => set_value('shift'),
                            'papers' => $papers,
                            'local' => set_value('local')
	                    )
	            );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar GTs/Artigos na programação, verifique o formulário.');
            redirect(base_url('dashboard/schedule/paper'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $thematicgroup = $this->input->post('thematicgroup');
        $day = $this->input->post('dateday');
        $month = $this->input->post('datemonth');
        $year = $this->input->post('dateyear');
        $date = $year."-".sprintf("%02s", $month)."-".sprintf("%02s", $day);
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $shift = $this->input->post('shift');
        $papers = $papers; 
        $local = $this->input->post('local'); 

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Verifying if it's a valid date
        if(!checkdate($month,$day,$year)){
            $this->session->set_flashdata('error','Data não válida.');
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'thematicgroup' => set_value('thematicgroup'),
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'shift' => set_value('shift'),
                            'papers' => $papers,
                            'local' => $local
                        )
                );
            redirect(base_url('dashboard/schedule/paper'));
            exit;
        }

        $tg = R::findOne('thematicgroup','id=?',array($thematicgroup));
        echo $tg->name;
        exit;

        $rec = R::dispense('schedulerecord');
        $rec->type = 'gtpaper';
        // The line below is not working, maybe it is some bug of redBeanPHP
        //$rec->thematicgroup = $tg;
        $rec->date = $date;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->shift = $shift;
        $rec->local = $local;

        // Scheduling papers
        foreach ($papers as $id) {
            $paper = R::findOne('paper','id=?',array($id));
            $paper->scheduled = 'yes';
            R::store($paper);
            $rec->ownPaperList[] = $paper;
        }

        R::store($rec);

        $this->session->set_flashdata('success','GT/Artigos adicionados à programação.');
        redirect(base_url('dashboard/schedule/paper'));
        exit;
		
	}

    public function removeRecord(){

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
            BEGIN - PREPARING DATA
        ============================================ */

        $id = $this->input->post('id');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $rec = R::findOne('schedulerecord','id=?',array($id));

        if($rec->type=='gtpaper'){

            $papers = $rec->ownPaperList;

            foreach ($papers as $p) {
                $p->scheduled = 'no';
                R::store($p);
            }

        }else if($rec->type=='gtposter'){

            $posters = $rec->ownPosterList;

            foreach ($posters as $p) {
                $p->scheduled = 'no';
                R::store($p);
            }

        }else if($rec->type=='minicourse'){

            $minicourse = $rec->minicourse;
            $minicourse->scheduled = 'no';

            R::store($minicourse);

        }else if($rec->type=='roundtable'){

            $roundtable = $rec->roundtable;
            $roundtable->scheduled = 'no';

            R::store($roundtable);

        }else if($rec->type=='conference'){

            $conference = $rec->conference;
            $conference->scheduled = 'no';

            R::store($conference);

        }else if($rec->type=='othersactivities'){

            // kkkkkkkk, tão gambiarra isso...

        }else{
            exit;
        }
        

        R::trash($rec);

        $this->session->set_flashdata('success','Registro removido da programação');
        if($rec->type=='gtpaper')
            redirect(base_url('dashboard/schedule/paper'));
        else if($rec->type=='gtposter')
            redirect(base_url('dashboard/schedule/poster'));
        else if($rec->type=='minicourse')
            redirect(base_url('dashboard/schedule/minicourse'));
        else if($rec->type=='roundtable')
            redirect(base_url('dashboard/schedule/roundtable'));
        else if($rec->type=='conference')
            redirect(base_url('dashboard/schedule/conference'));
        else if($rec->type=='otheractivity')
            redirect(base_url('dashboard/schedule/othersactivities'));
        exit;

    }

	public function posterManageView(){

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

        $posters = R::find('poster','evaluation="accepted" AND (scheduled IS NULL OR scheduled="no") ORDER BY title ');
        $tgs = R::find('thematicgroup','ORDER BY name');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'posters' => $posters,
            'tgs' => $tgs,
            'records' => R::find('schedulerecord','type="gtposter" ORDER BY date ASC, starthour ASC')
        );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/poster',$data);
        $this->load->view('dashboard/footer');

	}

    public function posterNewRecord(){

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
                'field' => 'thematicgroup',
                'label' => 'Grupo Temático',
                'rules' => 'numeric|required'
            ),array(
                'field' => 'dateday',
                'label' => 'Dia',
                'rules' => 'numeric|is_natural_no_zero|greater_than[0]|less_than[32]|required'
            ),
            array(
                'field' => 'datemonth',
                'label' => 'Mês',
                'rules' => 'numeric|greater_than[0]|less_than[13]|required'
            ),
            array(
                'field' => 'dateyear',
                'label' => 'Ano',
                'rules' => 'numeric|is_natural_no_zero|exact_length[4]|required'
            ),
            array(
                'field' => 'starthour',
                'label' => 'Horário de Início',
                'rules' => 'required'
            ),
            array(
                'field' => 'endhour',
                'label' => 'Horário de Término',
                'rules' => 'required'
            ),
            array(
                'field' => 'shift',
                'label' => 'Turno',
                'rules' => 'required'
            ),
            array(
                'field' => 'posters',
                'label' => 'Pôsteres',
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

        $pps = $this->input->post('posters');
        $posters = array();
        
        foreach ($pps as $pid) {
            $posters[] = $pid;
        }
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata(
                    'validation', 
                    array(
                            'thematicgroup' => form_error('thematicgroup'),
                            'dateday' => form_error('dateday'),
                            'datemonth' => form_error('datemonth'),
                            'dateyear' => form_error('dateyear'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'shift' => form_error('shift'),
                            'posters' => form_error('posters'),
                            'local' => form_error('local')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'thematicgroup' => set_value('thematicgroup'),
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'shift' => set_value('shift'),
                            'posters' => $posters,
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar GTs/Pôsteres na programação, verifique o formulário.');
            redirect(base_url('dashboard/schedule/poster'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $thematicgroup = $this->input->post('thematicgroup');
        $day = $this->input->post('dateday');
        $month = $this->input->post('datemonth');
        $year = $this->input->post('dateyear');
        $date = $year."-".sprintf("%02s", $month)."-".sprintf("%02s", $day);
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $shift = $this->input->post('shift');
        $local = $this->input->post('local');
        $posters = $posters;  

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Verifying if it's a valid date
        if(!checkdate($month,$day,$year)){
            $this->session->set_flashdata('error','Data não válida.');
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'thematicgroup' => set_value('thematicgroup'),
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'shift' => set_value('shift'),
                            'posters' => $posters,
                            'lolcal' => $local
                        )
                );
            redirect(base_url('dashboard/schedule/poster'));
            exit;
        }

        $tg = R::findOne('thematicgroup','id=?',array($thematicgroup));

        $rec = R::dispense('schedulerecord');
        $rec->type = 'gtposter';
        // The line below is not working, maybe it is some bug of redBeanPHP
        $rec->thematicgroup = R::findOne('thematicgroup','id=?',array($thematicgroup));
        // So i decided to put in the title, the name of the GT, cause i don't want to update the last version of redbeanphp without test
        $rec->title = $tg->name;
        $rec->date = $date;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->shift = $shift;
        $rec->local = $local;

        // Scheduling papers
        foreach ($posters as $id) {
            $poster = R::findOne('poster','id=?',array($id));
            $poster->scheduled = 'yes';
            R::store($poster);
            $rec->ownPosterList[] = $poster;
        }

        R::store($rec);

        $this->session->set_flashdata('success','GT/Pôsteres adicionados à programação.');
        redirect(base_url('dashboard/schedule/poster'));
        exit;

    }

	public function roundtableManageView(){

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

        $rts = R::find('roundtable','consolidated="yes" AND (scheduled="no" OR scheduled IS NULL) ORDER BY title');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'rts' => $rts,
            'records' => R::find('schedulerecord','type="roundtable" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/roundtable',$data);
        $this->load->view('dashboard/footer');

	}

    public function roundtableNewRecord(){

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
                'field' => 'roundtable',
                'label' => 'Mesa-Redonda',
                'rules' => 'numeric|required'
            ),
            array(
                'field' => 'starthour',
                'label' => 'Horário de Início',
                'rules' => 'required'
            ),
            array(
                'field' => 'endhour',
                'label' => 'Horário de Término',
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
                            'roundtable' => form_error('roundtable'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'local' => form_error('local')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'roundtable' => set_value('roundtable'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar a mesa-redonda na programação, verifique o formulário.');
            redirect(base_url('dashboard/schedule/roundtable'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $roundtable = $this->input->post('roundtable');
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $local = $this->input->post('local');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $roundtable = R::findOne('roundtable','id=?',array($roundtable));
        $dsr = $roundtable->roundtabledayshift;

        $rec = R::dispense('schedulerecord');
        $rec->type = 'roundtable';
        $rec->roundtable = $roundtable;
        $rec->date = $dsr->date;
        $rec->shift = $dsr->shift;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->local = $local;

        // Scheduling minicourse
        $roundtable->scheduled = 'yes';
        R::store($roundtable);

        R::store($rec);

        $this->session->set_flashdata('success','Mesa-redonda adicionado à programação.');
        redirect(base_url('dashboard/schedule/roundtable'));
        exit;

    }

    public function conferenceManageView(){

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

        $cs = R::find('conference','scheduled="no" OR scheduled IS NULL ORDER BY title');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'cs' => $cs,
            'records' => R::find('schedulerecord','type="conference" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/conference',$data);
        $this->load->view('dashboard/footer');

    }

    public function conferenceNewRecord(){

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
                'field' => 'conference',
                'label' => 'Conferência',
                'rules' => 'numeric|required'
            ),
            array(
                'field' => 'starthour',
                'label' => 'Horário de Início',
                'rules' => 'required'
            ),
            array(
                'field' => 'endhour',
                'label' => 'Horário de Término',
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
                            'conference' => form_error('conference'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'local' => form_error('local')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'conference' => set_value('conference'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar a conferência na programação, verifique o formulário.');
            redirect(base_url('dashboard/schedule/conference'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $conference = $this->input->post('conference');
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $local = $this->input->post('local');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $conference = R::findOne('conference','id=?',array($conference));
        $dsr = $conference->conferencedayshift;

        $rec = R::dispense('schedulerecord');
        $rec->type = 'conference';
        $rec->conference = $conference;
        $rec->date = $dsr->date;
        $rec->shift = $dsr->shift;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->local = $local;

        // Scheduling minicourse
        $conference->scheduled = 'yes';
        R::store($conference);

        R::store($rec);

        $this->session->set_flashdata('success','Conferência adicionado à programação.');
        redirect(base_url('dashboard/schedule/conference'));
        exit;

    }

	public function minicourseManageView(){

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

        $mcs = R::find('minicourse','consolidated="yes" AND (scheduled="no" OR scheduled IS NULL) ORDER BY title');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => $mcs,
            'records' => R::find('schedulerecord','type="minicourse" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/minicourse',$data);
        $this->load->view('dashboard/footer');

	}

    public function minicourseNewRecord(){

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
                'field' => 'minicourse',
                'label' => 'Minicurso',
                'rules' => 'numeric|required'
            ),
            array(
                'field' => 'starthour',
                'label' => 'Horário de Início',
                'rules' => 'required'
            ),
            array(
                'field' => 'endhour',
                'label' => 'Horário de Término',
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
                            'minicourse' => form_error('minicourse'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'local' => form_error('local')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'minicourse' => set_value('mincourse'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar o Minicurso na programação, verifique o formulário.');
            redirect(base_url('dashboard/schedule/minicourse'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $minicourse = $this->input->post('minicourse');
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $local = $this->input->post('local');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $minicourse = R::findOne('minicourse','id=?',array($minicourse));
        $ds = $minicourse->with('ORDER BY date ASC')->sharedMinicoursedayshiftList;

        $dsr = NULL;

        foreach($ds as $d) {
            $dsr = $d;
        }

        $rec = R::dispense('schedulerecord');
        $rec->type = 'minicourse';
        $rec->minicourse = $minicourse;
        $rec->date = $dsr->date;
        $rec->shift = $dsr->shift;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->local = $local;

        // Scheduling minicourse
        $minicourse->scheduled = 'yes';
        R::store($minicourse);

        R::store($rec);

        $this->session->set_flashdata('success','Minicurso adicionado à programação.');
        redirect(base_url('dashboard/schedule/minicourse'));
        exit;

    }

	public function othersActivitiesManageView(){

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
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'records' => R::find('schedulerecord','type="otheractivity" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/othersActivities',$data);
        $this->load->view('dashboard/footer');

	}

    public function othersActivitiesNewRecord(){

        $this->load->library( array('session','rb','form_validation') );
        $this->load->helper( array('url','form','date','typography') );
        
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
                'field' => 'title',
                'label' => 'Título',
                'rules' => 'required'
            ),array(
                'field' => 'description',
                'label' => 'Descrição',
                'rules' => 'required'
            ),array(
                'field' => 'dateday',
                'label' => 'Dia',
                'rules' => 'numeric|is_natural_no_zero|greater_than[0]|less_than[32]|required'
            ),
            array(
                'field' => 'datemonth',
                'label' => 'Mês',
                'rules' => 'numeric|greater_than[0]|less_than[13]|required'
            ),
            array(
                'field' => 'dateyear',
                'label' => 'Ano',
                'rules' => 'numeric|is_natural_no_zero|exact_length[4]|required'
            ),
            array(
                'field' => 'starthour',
                'label' => 'Horário de Início',
                'rules' => 'required'
            ),
            array(
                'field' => 'endhour',
                'label' => 'Horário de Término',
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
                            'description' => form_error('description'),
                            'dateday' => form_error('dateday'),
                            'datemonth' => form_error('datemonth'),
                            'dateyear' => form_error('dateyear'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'papers' => form_error('papers'),
                            'local' => form_error('local')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'description' => set_value('description'),
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar "outra atividade", verifique o formulário.');
            redirect(base_url('dashboard/schedule/othersactivities'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $title = $this->input->post('title');
        $description = auto_typography($this->input->post('description'));
        $day = $this->input->post('dateday');
        $month = $this->input->post('datemonth');
        $year = $this->input->post('dateyear');
        $date = $year."-".sprintf("%02s", $month)."-".sprintf("%02s", $day);
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $local = $this->input->post('local'); 

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // Verifying if it's a valid date
        if(!checkdate($month,$day,$year)){
            $this->session->set_flashdata('error','Data não válida.');
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'description' => set_value('description'),
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'local' => set_value('local')
                        )
                );
            redirect(base_url('dashboard/schedule/othersactivities'));
            exit;
        }

        $rec = R::dispense('schedulerecord');
        $rec->type = 'otheractivity';
        $rec->title = $title;
        $rec->description = $description;
        $rec->date = $date;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->local = $local;

        R::store($rec);

        $this->session->set_flashdata('success',' "Outra atividade" adicionada à programação.');
        redirect(base_url('dashboard/schedule/othersactivity'));
        exit;

    }

	public function generateView(){

		$this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','text','date') );
        
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
        
        $recordsOA = R::find('schedulerecord','type="otheractivity" ORDER BY date ASC, starthour ASC');
        $recordsPO = R::find('schedulerecord','type="gtposter" ORDER BY date ASC, starthour ASC');
        $recordsPA = R::find('schedulerecord','type="gtpaper" ORDER BY date ASC, starthour ASC');
        $recordsMC = R::find('schedulerecord','type="minicourse" ORDER BY date ASC, starthour ASC');
        $recordsRT = R::find('schedulerecord','type="roundtable" ORDER BY date ASC, starthour ASC');
        $recordsCF = R::find('schedulerecord','type="conference" ORDER BY date ASC, starthour ASC');

        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'recordsOA' => $recordsOA,
            'recordsPO' => $recordsPO,
            'recordsPA' => $recordsPA,
            'recordsMC' => $recordsMC,
            'recordsRT' => $recordsRT,
            'recordsCF' => $recordsCF
        );
        
        $this->load->view('dashboard/schedule/generate',$data);
	}

    public function retrieveWorksByTg(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );

        // It's a GET request?
        if($this->input->server('REQUEST_METHOD')!='GET'){
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

        $type = $this->input->get('type');
        $tgid = $this->input->get('tgid');

        $tg = R::findOne('thematicgroup','id=?',array($tgid));
        $data = array();

        if($type=='paper'){

            $papers = $tg->withCondition(' evaluation <> "rejected" AND (scheduled IS NULL OR scheduled="no" ) ORDER BY title')->ownPaperList;
            $papersf = array();
            foreach ($papers as $paper){
                $papersf[] = array( 'id' => $paper->id, 'title' => $paper->title );
            }
            $data = array( 'type' => 'paper', 'papers' => $papersf );
            
            // reset list
            $papers = $tg->all()->ownPaperList;

        }else if($type=='poster'){

            $posters = $tg->withCondition(' evaluation <> "rejected" AND (scheduled IS NULL OR scheduled="no" ) ORDER BY title')->ownPosterList;
            $postersf = array();
            foreach ($posters as $poster){
                $postersf[] = array( 'id' => $poster->id, 'title' => $poster->title );
            }
            $data = array( 'type' => 'poster', 'posters' => $postersf );

            // reset list
            $papers = $tg->all()->ownPosterList;

        }else{
            exit;
        }

        // Continue here
        echo json_encode( $data );
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */