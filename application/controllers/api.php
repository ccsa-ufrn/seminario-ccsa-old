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
      $validated = true;
      foreach ($fields as $field) {
        if(empty($this->input->post($field))) {
          $validated = false;
          break;
        }
      }

      if($validated) {
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
      } else {
          $this->output->set_status_header(400) // Bad Request
          ->set_output(json_encode(array(
            'status'=>'error',
            'message'=>'Campo(s) obrigatório(s) não preenchido(s)'
          )));
      }
  } /* Ends function message() */

  /*
   * Thematic Groups Provider. Expects nothing
   * @return tgs Thematic Groups
   */
  public function tgs() {
      $this->load->library(array('output', 'rb'));
      $this->output->set_content_type('application/json', 'utf-8');
      ini_set('display_errors', 1);
      $data = array();

      try {
          $areas = R::find('area');

          foreach ($areas as $a) {
              $tgs = $a->ownThematicgroupList;
              $res_tgs = array();
              foreach ($tgs as $tg) {
                  // If tg is not listable should not return it
                  if($tg->is_listable != 'Y') {
                      continue;
                  }
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

} /* Ends class Api*/
