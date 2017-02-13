<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ThematicGroup extends Base 
{
    
    /*
     * Function : index()
     * Description : View to manage Thematic Groups Page
    */
    public function index()
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
                'form'
            ) 
        );
        
        
        /*
         * User is logged?
        */
        if ( !parent::_isLogged() ) : 
        
            redirect(
                base_url(
                    'dashboard/login'
                )
            );
        
        endif;
        
        
        /*
         * Loading logged user
        */
        $user = R::findOne(
            'user',
            'id=?',
            array(
                $this->session->userdata('user_id')
            )
        );
        
        
        /* 
         * User has capabilities?
        */
        if( ! parent::_hasCapabilities( $user, 'tg_view' ) ) : 
        
            redirect(base_url('dashboard'));
        
        endif;

        
        /* 
         * Loading views
        */
        $areas = R::find(
            'area',
            'ORDER BY name ASC'
        );
        
        $tgs = R::find(
            'thematicgroup'
        );

        $this->load->view('dashboard/header');
        
        $this->load->view('dashboard/template/menuAdministrator');
        
        $this->load->view(
            'dashboard/thematicgroup/manage',
            array(
                'success' => $this->session->flashdata('success'),
                'error' => $this->session->flashdata('error'),
                'validation' => $this->session->flashdata('validation'),
                'popform' => $this->session->flashdata('popform'),
                'thematicgroups' => $tgs,
                'areas' => $areas
            )
        );
        
        $this->load->view('dashboard/footer');

	}
    
    
    /*
     * Function : retrieveDetailsView()
     * Description : View that retrieve details of a thematic group
    */
    public function retrieveDetailsView()
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
                'form'
            ) 
        );
        
        
        /*
         * User is logged?
        */
        if ( !parent::_isLogged() ) : 
        
            redirect(
                base_url(
                    'dashboard/login'
                )
            );
        
        endif;
        
        
        /*
         * Loading logged user
        */
        $user = R::findOne(
            'user',
            'id=?',
            array(
                $this->session->userdata('user_id')
            )
        );  
        
        
        /* 
         * User has capabilities?
        */
        if( ! parent::_hasCapabilities( $user, 'tg_view' ) ) : 
        
            redirect(base_url('dashboard'));
        
        endif;
        
        
        /* 
         * Getting get data
        */
        $id = $this->input->get('id');
        
        
        /* 
         * Loading Thematic Group
        */
        $tg = R::findOne(
            'thematicgroup',
            'id=?',
            array(
                $id
            )
        );
          
          
        /* 
         * Loading Thematic Group
        */  
        $areas = R::find(
            'area',
            'ORDER BY name ASC'
        );

        
        /* 
         * Loading views
        */
        $this->load->view(
            'dashboard/thematicgroup/retrieveDetails',
            array(
                'tg' => $tg,
                'areas' => $areas
            )
        );

	}
    

    /*
     * Function : create()
     * Description : Create a new Thematic Group
    */
    public function create()
    {

        /*
         * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'rb', 
                'form_validation', 
                'session'
            ) 
        );
        
        $this->load->helper( 
            array( 
                'url' , 
                'security' 
             ) 
        );
        
        
        /*
         * It's a POST request?
        */
        if ( $this->input->server('REQUEST_METHOD') != 'POST' ) : 
        
            echo "Don't do that";
            exit;
        
        endif;
        
        
        /*
         * User is logged?
        */
        if ( !parent::_isLogged() ) : 
        
            redirect(
                base_url(
                    'dashboard/login'
                )
            );
        
        endif;
        
        
        /*
         * Loading logged user
        */
        $user = R::findOne(
            'user',
            'id=?',
            array(
                $this->session->userdata('user_id')
            )
        );
        
        
        /* 
         * User has capabilities?
        */
        if( ! parent::_hasCapabilities( $user, 'tg_create' ) ) : 
        
            redirect(base_url('dashboard'));
        
        endif;


        /* 
         * Setting form validation rules
        */
        $validation = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            ),
            array(
                'field' => 'area',
                'label' => 'Área',
                'rules' => 'required'
            ),
            array(
                'field' => 'syllabus',
                'label' => 'Ementa',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        
        /* 
         * All right with form?
        */
        if ( ! $this->form_validation->run() ) : 
            
            $this->session->set_flashdata(
                'validation', 
                array(
                    'name' => form_error('name'),
                    'area' => form_error('area'),
                    'syllabus' => form_error('syllabus')
                )
            );
            
             $this->session->set_flashdata(
                'popform', 
                 array(
                    'name' => set_value('name'),
                    'area' => set_value('area'),
                    'syllabus' => set_value('syllabus')
                )
            );
            
            redirect(
                base_url(
                    'dashboard/thematicgroup'
                 )
            );
            exit;
        
        endif;
        
        
        /* 
         * Getting post data
        */
        $name = $this->input->post('name');
        $area = $this->input->post('area');
        $syllabus = $this->input->post('syllabus');
        $isNotListable = $this->input->post('isNotListable');
        
        
        /* 
         * Creating Thematic Group
        */
        $tg = R::dispense("thematicgroup");
        $tg->name = $name;
        $tg->syllabus = $syllabus;
        $tg->isListable = 'Y';
        
        if ( $isNotListable ) : 
        
            $tg->isListable = 'N';

        endif;
        
        $a = R::findOne(
            'area',
            'id=?', 
            array(
                $area
            )
        );
        
        $a->ownThematicgroupList[] = $tg;
        
        R::store($a);
        
        
        /* 
         * Success message
        */
        $this->session->set_flashdata(
            'success',
            'O grupo temático foi adicionado com sucesso.'
        );
        
        redirect(base_url('dashboard/thematicgroup'));
        
        exit;

    }
    
    
    /*
     * Function : update()
     * Description : Update a Thematic Group
    */
    public function update()
    {
        
        /* 
         * Loading libraries and helpers
        */
        $this->load->library( 
            array(
                'rb', 
                'form_validation', 
                'session'
            ) 
        );
        
        $this->load->helper( 
            array( 
                'url' , 
                'security' 
            ) 
        );
        
        
        /* 
         * Is it a post request?
        */
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        
        /*
         * User is logged?
        */
        if ( !parent::_isLogged() ) : 
        
            redirect(
                base_url(
                    'dashboard/login'
                )
            );
        
        endif;
        
        
        /*
         * Loading logged user
        */
        $user = R::findOne(
            'user',
            'id=?',
            array(
                $this->session->userdata('user_id')
            )
        );  
        
        
        /* 
         * User has capabilities?
        */
        if( ! parent::_hasCapabilities( $user , 'tg_update' ) ) : 
        
            redirect(base_url('dashboard'));
        
        endif;
        

        /* 
         * Setting form validation fields
        */
        $validation = array(
            array(
                'field' => 'name',
                'label' => 'Nome',
                'rules' => 'required'
            ),
            array(
                'field' => 'area',
                'label' => 'Área',
                'rules' => 'required'
            ),
            array(
                'field' => 'syllabus',
                'label' => 'Ementa',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        
        /* 
         * All right with form?
        */
        if ( ! $this->form_validation->run() ) : 
            
             $this->session->set_flashdata(
                 'error',
                 'Você precisa preencher todos os campos obrigatórios para poder atualizar um grupo temático. Repita a operação.'
             );
            
            redirect(base_url('dashboard/thematicgroup'));
            exit;
            
        endif;
        
        
        /* 
         * Getting post data
        */
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $syllabus = $this->input->post('syllabus');
        $area = $this->input->post('area');
        $isNotListable = $this->input->post('isNotListable');

        /* 
         * Loading Area
        */
        $area = R::findOne(
            'area',
            'id=?', 
            array(
                $area
            ) 
        );
        
        
        /* 
         * Loading Thematic Group
        */
        $tg = R::findOne(
            'thematicgroup',
            'id=?', 
            array(
                $id
            ) 
        );
        
        
        /* 
         * Updating Thematic Group fields
        */
        $tg->area = $area;
        $tg->name = $name;
        $tg->syllabus = $syllabus;
        $tg->isListable = 'Y';
        
        if ( $isNotListable ) : 
        
            $tg->isListable = 'N';
        
        endif;
        
        R::store($tg);


        /* 
         * All right!
        */
        $this->session->set_flashdata(
            'success',
            'O grupo temático foi <b>atualizado</b> com sucesso.'
        );
        
        redirect(base_url('dashboard/thematicgroup'));
        
        exit;

    }
    
    public function delete(){

        $this->load->library( array('rb', 'session', 'form_validation') );
        $this->load->helper( array( 'url' , 'security' ) );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
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

        $tg = R::findOne('thematicgroup','id=?', array($id) );
        R::trash($tg);

        $this->session->set_flashdata('success','O grupo temático foi <b>removido</b> com sucesso.');
        redirect(base_url('dashboard/thematicgroup'));
        exit;

    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */