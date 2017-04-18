<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Poster extends Base {

	public function getSource() {
		$this->load->library(['rb']);

		$this->output->set_header('Content-Type: application/json');

		$page = $this->input->get('page');
		$search = $this->input->get('search');

		$papers = R::getAll('
      SELECT
          id, title, authors, cernn, certgen
      FROM
          poster
      WHERE
          ( title LIKE ?
          OR authors LIKE ? )
          AND cernn = "yes"
      ORDER BY
          title ASC
      LIMIT 10 OFFSET '.($page-1)*10,
      array('%'.$search.'%', '%'.$search.'%')
    );

		$papers = R::convertToBeans('paper', $papers );

		echo json_encode(
      array(
        'status' => 'success',
        'data' => R::exportAll($papers),
        'numberResults' => R::count('poster', ' (title LIKE ? OR authors LIKE ?) AND cernn = "yes" ',
          array('%'.$search.'%', '%'.$search.'%'))
      )
    );
		exit;
	}

	public function getPoster() {
		$this->load->library(['rb']);

		$posters = R::find('poster', 'evaluation = "accepted" ');

		$this->load->view('dashboard/header');
		$this->load->view('dashboard/template/menuAdministrator');
		$this->load->view('dashboard/poster/getp', ['posters' => $posters]);
		$this->load->view('dashboard/footer');
	}

	public function uploadLaterDo() {
		$this->load->library( array('rb','session') );
		$this->load->helper( array('string') );

		$id = $this->input->post('id-poster');
		$poster = $this->input->post('poster');

		$config['upload_path'] = './assets/upload/posters/';
		$config['file_name'] = random_string('unique');
		$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|odp|ppt|pptx|doc|docx';

		$this->load->library('upload', $config);

		if (! $this->upload->do_upload('poster')){

			$this->session->set_flashdata('error','O pôster não pode ser enviado, talvez o tipo de arquivo não seja permitido, somente pdf,ppt,pptx e odp são permitidos. Caso persista, envie uma mensagem para o suporte informando o problema.');
			redirect(base_url('dashboard/poster/submit'));

			exit;
		} else{

			$data = $this->upload->data();

			$poster = R::findOne(
        'poster',
        ' id = ? ',
        array($id)
      );

			$poster->poster = $data['file_name'];

			R::store($poster);

			$this->session->set_flashdata('success','Parabéns, o pôster foi enviado com sucesso.');
			redirect(base_url('dashboard/poster/submit'));

			exit;
		}

	}

  public function uploadLaterArtDo() {
		$this->load->library( array('rb','session') );
		$this->load->helper( array('string') );

		$id = $this->input->post('id-poster');
		$poster = $this->input->post('poster');

		$config['upload_path'] = './assets/upload/posters/art/';
		$config['file_name'] = random_string('unique');
		$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|odp|ppt|pptx|doc|docx';

		$this->load->library('upload', $config);

		if (! $this->upload->do_upload('poster')){

			$this->session->set_flashdata('error','O pôster não pode ser enviado, talvez o tipo de arquivo não seja permitido,
        somente pdf,ppt,pptx e odp são permitidos. Caso persista, envie uma mensagem para o suporte informando o problema.');
			redirect(base_url('dashboard/poster/submit'));

			exit;
		} else{

			$data = $this->upload->data();

			$poster = R::findOne(
        'poster',
        ' id = ? ',
        array($id)
      );

			$poster->art = $data['file_name'];

			R::store($poster);

			$this->session->set_flashdata('success','Parabéns, a arte do pôster foi enviada com sucesso.');
			redirect(base_url('dashboard/poster/submit'));

			exit;
		}

	}

	public function uploadPoster()
	    {

		$this->load->library( array('rb') );
		$this->load->helper( array('string') );

		$config['upload_path'] = './assets/upload/posters/';
		$config['file_name'] = random_string('unique');
		$config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';

		$this->load->library('upload', $config);
		// 		upload

		if ( ! $this->upload->do_upload() ){

			$log = R::dispense('log');
			$log['msg'] = (string) $this->upload->display_errors();
			R::store($log);


			/* ================================================
			BEGIN - PREAPERING TO RETURN JSON OF ERROR
			            ================================================ */

			// 			If there is any error, then prop 'error' will be 'true', and message will be set
			            $info = new StdClass;
			$info->error = true;
			$info->message = (string) $this->upload->display_errors();

			echo json_encode(array("file" => $info));


			/* ================================================
			END - PREAPERING TO RETURN JSON OF ERROR
			            ================================================ */

			exit;

		}
		else{


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



	/*
	* Function : submitView()
	     * Description : View to submit posters
	    */
		public function submitView()
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
		                'form',
		                'date'
		            )
		        );



		/*
		* User is logged?
		        */
		        if ( !parent::_isLogged() ) :

		redirect(
		                base_url(
		                    'dashboard'
		                )
		            );

		endif;



		/*
		* Getting max date to submit papers
		        */
		        $config = R::findOne(
		            'configuration',
		            ' name= "max_date_poster_submission" '
		        );


		/*
		* Getting max date to submit poster file
		        */
		        $config2 = R::findOne(
		            'configuration',
		            ' name= "max_date_poster_file_submission" '
		        );



		/*
		* Date is less or equal to max date?
		        */
		        $open = false;

		if ( dateleq( mdate('%Y-%m-%d') , $config->value ) ) :

		$open = true;

		endif;



		/*
		* Retrieving user
		        */
		        $user = R::findOne(
		            'user','id=?',
		            array(
		                $this->session->userdata('user_id')
		            )
		        );



		/*
		* System needs payment to send works?
		        */
		        $needPayment = R::findOne(
		            'configuration',
		            ' name = "need_payment" '
		        );

		$paid = true;

		if ( $needPayment->value == 'true' ) :

		if( $user->paid == 'no' ) :

		$paid = false;

		endif;

		endif;



		/*
		* Loading views
		        */
		        $this->load->view('dashboard/header');

		if( $this->session->userdata('user_type') == 'administrator') :

		$this->load->view('dashboard/template/menuAdministrator');

		elseif ( $this->session->userdata('user_type') == 'coordinator' ) :

		$this->load->view('dashboard/template/menuCoordinator');

		else :

		if ( $this->session->userdata('user_type')=='instructor' ) :

		$this->load->view(
		                    'dashboard/template/menuInstructor',
		                    array(
		                        'active' => 'submit-poster'
		                    )
		                );

		else :

		$this->load->view(
		                    'dashboard/template/menuStudent',
		                    array(
		                        'active' => 'submit-poster'
		                    )
		                );

		endif;

		endif;

		$this->load->view(
		            'dashboard/poster/submit',
		            array(
		                'success' => $this->session->flashdata('success'),
		                    'error' => $this->session->flashdata('error'),
		                    'validation' => $this->session->flashdata('validation'),
		                    'popform' => $this->session->flashdata('popform'),
		                    'tgs' => R::find('thematicgroup','is_listable = "Y" ORDER BY name ASC'),
		                    'posters' => R::find('poster','user_id=?',array($this->session->userdata('user_id'))),
		                    'date_limit' => array( 'config' => $config , 'open' => $open ),
		                    'date_limit_file' => $config2,
		                    'paid' => $paid
		            )
		        );

		$this->load->view('dashboard/footer');

	}

	public function verifyingAuthors($str,$limit){

		if($limit==0) return TRUE;

		$result = explode('||',$str);

		if(count($result)>$limit)
		            return FALSE;

		for ($i=0;$i<count($result);++$i){
			$test = explode("[",$result[$i]);

			if($test[0]=='' || $test[1]=='')
			                return FALSE;

		}

		// 		$limit == 0 then will not verify the quantity of authors, otherwise will verify
		        return TRUE;

	}

	public function create(){

		$this->load->library( array('session','rb', 'form_validation') );
		$this->load->helper( array('url','form','date') );

		// 		User logged in?
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

		// 		Retrieving user
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
		            )
		        );

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules($validation);
		customErrorMessages($this->form_validation);

		// 		Verifyng validation error
		        if(!$this->form_validation->run()){
			$this->session->set_flashdata(
			                    'validation',
			                    array(
			                            'title' => form_error('title'),
			                            'thematicgroup' => form_error('thematicgroup'),
			                            'authors' => form_error('authors'),
			                            'abstract' => form_error('abstract'),
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

		// 		Retrieving thematicgroup
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

		$tgs = $user->withCondition( ' is_listable = "Y" ORDER BY name ASC  ' )->sharedThematicgroupList;

		$data = array(
		                'tgs' => $tgs,
		                'success' => $this->session->flashdata('success'),
		                'error' => $this->session->flashdata('error')
		            );

		$this->load->view('dashboard/header');

		if($this->session->userdata('user_type')=='administrator'){
			$this->load->view('dashboard/template/menuAdministrator');
		}
		else if($this->session->userdata('user_type')=='coordinator'){
			$this->load->view('dashboard/template/menuCoordinator');
		}
		else{
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



	/*
	* Function : accept()
	    */
	    public function accept(){



		/*
		* Loading libraries and helpers
		        */
		        $this->load->library(
		            array(
		                'session',
		                'rb',
		                'email',
		                'gomail'
		            )
		        );

		$this->load->helper(
		            array(
		                'url'
		            )
		        );



		/*
		* Loading users
		        */
		        $user = R::findOne('user','id=?',array($this->session->userdata('user_id')));



		/*
		* Verifying capabilities
		        */
		        $type = 'coordinator';
		$userLogged = $this->session->userdata('user_logged_in');
		if(!$userLogged)
		            redirect(base_url('dashboard'));
		$u = $user;
		if($u['type']!=$type)
		            redirect(base_url('dashboard'));



		/*
		* Getting post data
		        */
		        $id = $this->input->post('id');



		/*
		* Loading poster
		        */
		        $poster = R::findOne('poster','id=?',array($id));



		/*
		* Verifying if it's not a user poster
        */
        if( $poster->user->id == $user->id ){
            $this->session->set_flashdata('error', 'Você não pode avaliar o próprio pôster');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        /*
         * Verifying if the poster is not avaliated yet
        */
        if( $poster->evaluation != 'pending' )
        {
            $this->session->set_flashdata('error', 'O pôster já foi avaliado.');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        /*
         * Sending confirmation email
        */
        $msg = "<h1 style='font-weight:bold;
		'>Seu pôster foi aceito, parabêns!</h1>";
        $msg .= "<h3>Seu pôster, $poster->title , foi aceito.</h3>";
        $msg .= "<p style='color:red;
		'>Agora, você só precisa enviar o modelo do banner. Entre no Sistema do Seminário. Vá em Pôsters e envie o modelo. Se você já enviou, descarte essa mensagem.</p>";
        $msg .= "<p>Acompanhe as datas das normas e as notícias dos seminário para os próximos passos.</p>";
        $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";
        $status = false;
        try {
            $status = $this->gomail->send_email(
                'seminario@ccsa.ufrn.br',
                'Seminário de Pesquisa do CCSA',
                $poster->user->email,
                '[Pôster Aceito] Seminário de Pesquisa do CCSA',
                emailMsg($msg)
            );
        } catch (Exception $e) {
        }
        if(!$status){
            $this->session->set_flashdata('error', 'O servidor de emails está fora do ar. Tente novamente mais tarde.');
            redirect(base_url('dashboard/poster/evaluate'));
            exit;
        }
        /*
         * Accepting
        */
        $poster->evaluation = 'accepted';
        R::store($poster);
        /*
         * Confirmation message
        */
        $this->session->set_flashdata('success', 'O pôster foi avaliado como <b>aceito</b> com sucesso.');
        redirect(base_url('dashboard/poster/evaluate'));
    }
    public function reject(){
        $this->load->library( array('session','rb','form_validation','email','gomail') );
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
        /*
         * Verifying if it's not a user poster
		        */
		        if( $poster->user->id == $user->id ){

			$this->session->set_flashdata('error', 'Você não pode avaliar o próprio pôster');
			redirect(base_url('dashboard/poster/evaluate'));
			exit;

		}

		// 		If the article was evaluated
		        if($poster->evaluation!='pending'){
			$this->session->set_flashdata('error', 'O pôster já foi avaliado.');
			redirect(base_url('dashboard/poster/evaluate'));
			exit;
		}


		/* ===========================================
		BEGIN - SENDING EMAIL CONFIRMATION
		        ============================================ */
		        $msg = "<h1 style='font-weight:bold;'>Seu pôster não foi aceito!</h1>";
		$msg .= "<h3>Seu pôster, $poster->title, não foi aceito na avaliação.</h3>";
		$msg .= "<p>$poster->evaluationobservation</p>";
		$msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

		$status = false;

		try {
			$status = $this->gomail->send_email(
			                'seminario@ccsa.ufrn.br',
			                'Seminário de Pesquisa do CCSA',
			                $poster->user->email,
			                '[Pôster Rejeitado] Seminário de Pesquisa do CCSA',
			                emailMsg($msg)
			            );
		}
		catch (Exception $e) {

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

		// 		If the article was evaluated
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
