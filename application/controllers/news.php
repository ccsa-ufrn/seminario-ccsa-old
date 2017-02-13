<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }

    public function index(){

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

        $news = R::find('news','ORDER BY created_at DESC', array('%Y-%m-%d %H:%i:%s'));

        $data = array(
                    'success' => $this->session->flashdata('success'),
                    'error' => $this->session->flashdata('error'),
                    'validation' => $this->session->flashdata('validation'),
                    'popform' => $this->session->flashdata('popform'),
                    'news' => $news
                );

        $this->load->view('dashboard/header');
        $this->load->view('dashboard/template/menuAdministrator');
        $this->load->view('dashboard/news/manage',$data);
        $this->load->view('dashboard/footer');

	}
    
    public function retrieveDetailsView(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form') );
        
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
        
        $id = $this->input->get('id');
        
        if(!is_numeric($id)){
            echo "The id is not numeric. Do not do that! :D";
            exit;
        }
        
        $news = R::findOne('news','id=?',array($id));

        $data = array(
                    'news' => $news
                );

        $this->load->view('dashboard/news/retrieveDetails',$data);

	}

    public function create(){

        $this->load->library( array('rb','session','form_validation','session') );
        $this->load->helper( array('url','security','date','typography') );
        
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
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'title',
                'label' => 'Título',
                'rules' => 'required'
            ),
            array(
                'field' => 'text',
                'label' => 'Texto',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        if(!$this->form_validation->run()){
            $this->session->set_flashdata(
                    'validation', 
                    array(
                            'title' => form_error('title'),
                            'text' => form_error('text')
                        )
                );
            
             $this->session->set_flashdata(
                    'popform', 
                    array(
                            'title' => set_value('title'),
                            'isFixed' => set_value('isFixed'),
                            'text' => set_value('text')
                        )
                );
            
            redirect(base_url('dashboard/news'));
            exit;
        }
        
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $name = $this->input->post('title');
        $isFixed = $this->input->post('isFixed');
        $text = auto_typography($this->input->post('text'));
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $news = R::dispense('news');
        $news->title = $name;
        $news->isFixed = 'N';
        $news->wasFixed = 'N';
        
        if ( isset($isFixed) && $isFixed == true ) : 
        
           $news->isFixed = 'Y';
           $news->wasFixed = 'Y';
           
           /* 
            * Removing the others fixed news
            */ 
           $fixedNews = R::find(
               'news',
               ' is_fixed = "Y" '    
           );
           
           foreach ( $fixedNews as $n ) :
           
                $n->isFixed = 'N';
                R::store($n);
           
           endforeach;
        
        endif;
        
        
        $news['text'] = $text;
        $news['created_by'] = R::findOne('user','id=?',array($this->session->userdata('user_id')));
        $news['created_at'] = mdate('%Y-%m-%d %H:%i:%s');

        $id = R::store($news);

        $this->session->set_flashdata('success','A notícia foi adicionada com sucesso.');
        redirect(base_url('dashboard/news'));
        exit;

    }

    public function update(){

        $this->load->library( array('rb', 'form_validation','session') );
        $this->load->helper( array( 'url' , 'security' , 'typography' ) );
        
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
            BEGIN - VALIDATION
        ============================================ */
        
        $validation = array(
            array(
                'field' => 'title',
                'label' => 'Título',
                'rules' => 'required'
            ),
            array(
                'field' => 'text',
                'label' => 'Texto',
                'rules' => 'required'
            )
        );
        
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules($validation);
        customErrorMessages($this->form_validation);
        
        // Verifyng validation error
        if(!$this->form_validation->run()){
             $this->session->set_flashdata('error','Você precisa preencher todos os campos obrigatórios para poder atualizar uma notícia. Repita a operação.');
            
            redirect(base_url('dashboard/news'));
            exit;
        }
        /* ===========================================
            END - VALIDATION
        ============================================ */
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $id = $this->input->post('id');
        
        if(!is_numeric($id)){
            echo "The ID has to be numeric. Do not do that! :D";
            exit;
        }
        
        $title = $this->input->post('title');
        $text = auto_typography($this->input->post('text'));
        $isFixed = $this->input->post('isFixed');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        $news = R::findOne('news','id=?',array($id));
        
        $news->title = $title;
        
        if ( isset($isFixed) && $isFixed == true ) : 
        
           $news->isFixed = 'Y';
           $news->wasFixed = 'Y';
           
           /* 
            * Removing the others fixed news
            */ 
           $fixedNews = R::find(
               'news',
               ' is_fixed = "Y" '    
           );
           
           foreach ( $fixedNews as $n ) :
           
                $n->isFixed = 'N';
                R::store($n);
           
           endforeach;
        
        elseif ( isset($isFixed) && $isFixed == false ) : 
        
            $news->isFixed = 'N';
        
        endif;
        
        $news->text = $text;

        R::store($news);

        $this->session->set_flashdata('success','A notícia foi <b>atualizado</b> com sucesso.');
        redirect(base_url('dashboard/news'));
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

        $news = R::findOne('news','id=?', array($id) );
        R::trash($news);

        $this->session->set_flashdata('success','A notícia foi <b>removida</b> com sucesso.');
        redirect(base_url('dashboard/news'));
        exit;

    }

    public function mainView(){

        $this->load->library( array('rb','session') );
        $this->load->helper( array('url') );

        $data = array(
                'news' => R::find('news','ORDER BY created_at DESC')
            );
        
        $this->load->view('template/header');
        $this->load->view('news',$data);
        $this->load->view('template/footer');

    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */