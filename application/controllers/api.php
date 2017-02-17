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
     * Ma in API endpoint. Useless
     */
    public function index() {
      $this->load->library(array('output'));
      $this->output
        ->set_content_type('application/json')
        ->set_status_header(200) // OK
        ->set_output(json_encode(array('foo'=>'bar')));
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
      $this->output->set_content_type('application/json', 'utf-8');

      $fields = array('name', 'email', 'subject', 'message');

      /* validating fields */
      foreach ($fields as $field) {
        if(empty($this->input->post($field))) {
          $this->output->set_status_header(400) // Bad Request
          ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Campo(s) obrigatório(s) não preenchido(s)'
          )));
          break;
        }
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

    }

}
