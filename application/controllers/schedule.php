<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }
    
    public function teachingcaseView()
    {
        
        $this->load->library( 
            array(
                'session',
                'rb'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url',
                'form'
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

        $tcs = R::find(
            'teachingcase',
            'evaluation = "accepted" AND (scheduled IS NULL OR scheduled="no") ORDER BY title '
        );
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'tcs' => $tcs,
            'records' => R::find('schedulerecord','type="gtteachingcase" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/teachingcase',$data);
        $this->load->view('dashboard/footer');
        
    }

    public function teachingcaseNewRecord()
    {
        
        $this->load->library( 
            array(
                'session',
                'rb',
                'form_validation'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url',
                'form',
                'date'
            ) 
        );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD') != 'POST') :
            
            echo "Don't do that. :D";
            exit;
        endif;
        
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
                'field' => 'teachingcases',
                'label' => 'Casos para Ensino',
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

        $pps = $this->input->post('teachingcases');
        $tcs = array();
        
        foreach ($pps as $pid) {
            $tcs[] = $pid;
        }
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
            $this->session->set_flashdata(
                    'validation', 
                    array(
                            'dateday' => form_error('dateday'),
                            'datemonth' => form_error('datemonth'),
                            'dateyear' => form_error('dateyear'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'shift' => form_error('shift'),
                            'teachingcases' => form_error('teachingcases'),
                            'local' => form_error('local')
                        )
                );
            
	        $this->session->set_flashdata(
	                'popform', 
	                array(
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
            
            $this->session->set_flashdata(
                'error',
                'Você esqueceu de preencher algum dado para cadastrar casos para ensino na programação, verifique o formulário.'
            );
            redirect(base_url('dashboard/schedule/teachingcase'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $day = $this->input->post('dateday');
        $month = $this->input->post('datemonth');
        $year = $this->input->post('dateyear');
        $date = $year."-".sprintf("%02s", $month)."-".sprintf("%02s", $day);
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $shift = $this->input->post('shift');
        $teachingcases = $teachingcases; 
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
                            'dateday' => set_value('dateday'),
                            'datemonth' => set_value('datemonth'),
                            'dateyear' => set_value('dateyear'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'shift' => set_value('shift'),
                            'teachingcases' => $teachingcases,
                            'local' => $local
                        )
                );
            redirect(base_url('dashboard/schedule/teachingcase'));
            exit;
        }

        $rec = R::dispense('schedulerecord');
        $rec->type = 'gtteachingcase';
        // The line below is not working, maybe it is some bug of redBeanPHP
        $rec->date = $date;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->shift = $shift;
        $rec->local = $local;

        // Scheduling papers
        foreach ($tcs as $id) {
            $tc = R::findOne('teachingcase','id=?',array($id));
            $tc->scheduled = 'yes';
            R::store($tc);
            $rec->ownTeachingcaseList[] = $tc;
        }

        R::store($rec);

        $this->session->set_flashdata(
            'success',
            'Casos para Ensino adicionados à programação.'
        );
        redirect(base_url('dashboard/schedule/teachingcase'));
        exit;
        
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


	public function paperNewRecord()
    {

		$this->load->library( 
            array(
                'session',
                'rb',
                'form_validation'
            ) 
        );
        
        $this->load->helper( 
            array(
                'url',
                'form',
                'date'
            ) 
        );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD') != 'POST') :
            
            echo "Don't do that. :D";
            exit;
        endif;
        
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

        $rec = R::dispense('schedulerecord');
        $rec->type = 'gtpaper';
        // The line below is not working, maybe it is some bug of redBeanPHP
        $rec->thematicgroup = $tg;
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

        }else if($rec->type=='gtteachingcase'){

            $tcs = $rec->ownTeachingcaseList;

            foreach ($tcs as $tc) {
                $tc->scheduled = 'no';
                R::store($tc);
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

        }else if($rec->type=='workshop'){

            $workshop = $rec->workshop;
            $workshop->scheduled = 'no';

            R::store($workshop);

        }else if($rec->type=='otheractivity'){

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
        else if($rec->type=='gtteachingcase')
            redirect(base_url('dashboard/schedule/teachingcase'));
        else if($rec->type=='minicourse')
            redirect(base_url('dashboard/schedule/minicourse'));
        else if($rec->type=='roundtable')
            redirect(base_url('dashboard/schedule/roundtable'));
        else if($rec->type=='conference')
            redirect(base_url('dashboard/schedule/conference'));
        else if($rec->type=='workshop')
            redirect(base_url('dashboard/schedule/workshop'));
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
    
    public function workshopView()
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

        $mcs = R::find('workshop','consolidated="yes" AND (scheduled="no" OR scheduled IS NULL) ORDER BY title');
        
        $data = array( 
            'success' => $this->session->flashdata('success'),
            'error' => $this->session->flashdata('error'),
            'validation' => $this->session->flashdata('validation'),
            'popform' => $this->session->flashdata('popform'),
            'mcs' => $mcs,
            'records' => R::find('schedulerecord','type="workshop" ORDER BY date ASC, starthour ASC')
        );
        
        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/schedule/workshop',$data);
        $this->load->view('dashboard/footer');
        
        
    }
    
    public function workshopNewRecord()
    {
        
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
                'field' => 'workshop',
                'label' => 'Oficina',
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
                            'workshop' => form_error('workshop'),
                            'starthour' => form_error('starthour'),
                            'endhour' => form_error('endhour'),
                            'local' => form_error('local')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'workshop' => set_value('workshop'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour'),
                            'local' => set_value('local')
                        )
                );
            
            $this->session->set_flashdata('error','Você esqueceu de preencher algum dado para cadastrar o Minicurso na programação, verifique o formulário.');
            redirect(base_url('dashboard/schedule/workshop'));
            exit;
        }

        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */

        $workshop = $this->input->post('workshop');
        $starthour = $this->input->post('starthour'); 
        $endhour = $this->input->post('endhour'); 
        $local = $this->input->post('local');

        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $workshop = R::findOne('workshop','id=?',array($workshop));
        $ds = $workshop->with('ORDER BY date ASC')->sharedWorkshopdayshiftList;

        $dsr = NULL;

        foreach($ds as $d) {
            $dsr = $d;
        }

        $rec = R::dispense('schedulerecord');
        $rec->type = 'workshop';
        $rec->workshop = $workshop;
        $rec->date = $dsr->date;
        $rec->shift = $dsr->shift;
        $rec->starthour = $starthour;
        $rec->endhour = $endhour;
        $rec->local = $local;

        // Scheduling minicourse
        $workshop->scheduled = 'yes';
        R::store($workshop);

        R::store($rec);

        $this->session->set_flashdata('success','Oficina adicionado à programação.');
        redirect(base_url('dashboard/schedule/workshop'));
        exit;
        
        
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
                            'endhour' => form_error('endhour')
                        )
                );
            
            $this->session->set_flashdata(
                    'popform', 
                    array(
                            'minicourse' => set_value('mincourse'),
                            'starthour' => set_value('starthour'),
                            'endhour' => set_value('endhour')
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
        redirect(base_url('dashboard/schedule/othersactivities'));
        exit;

    }


	public function generateView()
    {
        
        /* 
         * Loading libraries and helpers
        */
        $this->load->library(
            array(
                'rb',
                'fpdfgen'
            )
        );
        
        $this->load->helper(
            array(
                'text'
            )
        );
        
        /*
         * Creating PDF
        */
        $pdf = new FPDI();
        
        $pdf->addPage('P','A4');
        
        /* *********************************************************
         * BEGIN  - HEADER
        ********************************************************* */
         
        $pdf->Ln(22);
         
        $pdf->SetFont('Arial','',12);
        
        $pdf->Cell(
            0,
            0, 
            utf8_decode( 'UNIVERSIDADE FEDERAL DO RIO GRANDE DO NORTE' ), 
            0, 
            0,
            'C'
        );
        
        $pdf->Ln(7);
         
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            0, 
            utf8_decode( 'CENTRO DE CIÊNCIAS SOCIAIS APLICADAS' ), 
            0, 
            0,
            'C'
        );
        
        $pdf->Ln(92);
        
        $pdf->SetFont('Arial','B',22);
        
        $pdf->MultiCell(
            100,
            10, 
            utf8_decode( 'XXI SEMINÁRIO DE PESQUISA DO CCSA' ), 
            'R',
            'C'
        );
        
        $pdf->Ln(0);
        
        $pdf->SetFont('Arial','',12);
        $pdf->SetDrawColor(0,0,0);
                
        $pdf->Cell(
            100,
            10, 
            utf8_decode( '4 A 8 DE MAIO DE 2016' ), 
            'R', 
            0,
            'C'
        );
        
        $pdf->Ln(7);
        
        $pdf->SetFont('Arial','',12);
        
        $pdf->Cell(
            100,
            10, 
            utf8_decode( 'Cidadania em tempos de intolerância' ), 
            'R', 
            0,
            'C'
        );
        
        $pdf->setY( $pdf->getY() - 8);
        $pdf->setX( $pdf->getX() + 100 );
        
        $pdf->SetFont('Arial','B',22);
        
        $pdf->Cell(
            0,
            0, 
            utf8_decode( 'PROGRAMAÇÃO' ), 
            0, 
            0,
            'C'
        );
        
        $pdf->Ln(105);
        
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            0, 
            utf8_decode( 'NATAL/RN' ), 
            0, 
            0,
            'C'
        );
        
        $pdf->Ln(5);
        
        $pdf->SetFont('Arial','',12);
         
        $pdf->Cell(
            0,
            0, 
            utf8_decode( '2016' ), 
            0, 
            0,
            'C'
        );
        
        
        /* *********************************************************
        * END - HEADER
        ********************************************************* */
        
        /* Setting Margin */
        $pdf->SetMargins(22, 22);
        
        /* 
         * Blank page
        */
        $pdf->addPage('P','A4');
        
        
        /* 
         * Presentation
        */
        $pdf->addPage('P','A4');
        
        // Begin with regular font
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'APRESENTAÇÃO' ), 
            'TRBL', 
            0,
            'C',
            false
        );
        
        $pdf->Ln(20);
        
        $pdf->SetFont('Arial','',11);
        
        $pdf->MultiCell(
            0,
            5, 
            utf8_decode('O XX Seminário de Pesquisa do CCSA a ser realizado no período de 4 a 8 de maio de 2015, com tema "Construindo saberes para promoção do desenvolvimento e da democracia" objetiva tornar acessível à comunidade universitária a produção científica existente no CCSA por meio da divulgação dos trabalhos apresentados; estimular a comunidade acadêmica do CCSA para a prática da pesquisa; contribuir para o desenvolvimento da pesquisa e da reflexão teórico-metodológica no campo das Ciências Sociais Aplicadas; abrir espaço para interlocução com outras áreas do conhecimento. '),  
            0,
            'J',
            false
        );
        
        $pdf->Ln(5);
        
        $pdf->MultiCell(
            0,
            5, 
            utf8_decode('A ampla e diversificada programação mostra a vitalidade da comunidade acadêmica do CCSA para produção e disseminação do conhecimento ao tempo em que cria oportunidades de interlocução de diferentes saberes entre as diversas áreas das Ciências Sociais Aplicadas (Direito, Economia, Administração, Serviço Social, Ciências Contábeis, Turismo e Biblioteconomia). Durante o XX Seminário de Pesquisa do CCSA serão realizadas conferências, mesas redondas, trabalhos na forma de comunicação oral e/ou pôster, minicursos em diferentes temáticas das ciências sociais aplicadas, além de várias mostras – Mostra de Extensão, Exposição de Fotografias e oficinas. '),  
            0,
            'J',
            false
        );
        
        $pdf->Ln(5);
        
        $pdf->MultiCell(
            0,
            5, 
            utf8_decode('A construção de um evento da magnitude do XX Seminário de Pesquisa é uma construção de muitos. Assim, agradecemos aos nossos conferencistas, comissão científica, professores, coordenadores e debatedores de mesas redondas, técnicos e alunos de pósgraduação ministrantes dos minicursos, técnicos-administrativos que apoiam o evento e aos parceiros ADURN, CRA/RN, CREDISUPER, PROEX e a toda a comunidade do CCSA e da sociedade de um modo geral que prestigiam o Seminário e, em especial, à Comissão Organizadora que trabalhou com afinco para fazer da vigésima edição do Seminário de Pesquisa do CCSA um grande evento científico.'),  
            0,
            'J',
            false
        );
        
        $pdf->Ln(16);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'Natal, 22 de maio de 2016' ), 
            '', 
            0,
            'R',
            false
        );
        
        $pdf->Ln(16);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'Maria Arlete Duarte de Araújo' ), 
            '', 
            0,
            'R',
            false
        );
        
        $pdf->Ln(5);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'Diretora do CCSA' ), 
            '', 
            0,
            'R',
            false
        );
        
        $pdf->Ln(16);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'Adilson de Lima Tavares' ), 
            '', 
            0,
            'R',
            false
        );
        
        $pdf->Ln(5);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'Vice-Diretor do CCSA' ), 
            '', 
            0,
            'R',
            false
        );
        
        
        /* 
         * ORGANIZATION COMISSION
        */
        $pdf->addPage('P','A4');
        
        
        
        
        
        
        /* 
         * Papers
        */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(220,220,220);
        $pdf->SetDrawColor(160,160,160);
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'COMUNICAÇÃO ORAL - ARTIGOS' ), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(10);
        
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            JOIN 
                thematicgroup AS tg 
            ON
                tg.id = sr.thematicgroup_id
            WHERE
                sr.type = "gtpaper"  
            ORDER BY 
                sr.date, 
                sr.starthour,
                sr.local,
                tg.name
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );
        
        $date = "";

        foreach($records as $r) :
            
            /* 
             * Date Header
            */
            if($date == "" || $date != $r->date) :
            
                $date = $r->date;
            
                $pdf->SetFont('Arial','B',12);
                
                $pdf->Ln(5);
            
                $pdf->Cell(
                    45,
                    10, 
                    utf8_decode('> '.date('d/m/Y',strtotime($r->date)) ), 
                    '', 
                    0,
                    'L',
                    false
                );
                
                $pdf->Ln(10);
            
            endif;
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Ln(4);
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( 'GT - '.mb_strtoupper($r->thematicgroup->name,'UTF-8') ),  
                0,
                'L',
                false
            );
            
            $pdf->Ln(0);
            $pdf->SetFont('Arial','',9);
            
            /* Get Coordinators */
            $users = $r->thematicgroup->sharedUserList;
            $usersText = "";
            $usersCount = 0;
            
            foreach($users as $u ) : 
            
                if(!$usersCount) :
                    
                    $usersText .= $u->name;
                
                elseif($usersCount === count($users)-1) :    
                
                    $usersText .= ' e '.$u->name.'.';
                
                else :
                
                    $usersText .= ', '.$u->name;
                
                endif;
                
                $usersCount++;
            
            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( trim($usersText) ), 
                0, 
                'L',
                false
            );
            
            $pdf->Ln(-2);
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                10, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                10, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                10, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                100,
                10, 
                utf8_decode( $r->local ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->Ln(8);
            
            $pdf->SetFillColor(240,240,240);
            
            $pdf->Cell(
                0,
                0, 
                '', 
                'B', 
                0,
                'L',
                true
            );
            
            $pdf->SetFillColor(220,220,220);
            $pdf->SetDrawColor(160,160,160);
            
            $pdf->Ln(4);

            /* Getting papers from record */
            foreach($r->ownPaperList as $p) : /* Need be ordered? */
            
                $pdf->SetFont('Arial','B',9);
            
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase( trim($p->title) ) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','',9);
                
                /* Get authors */
                $users = explode('||', $p->authors);
                $usersText = "";
                $usersCount = 0;
                
                foreach($users as $u) : 
                
                    if(!$usersCount) :
                        
                        $usersText .= $u;
                    
                    elseif($usersCount === count($users)-1) :    
                    
                        $usersText .= ' e '.$u.'.';
                    
                    else :
                    
                        $usersText .= ', '.$u;
                    
                    endif;
                    
                    $usersCount++;
                
                endforeach;
                
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase(trim($usersText)) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->Ln(3);
            
            endforeach;
        
        endforeach;
        
        
        /* 
         * Pôsters
        */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(220,220,220);
        $pdf->SetDrawColor(160,160,160);
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'COMUNICAÇÃO ORAL - PÔSTERES' ), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(10);
        
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            JOIN 
                thematicgroup AS tg 
            ON
                tg.id = sr.thematicgroup_id
            WHERE
                sr.type = "gtposter"  
            ORDER BY 
                sr.date, 
                sr.starthour,
                sr.local,
                tg.name 
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );
        
        $date = "";

        foreach($records as $r) :
            
            /* 
             * Date Header
            */
            if($date == "" || $date != $r->date) :
            
                $date = $r->date;
            
                $pdf->SetFont('Arial','B',12);
                
                $pdf->Ln(5);
            
                $pdf->Cell(
                    0,
                    10, 
                    utf8_decode( '> '.date('d/m/Y',strtotime($r->date)) ), 
                    '', 
                    0,
                    'L',
                    false
                );
                
                $pdf->Ln(10);
                
            endif;
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Ln(4);
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( 'GT - '.mb_strtoupper($r->thematicgroup->name) ),  
                0,
                'L',
                false
            );
            
            $pdf->Ln(0);
            $pdf->SetFont('Arial','',9);
            
            /* Get Coordinators */
            $users = $r->thematicgroup->sharedUserList;
            $usersText = "";
            $usersCount = 0;
            
            foreach($users as $u ) : 
            
                if(!$usersCount) :
                    
                    $usersText .= $u->name;
                
                elseif($usersCount === count($users)-1) :    
                
                    $usersText .= ' e '.$u->name.'.';
                
                else :
                
                    $usersText .= ', '.$u->name;
                
                endif;
                
                $usersCount++;
            
            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( trim($usersText) ), 
                0, 
                'L',
                false
            );
            
            $pdf->Ln(-2);
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                10, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                10, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                10, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                100,
                10, 
                utf8_decode( $r->local ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->Ln(8);
            
            $pdf->SetFillColor(240,240,240);
            
            $pdf->Cell(
                0,
                0, 
                '', 
                'B', 
                0,
                'L',
                true
            );
            
            $pdf->SetFillColor(220,220,220);
            $pdf->SetDrawColor(160,160,160);
            
            $pdf->Ln(4);

            /* Getting posters from record */
            foreach($r->ownPosterList as $p) : 
            
                $pdf->SetFont('Arial','B',9);
            
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase( trim($p->title) ) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','',9);
                
                /* Get authors */
                $users = explode('||', $p->authors);
                $usersText = "";
                $usersCount = 0;
                
                foreach($users as $u) : 
                
                    if(!$usersCount) :
                        
                        $usersText .= $u;
                    
                    elseif($usersCount === count($users)-1) :    
                    
                        $usersText .= ' e '.$u.'.';
                    
                    else :
                    
                        $usersText .= ', '.$u;
                    
                    endif;
                    
                    $usersCount++;
                
                endforeach;
                
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase( trim($usersText) ) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->Ln(3);
            
            endforeach;
        
        endforeach;
        
        
        /* 
         * Teaching Cases
        */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(
            220,
            220,
            220
        );
        
        $pdf->SetDrawColor(
            160,
            160,
            160
        );
        
        $pdf->SetFont(
            'Arial',
            'B',
            12
        );
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'COMUNICAÇÃO ORAL - CASOS PARA ENSINO' ), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(10);
        
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            WHERE
                sr.type = "gtteachingcase"  
            ORDER BY 
                sr.date, 
                sr.starthour,
                sr.local
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );
        
        $tctg = R::findOne(
            'thematicgroup',
            'name="Casos para Ensino"'
        );
        
        
        $date = "";

        foreach($records as $r) :
            
            /* 
             * Date Header
            */
            if($date == "" || $date != $r->date) :
            
                $date = $r->date;
            
                $pdf->SetFont(
                    'Arial',
                    'B',
                    12
                );
                
                $pdf->Ln(5);
                
                $pdf->Cell(
                    0,
                    10, 
                    utf8_decode( '> '.date('d/m/Y',strtotime($r->date)) ), 
                    '', 
                    0,
                    'L',
                    false
                );
                
                $pdf->Ln(10);
                
            endif;
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                0,
                5, 
                utf8_decode( $tctg->name ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->Ln(5);
            $pdf->SetFont('Arial','',9);
            
            /* Get Coordinators */
            $users = $tctg->sharedUserList;
            $usersText = "";
            $usersCount = 0;
            
            foreach($users as $u ) : 
            
                if(!$usersCount) :
                    
                    $usersText .= $u->name;
                
                elseif($usersCount === count($users)-1) :    
                
                    $usersText .= ' e '.$u->name.'.';
                
                else :
                
                    $usersText .= ', '.$u->name;
                
                endif;
                
                $usersCount++;
            
            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( trim($usersText) ), 
                0, 
                'L',
                false
            );
            
            $pdf->Ln(0);
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                5, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                5, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                5, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                100,
                5, 
                utf8_decode( $r->local ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->Ln(6);
            
            $pdf->SetFillColor(240,240,240);
            
            $pdf->Cell(
                0,
                0, 
                '', 
                'B', 
                0,
                'L',
                true
            );
            
            $pdf->SetFillColor(220,220,220);
            $pdf->SetDrawColor(160,160,160);
            
            $pdf->Ln(4);

            /* Getting teaching cases from record */
            foreach($r->ownTeachingcaseList as $p) : 
            
                $pdf->SetFont('Arial','B',9);
            
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase( trim($p->title) ) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','',9);
                
                /* Get authors */
                $users = explode('||', $p->authors);
                $usersText = "";
                $usersCount = 0;
                
                foreach($users as $u) : 
                
                    if(!$usersCount) :
                        
                        $usersText .= $u;
                    
                    elseif($usersCount === count($users)-1) :    
                    
                        $usersText .= ' e '.$u.'.';
                    
                    else :
                    
                        $usersText .= ', '.$u;
                    
                    endif;
                    
                    $usersCount++;
                
                endforeach;
                
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase(trim($usersText)) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->Ln(3);
            
            endforeach;
        
        endforeach;
        
        
        /* 
         * Minicourses
         * - Ordenar por nome, colocar datas...
        */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(
            220,
            220,
            220
        );
        
        $pdf->SetDrawColor(
            160,
            160,
            160
        );
        
        $pdf->SetFont(
            'Arial',
            'B',
            12
        );
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode( 'MINICURSOS' ), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(10);
        
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            JOIN
                minicourse AS m 
            ON 
                m.id = sr.minicourse_id
            WHERE
                sr.type = "minicourse"  
            ORDER BY 
                m.title, 
                sr.starthour,
                sr.local
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );

        foreach($records as $r):
        
            $pdf->Ln(5);

            $pdf->SetFont('Arial','B',9);
        
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($r->minicourse->title) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            /* Get authors */
            $users = explode('||', $r->minicourse->expositor);
            $usersText = "";
            $usersCount = 0;
            
            foreach($users as $u) : 
            
                if(!$usersCount) :
                    
                    $usersText .= $u;
                
                elseif($usersCount === count($users)-1) :    
                
                    $usersText .= ' e '.$u.'.';
                
                else :
                
                    $usersText .= ', '.$u;
                
                endif;
                
                $usersCount++;
            
            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode(  titleCase( trim($usersText) ) ), 
                0, 
                'L',
                true
            );
            
            $dss = $r->minicourse->with('ORDER BY date ASC')->sharedMinicoursedayshiftList;
            $dsstring = "";
            $count = 0;
            
            foreach($dss as $ds) : 

                if(count($dss)-1 == $count++): 
                
                    $dsstring .= ' e '.date('d/m/Y',strtotime($ds->date)).'.';
                
                elseif($count-1 != 0) : 
                
                    $dsstring .= ', '.date('d/m/Y',strtotime($ds->date));
                    
                else : 
                
                    $dsstring .= date('d/m/Y',strtotime($ds->date));
                
                endif;
            
            endforeach;
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( trim($dsstring) ), 
                0, 
                'L',
                true
            );

            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                5, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                5, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                5, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                104,
                5, 
                utf8_decode( $r->minicourse->consolidatedlocal ), 
                '', 
                0,
                'L',
                true
            );

            $pdf->Ln(3);
        
        endforeach;

        
        /**
         * ROUNDTABLES
         */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(220,220,220);
        $pdf->SetDrawColor(160,160,160);
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode('MESAS REDONDAS'), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(10);

        /** Getting dates */
        $dss = R::find(
            'roundtabledayshift',
            'ORDER BY date ASC'  
        );

        $dates = array();

        foreach($dss as $d) :
         
            if(! in_array($d->date, $dates)) : 
                $dates[] = $d->date;
            endif;
        
        endforeach;

        foreach($dates as $ds) :
        
            /** Showing date */
            $pdf->SetFont('Arial','B',12);
            
            $pdf->Ln(2);
        
            $pdf->Cell(
                0,
                10, 
                utf8_decode( '> '.date('d/m/Y',strtotime($ds)) ), 
                '', 
                0,
                'L',
                false
            );
            
            $pdf->Ln(10);
            
            /** Getting records */           
            $rows = R::getAll('
                SELECT 
                    sr.*
                FROM
                    schedulerecord AS sr 
                JOIN 
                    roundtable AS rt 
                ON
                    rt.id = sr.roundtable_id
                JOIN 
                    roundtabledayshift AS rtds 
                ON
                    rtds.id = rt.roundtabledayshift_id
                WHERE 
                    sr.type = "roundtable"
                    AND rtds.date = ?
                ORDER BY 
                    rtds.date, 
                    sr.starthour,
                    sr.local,
                    rt.title 
                    ASC
            ', [$ds]);
            
            $records = R::convertToBeans(
                'schedulerecord',
                $rows
            );
            
            foreach($records as $r) :
            
                /* Roundtable */
                $pdf->SetFont('Arial','B',9);
            
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase( trim($r->roundtable->title) ) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','',9);
                
                /* Get authors */
                $users = explode('||', $r->roundtable->debaters);
                $usersText = "";
                $usersCount = 0;
                
                foreach($users as $u) : 
                
                    if(!$usersCount) :
                        
                        $usersText .= $u;
                    
                    elseif($usersCount === count($users)-1) :    
                    
                        $usersText .= ' e '.$u.'.';
                    
                    else :
                    
                        $usersText .= ', '.$u;
                    
                    endif;
                    
                    $usersCount++;
                
                endforeach;
                
                $pdf->MultiCell(
                    0,
                    5, 
                    utf8_decode( titleCase( trim($usersText.' -  Coordenador(a): '.$r->roundtable->coordinator) ) ), 
                    0, 
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','B',9);
                
                $pdf->Cell(
                    14,
                    5, 
                    utf8_decode( 'Horário:' ), 
                    '', 
                    0,
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','',9);
                
                $pdf->Cell(
                    36,
                    5, 
                    utf8_decode( $r->starthour." - ".$r->endhour ), 
                    '', 
                    0,
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','B',9);
                
                $pdf->Cell(
                    12,
                    5, 
                    utf8_decode( 'Local: ' ), 
                    '', 
                    0,
                    'L',
                    true
                );
                
                $pdf->SetFont('Arial','',9);
                
                $pdf->Cell(
                    104,
                    5, 
                    utf8_decode( $r->roundtable->consolidatedlocal ), 
                    '', 
                    0,
                    'L',
                    true
                );
                
                $pdf->Ln(9);
            
            endforeach;
            
        endforeach;

        
        /**
         * WORKSHOPS
         */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(220,220,220);
        $pdf->SetDrawColor(160,160,160);
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode('OFICINAS'), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(15);
        
        /** Getting records */           
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            JOIN 
                workshop AS ws 
            ON
                ws.id = sr.workshop_id
            JOIN
                workshop_workshopdayshift AS wwsds
            ON 
                wwsds.workshop_id = ws.id 
            JOIN
                workshopdayshift AS wsds
            ON 
                wsds.id = wwsds.workshopdayshift_id
            WHERE 
                sr.type = "workshop"
            ORDER BY 
                wsds.date,
                sr.starthour,
                ws.title
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );
        
        foreach($records as $r) :
        
            /* Roundtable */
            $pdf->SetFont('Arial','B',9);
        
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($r->workshop->title) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            /* Get authors */
            $users = explode('||', $r->workshop->expositor);
            $usersText = "";
            $usersCount = 0;
            
            foreach($users as $u) : 
            
                if(!$usersCount) :
                    
                    $usersText .= $u;
                
                elseif($usersCount === count($users)-1) :    
                
                    $usersText .= ' e '.$u.'.';
                
                else :
                
                    $usersText .= ', '.$u;
                
                endif;
                
                $usersCount++;
            
            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($usersText) ) ), 
                0, 
                'L',
                true
            );
            
            $dates = '';
            $arrdts = array();
            
            $pdf->SetFont('Arial','B',9);
            
            foreach($r->workshop->with('ORDER BY date ASC')->sharedWorkshopdayshiftList as $wds) : 
                
                if(! in_array($wds->date , $arrdts)) : 
                    $arrdts[] = $wds->date;
                    $dates .= date('d/m/Y',strtotime($wds->date)).' ';
                endif;            

            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($dates) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                5, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                5, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                5, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                104,
                5, 
                utf8_decode( $r->workshop->consolidatedlocal ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->Ln(9);
        
        endforeach;
        
        
        /**
         * CONFERENCES
         */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(220,220,220);
        $pdf->SetDrawColor(160,160,160);
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode('CONFERÊNCIAS'), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(15);
        
        /** Getting records */           
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            JOIN 
                conference AS c 
            ON
                c.id = sr.conference_id
            JOIN
                conferencedayshift AS cds
            ON 
                cds.id = c.conferencedayshift_id
            WHERE 
                sr.type = "conference"
            ORDER BY 
                cds.date,
                sr.starthour,
                c.title
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );
        
        foreach($records as $r) :
        
            /* Roundtable */
            $pdf->SetFont('Arial','B',9);
        
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($r->conference->title) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            /* Get authors */
            $users = explode('||', $r->conference->lecturer);
            $usersText = "";
            $usersCount = 0;
            
            foreach($users as $u) : 
            
                if(!$usersCount) :
                    
                    $usersText .= $u;
                
                elseif($usersCount === count($users)-1) :    
                
                    $usersText .= ' e '.$u.'.';
                
                else :
                
                    $usersText .= ', '.$u;
                
                endif;
                
                $usersCount++;
            
            endforeach;
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($usersText) ) ), 
                0, 
                'L',
                true
            );
            
            $dates = '';
            $arrdts = array();
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim( date('d/m/Y',strtotime($r->conference->conferencedayshift->date) ) ) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                5, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                5, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                5, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                104,
                5, 
                utf8_decode( $r->conference->local ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->Ln(9);
        
        endforeach;
        
        
        /**
         * OTHERS ACTIVITIES
         */
        $pdf->addPage('P','A4');
        
        $pdf->SetFillColor(220,220,220);
        $pdf->SetDrawColor(160,160,160);
        $pdf->SetFont('Arial','B',12);
        
        $pdf->Cell(
            0,
            10, 
            utf8_decode('OUTRAS ATIVIDADES'), 
            'TRBL', 
            0,
            'C',
            true
        );
        
        $pdf->Ln(15);
        
        /** Getting records */           
        $rows = R::getAll('
            SELECT 
                sr.*
            FROM
                schedulerecord AS sr 
            WHERE 
                sr.type = "otheractivity"
            ORDER BY 
                sr.date,
                sr.starthour,
                sr.title
                ASC
        ');
        
        $records = R::convertToBeans(
            'schedulerecord',
            $rows
        );
        
        foreach($records as $r) :
        
            $pdf->SetFont('Arial','B',9);
        
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim($r->title) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim( strip_tags($r->description) ) ) ), 
                0, 
                'L',
                true
            );
            
            $dates = '';
            $arrdts = array();
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->MultiCell(
                0,
                5, 
                utf8_decode( titleCase( trim( date('d/m/Y',strtotime($r->date) ) ) ) ), 
                0, 
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                14,
                5, 
                utf8_decode( 'Horário:' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                36,
                5, 
                utf8_decode( $r->starthour." - ".$r->endhour ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','B',9);
            
            $pdf->Cell(
                12,
                5, 
                utf8_decode( 'Local: ' ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->SetFont('Arial','',9);
            
            $pdf->Cell(
                104,
                5, 
                utf8_decode( $r->local ), 
                '', 
                0,
                'L',
                true
            );
            
            $pdf->Ln(9);
        
        endforeach;
        
        $pdf->Output();  
        
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

            $papers = $tg->withCondition(' evaluation = "accepted" AND (scheduled IS NULL OR scheduled="no" ) ORDER BY title')->ownPaperList;
            $papersf = array();
            foreach ($papers as $paper){
                $papersf[] = array( 'id' => $paper->id, 'title' => $paper->title );
            }
            $data = array( 'type' => 'paper', 'papers' => $papersf );
            
            // reset list
            $papers = $tg->all()->ownPaperList;

        }else if($type=='poster'){

            $posters = $tg->withCondition(' evaluation = "accepted" AND (scheduled IS NULL OR scheduled="no" ) AND poster != "" ORDER BY title')->ownPosterList;
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