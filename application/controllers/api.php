<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

# >> Seminário CCSA - Assessoria Técnica
# > Contributor: Maradona Morais (maradona.morais@hotmail.com)
# > Last update: 2017-02-15 17:05:00 by Maradona Morais


/* API Class. Expects REST requests and respond to them with JSON object */
class API extends CI_Controller {
    public function __construct(){

        parent::__construct();
    }

    /*
     * Main API endpoint. Useless
     */
    public function index() {
      $this->load->library(array('output'));
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(200) // OK
        ->set_output(json_encode(array('foo'=>'bar')))
        ->set_header('Access-Control-Allow-Origin: *');
    }

    /*
     * Message receiver. Expects:
     * @param name user name
     * @param email user mail
     * @param subject message subject
     * @param message user message
     * @return a success|error json obj
     *
     * Endpoint: http://URL_TO_CCSA/index.php?/api/message
     */
    public function message() {
      $this->load->library(array('output', 'rb'));
      $this->load->helper(array('date'));
      $this->output->set_content_type('application/json', 'utf-8')
        ->set_header('Access-Control-Allow-Origin: *');;


      $fields = array('name', 'email', 'subject', 'message');

      /* validating fields */
      $validated = true;

      foreach ($fields as $field) {
        if(empty($this->input->post($field))) {
          $validated = false;
          break;
        }
      }

      if(!$validated) {
        $this->output->set_status_header(400) // Bad Request
          ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Campo(s) obrigatório(s) não preenchido(s)'
        )))->_display();
        exit;
      }
      /* create RB instance and data terms */
      $message = R::dispense('message');
      foreach ($fields as $field) {
        /* this fits only name, email, subject and message */
        $message[$field] = $this->input->post($field);
      }
      $message->created_at = mdate('%Y-%m-%d %H:%i:%s');
      $message->answered = 'no';

      /* store it at database */
      try {
        $id = R::store($message);

        /* mount data object to return*/
        $data = array(
          'id'=>$id,
          'name'=>$message->name,
          'email'=>$message->email,
          'subject'=>$message->subject,
          'message'=>$message->message
        );

        /* returns status and data */
        $this->output->set_status_header(200)
          ->set_output(json_encode(array(
            'status'=>'success',
            'message'=>'Mensagem enviada com sucesso',
            'data'=> $data
        )));
      } catch(Exception $e) {
        /* handle the error */
        $this->output->set_status_header(500) // Internal Server Error
          ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Ocorreu um erro de conexão ao banco de dados'
        )));
      }
  } /* Ends function message() */

  /*
   * Thematic Groups Provider. Expects nothing
   * @return tgs Thematic Groups
   *
   * Endpoint: http://URL_TO_CCSA/index.php?/api/tgs
   */
  public function tgs() {
      $this->load->library(array('output', 'rb'));
      $this->output->set_content_type('application/json', 'utf-8')
        ->set_header('Access-Control-Allow-Origin: *');;

      $data = array();

      try {
          $areas = R::find('area');

          foreach ($areas as $a) {
              $tgs = $a->ownThematicgroupList;
              $res_tgs = array();
              foreach ($tgs as $tg) {
                  // get tg coordinators
                  $coordinators = $tg->sharedUserList;
                  // create coordinators string
                  $res_coordinators_str='';
                  $size = sizeof($coordinators);
                  $idx = 0;
                  foreach ($coordinators as $c) {
                      $res_coordinators_str .= $c->name;
                      if($idx<$size-1) {
                          $res_coordinators_str .= ', ';
                      }
                      $idx++;
                  }
                    //push tg to the array
                  array_push($res_tgs, array(
                      'name'=>$tg->name,
                      'syllabus'=>$tg->syllabus,
                      'coordinators'=>$res_coordinators_str
                  ));
              }
              //push the area to the array
              array_push($data, array(
                  'name'=>$a->name,
                  'tgs'=>$res_tgs
              ));
          }
          /* returns status and data */
          $this->output->set_status_header(200)
            ->set_output(json_encode(array(
              'status'=>'success',
              'data'=> $data
          )));
      } catch(Exception $e) {
          $this->output->set_status_header(500) // Internal Server Error
            ->set_output(json_encode(array(
              'status'=>'error',
              'message'=>'Ocorreu um erro de conexão ao banco de dados'
          )));
      }

  } /* Ends function tgs() */

  /*
   * New user routine. Expects:
   * @param name user name
   * @param email user email
   * @param cpf
   * @param type
   * @param institution
   * @param phone
   * @param pass
   * @param pass-repeate
   * @return error|succes
   *
   * Endpoint: http://URL_TO_CCSA/index.php?/api/new_user
   */
  public function new_user() {
      $this->load->library(array('output', 'rb', 'email', 'gomail'));
      $this->load->helper(array('date', 'security'));
      $this->output->set_content_type('application/json', 'utf-8')
        ->set_header('Access-Control-Allow-Origin: *');;

      $user_password = $this->input->post('pass');

      $fields = array('name', 'email', 'cpf', 'type', 'institution', 'phone', 'pass', 'pass-repeate');

      /* validating data */
      $validated = true;
      foreach ($fields as $field) {
          if(empty($this->input->post($field))) {
              $validated = false;
              break;
          }
      }

      if(!$validated) {
          $this->output->set_status_header(400) // Bad Request
            ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Campo(s) obrigatório(s) não preenchido(s)'
          )))->_display();
          exit;
      }

      /* checking if password and repeat are equals*/
      if(strcmp($this->input->post('pass'), $this->input->post('pass-repeate'))!=0) {
          $this->output->set_status_header(400) // Bad Request
            ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Senha e Repetição não são iguais'
          )))->_display();
          exit;
      }

      /* checking type */
      if($this->input->post('type')!='instructor'&&
      $this->input->post('type')!='student'&&
      $this->input->post('type')!='noacademic') {
         $this->output->set_status_header(400) // Bad Request
           ->set_output(json_encode(array(
           'status'=>'error',
           'message'=>'A categoria escolhida não é pertimida'
         )))->_display();
         exit;
      }

      /* checking if user already exists */
      $users = R::find('user', 'email = ?', [$this->input->post('email')]);
      if(count($users)) {
          $this->output->set_status_header(400) // Bad Request
            ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Este email já está cadastrado'
          )))->_display();
          exit;
      }

      try {
          /* recording the new user */
          $user = R::dispense('user');
          foreach ($fields as $field) {
              if($field !== 'pass'&&$field !== 'pass-repeate') {
                  $user[$field] = $this->input->post($field);
              }
          }
          $user->password = do_hash($this->input->post('pass'), 'md5');
          $user->payment = '';
          $user->paid = 'no';
          $user->authorizedinevent = 'no';
          $user->created_at = mdate('%Y-%m-%d %H:%i:%s');
          $id = R::store($user);

          /* send confirmation email */
          $msg = "<h1 style='font-weight:bold;'>Você foi cadastrado no sistema com sucesso</h1>";
          $msg .= "<h3>Porém sua inscrição no evento ainda não está efetivada, você precisa realizar o pagamento.</h3>";
          $msg .= "<p>Na nova versão, o sistema do Seminário de Pesquisa do CCSA oferece todas as funcionalidades de inscrição, submissão de trabalhos e submissão de propostas de atividades (minicursos, mesas redondas, conferências, em conformidade com as respectivas normas) diretamente pelo próprio sistema! Além disso, o inscrito encontrará instruções para o pagamento da Taxa de Inscrição e campo próprio para o envio do comprovante de pagamento, que validará a sua inscrição.</p>";
          $msg .= "<p>Seus dados de acesso são os seguintes:</p><ul><li><b>Email:</b> $user->email </li><li><b>Senha:</b> $user_password </li></ul>";
          if(R::count('user','type = "instructor" OR type = "student" OR type = "noacademic" ')>1500){
              $msg .= "<p><b>AVISO</b> Informamos que diante do grande número de inscritos no Seminário a entrega de material (pasta com programação, bloco, caneta) está limitada a 1500 participantes.  Assim, o sistema irá contabilizando as inscrições efetivamente concluídas até este número para efeito de entrega de material. Após este número, as inscrições poderão ser feitas mas o participante fica ciente de que não receberá o material do evento. No entanto, isso não impedirá a participação nas atividades programadas nem a sua certificação.</p>";
          }
          $msg .= "<p><a href='".base_url('dashboard')."'>Clique aqui para entrar no sistema</a></p>";

          $mail = $this->gomail->send_email(
              'seminario@ccsa.ufrn.br',
              'Seminário de Pesquisa do CCSA',
              $user->email,
              '[Cadastro no Sistema] Seminário de Pesquisa do CCSA',
              emailMsg($msg)
          );

          /* returns status and data */
          $this->output->set_status_header(200)
            ->set_output(json_encode(array(
              'status'=>'success',
              'message'=>'Usuário cadastrado com sucesso',
              'mail'=>$mail,
              'data'=> array(
                  'id'=>$id,
                  'name'=>$user->name,
                  'email'=>$user->email,
                  'type'=>$user->type,
                  'institution'=>$user->institution
              )
          )));
      } catch(Exception $e) {
          $this->output->set_status_header(500) // Internal Server Error
            ->set_output(json_encode(array(
              'status'=>'error',
              'message'=>'Ocorreu um erro de conexão ao banco de dados'
          )));
      }
  } /* Ends new_user() function*/

  /*
   * Login function. It expects:
   * @param email user email
   * @param pass
   * @return error|succes
   *
   * Endpoint: http://URL_TO_CCSA/index.php?/api/login
   */
  public function login() {
      $this->load->library(array('rb', 'session', 'email', 'gomail'));
      $this->load->helper( array('form' , 'url' , 'date' , 'security', 'utility' ) );

      $this->output->set_content_type('application/json', 'utf-8')
        ->set_header('Access-Control-Allow-Origin: *');;

      $email = $this->input->post('email');
      $password = $this->input->post('pass');

      /* validating fields */
      if(empty($email)||empty($password)) {
          $this->output->set_status_header(400) // Bad Request
            ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Preencha todos os campos'
          )))->_display();
          exit;
      }

      /* Search for user with this mail */
      $user = R::findOne('user', 'email = ?', [$email]);
      if(!count($user)) {
          $this->output->set_status_header(404) // Not Found
            ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Usuário não encontrado'
          )))->_display();
          exit;
      }

      /* check password */
      if($user->password !== do_hash($password, 'md5')) {
          $this->output->set_status_header(400) // Bad Request
            ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Senha incorreta'
          )))->_display();
          exit;
      }

      $this->session->set_userdata(
        array(
            'user_id'=>$user->id,
            'user_name'=>$user->name,
            'user_email'=>$user->email,
            'user_type'=>$user->type,
            'user_logged_in' => mdate('%Y-%m-%d %H:%i:%s')
        )
      );

      $this->output->set_status_header(200)
        ->set_output(json_encode(array(
          'status'=>'success',
          'message'=>'successfuly logged in'
      )));
  } /* Ends login function */

  /*
   * News provider. Expects
   * @slug /all
   *
   * Endpoint: http://URL_TO_CCSA/index.php?/api/news/all
   */
  public function news($all = FALSE) {
      $this->load->library(array('rb'));

      $this->output->set_content_type('application/json', 'utf-8')
        ->set_header('Access-Control-Allow-Origin: *');

      $data = array();
      try {
          if($all==='all') {
              /* get all news*/
              $news = R::find('news');
              foreach ($news as $new) {
                  array_push($data, array(
                      'title'=> $new->title,
                      'text'=> $new->text,
                      'created_at'=> $new->created_at
                  ));
              }
          } else {
              /* get one fixed new and two normal */
              $fixed = R::findOne('news', 'is_fixed = ?', ['Y']);
              if(count($fixed)) {
                  array_push($data, array(
                      'title'=> $fixed->title,
                      'text'=> $fixed->text,
                      'created_at'=> $fixed->created_at
                  ));
              }

              $others = R::find('news', 'is_fixed = ? limit ?', ['N', 2]);
              foreach ($others as $new) {
                  array_push($data, array(
                      'title'=> $new->title,
                      'text'=> $new->text,
                      'created_at'=> $new->created_at
                  ));
              }
          }

          $this->output->set_status_header(200)
            ->set_output(json_encode(array(
              'status'=>'success',
              'data'=> $data
          )));
     } catch(Exception $e) {
         $this->output->set_status_header(500) // Internal Server Error
           ->set_output(json_encode(array(
             'status'=>'error',
             'message'=>'Ocorreu um erro de conexão ao banco de dados'
         )));
     }
  } /* Ends new function */

  /*
   * Forgot password.
  */
  public function forgot_pass() {
      $this->load->library(array('rb', 'session', 'email', 'gomail'));
      $this->load->helper( array('form' , 'url' , 'date' , 'security', 'utility' ) );

      $this->output->set_content_type('application/json', 'utf-8')
        ->set_header('Access-Control-Allow-Origin: *');

      $email = $this->input->post('email');

      $users = R::find('user', "email = ?", [$email]);

      if(!count($users)){
          $this->output->set_status_header(404) // Internal Server Error
            ->set_output(json_encode(array(
              'status'=>'error',
              'message'=>'Email não encontrado em nossos registros'
          )))->_display();
          exit;
      }

      foreach ($users as $user) {

          $newPass = random_string('alnum',8);
          $encNewPass = do_hash($newPass,'md5');

          $status = false;

          $msg = "<h1>Recuperação de Senha</h1>";
          $msg .= "<p>Olá, sua nova senha é: ".$newPass."</p>";
          $msg .= "<p>Acesse <a href='".base_url()."'>o seu painel</a> utilizando a nova senha, vá em <b>Meus dados</b> na parte superior direita, e escolha sua nova senha.</p> ";

          $status = $this->gomail->send_email(
              'assessoriatecnica@ccsa.ufrn.br',
              'Seminário de Pesquisa do CCSA',
              $email,
              '[Recuperando senha] Seminário de Pesquisa do CCSA',
              emailMsg($msg)
          );

          if(!$status){
              $this->output->set_status_header(500) // Internal Server Error
                ->set_output(json_encode(array(
                  'status'=>'error',
                  'message'=>'Problema ao enviar o email'
              )))->_display();
              exit;
          }

          $user->password = $encNewPass;
          $user->retrievepass = 'yes';

          R::store($user);

          $this->output->set_status_header(200)
            ->set_output(json_encode(array(
              'status'=>'success',
              'data'=> []
          )));

      }

  }

} /* Ends class Api*/
