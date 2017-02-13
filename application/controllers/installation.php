<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Installation extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Verifying if system is already installed
        if(verifyingInstallationConf()){
            redirect(base_url());
        }
    }
    
    public function doInstall(){

        $this->load->library( array('session', 'form_validation') );
        $this->load->helper( array('url','form','date') );
        
        // It's a POST request?
        if($this->input->server('REQUEST_METHOD')!='POST'){
            echo "Don't do that. :D";
            exit;
        }
        
        /* ===========================================
            BEGIN - PREPARING DATA
        ============================================ */
        
        $host = $this->input->post('system-host1');
        $username = $this->input->post('username1');
        $password = $this->input->post('password1');
        $dbname = $this->input->post('dbname');

        echo $host;
        echo var_dump($host);
        echo $username;
        echo var_dump($username);
        exit;

        $systemTitle = $this->input->post('system-title');
        $systemDomainPerm = $this->input->post('system-domain-perm');
        $systemDomainTemp = $this->input->post('system-domain-temp');
        $dateLimitPaper = $this->input->post('date-limit-paper');
        $dateLimitPoster = $this->input->post('date-limit-poster');
        $dateLimitMinicourse = $this->input->post('date-limit-minicourse');
        $dateLimitRoundtable = $this->input->post('date-limit-roundtable');
        $dateInscriptionIniMinicourse = $this->input->post('date-inscription-ini-minicourse');
        $dateInscriptionEndMinicourse = $this->input->post('date-inscription-end-minicourse');
        $dateInscriptionIniRoundtable = $this->input->post('date-inscription-ini-roundtable');
        $dateInscriptionEndRoundtable = $this->input->post('date-inscription-end-roundtable');
        $dateInscriptionIniConference = $this->input->post('date-inscription-ini-conference');
        $dateInscriptionEndConference = $this->input->post('date-inscription-end-conference');
        $dateInscriptionIniWorkshop = $this->input->post('date-inscription-ini-workshop');
        $dateInscriptionEndWorkshop = $this->input->post('date-inscription-end-workshop');

        $systemAdminEmail = $this->input->post('system-admin-email');
        $systemAdminPass = $this->input->post('system-admin-pass');
        
        /* ===========================================
            END - PREPARING DATA
        ============================================ */

        // CREATE DATABASE 
        $mysqli = @new mysqli($host, $username, $password);
        if ($mysqli->connect_errno) {
            echo "Tente repetir a operação, houve um problema ao tentar se conectar ao Banco de Dados: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            exit;
        }
        $mysqli->query("CREATE DATABASE ".$dbname); // Create Database
        $mysqli->close();

        // CREATE JSON FILE
        $config = array("installation_time" => date("Y-m-d H:i:s") );

        $fp = fopen(FCPATH.APPPATH.'install.json', 'w');
        fwrite($fp, json_encode($config));
        fclose($fp);

        // CREATE DATABASE CONFIGURATION

        $databasefile = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ";
        $databasefile .= "\$active_group = 'default'; \$active_record = FALSE; ";
        $databasefile .= " \$db "."["."'default'"."]".""."["."'hostname'"."]"." = '".$host."'; \$db"."["."'default'"."]".""."["."'username'"."]"." = '".$username."'; \$db"."["."'default'"."]".""."["."'password'"."]"." = '".$password."'; \$db"."["."'default'"."]".""."["."'database'"."]"." = '".$dbname."'; ";
        $databasefile .= " \$db"."["."'default'"."]".""."["."'dbdriver'"."]"." = 'mysql'; \$db"."["."'default'"."]".""."["."'dbprefix'"."]"." = ''; \$db"."["."'default'"."]".""."["."'pconnect'"."]"." = TRUE; \$db"."["."'default'"."]".""."["."'db_debug'"."]"." = TRUE; \$db"."["."'default'"."]".""."["."'cache_on'"."]"." = FALSE; \$db"."["."'default'"."]".""."["."'cachedir'"."]"." = ''; \$db"."["."'default'"."]".""."["."'char_set'"."]"." = 'utf8'; \$db"."["."'default'"."]".""."["."'dbcollat'"."]"." = 'utf8_general_ci'; \$db"."["."'default'"."]".""."["."'swap_pre'"."]"." = ''; \$db"."["."'default'"."]".""."["."'autoinit'"."]"." = TRUE; \$db"."["."'default'"."]".""."["."'stricton'"."]"." = FALSE; ";

        $fp = fopen(FCPATH.APPPATH.'config/database.php', 'w');
        fwrite($fp, $databasefile);
        fclose($fp);

        // CREATE ADMIN USER
        $this->load->library( array('rb') ); // Loading RedBeanPHP

        $admin = R::dispense('user');
        $admin->type = "administrator";
        $admin->name = "Administrador";
        $admin->phone = "0";

        R::store($admin);

        // CREATE SYSTEM CONFIGS

        $config = R::dispense('configuration');
        $config->name = "system_title";
        $config->nickname = "Título do Sistema";
        $config->value = $systemTitle;
        $config->type = "text";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "system_domain_permanent";
        $config->nickname = "Domínio Permanente do Sistema";
        $config->value = $systemDomainPerm;
        $config->type = "text";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "system_domain_temporary";
        $config->nickname = "Domínio Temporário do Sistema";
        $config->value = $systemDomainTemp;
        $config->type = "text";
        R::store($config);
        
        $config = R::dispense('configuration');
        $config->name = "max_date_paper_submission";
        $config->nickname = "Data Limite de Submissão de Artigo";
        $config->value = $dateLimitPaper;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "max_date_poster_submission";
        $config->nickname = "Data Limite de Submissão de Pôster";
        $config->value = $dateLimitPoster;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "max_date_minicourse_submission";
        $config->nickname = "Data Limite de Submissão de Minicurso";
        $config->value = $dateLimitMinicourse;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "max_date_roundtable_submission";
        $config->nickname = "Data Limite de Submissão de Mesa-Redonda";
        $config->value = $dateLimitRoundtable;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "start_date_minicourse_inscription";
        $config->nickname = "Data Inicial para Inscrições em Minicursos";
        $config->value = $dateInscriptionIniMinicourse;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "end_date_minicourse_inscription";
        $config->nickname = "Data Final para Inscrições em Minicursos";
        $config->value = $dateInscriptionEndMinicourse;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "start_date_roundtable_inscription";
        $config->nickname = "Data Inicial para Inscrições em Mesa-Redondas";
        $config->value = $dateInscriptionIniRoundtable;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "end_date_roundtable_inscription";
        $config->nickname = "Data Final para Inscrições em Mesa-Redondas";
        $config->value = $dateInscriptionEndRoundtable;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "start_date_conference_inscription";
        $config->nickname = "Data Inicial para Inscrições em Mesa-Redondas";
        $config->value = $dateInscriptionIniConference;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "end_date_conference_inscription";
        $config->nickname = "Data Final para Inscrições em Mesa-Redondas";
        $config->value = $dateInscriptionEndConference;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "start_date_workshop_inscription";
        $config->nickname = "Data Inicial para Inscrições em Oficinas";
        $config->value = $dateInscriptionIniWorkshop;
        $config->type = "date";
        R::store($config);

        $config = R::dispense('configuration');
        $config->name = "end_date_workshop_inscription";
        $config->nickname = "Data Final para Inscrições em Oficinas";
        $config->value = $dateInscriptionEndWorkshop;
        $config->type = "date";
        R::store($config);

        // Installation is finished
        // OK

    }

    public function installView(){

        $this->load->library( array('session') );
        $this->load->helper( array('form') );

        $this->load->view('installation/template/header');
        $this->load->view('installation/install');
        $this->load->view('installation/template/footer');
    
    }
    
    public function testMysql(){

        $servername = $this->input->post("host");
        $username = $this->input->post("username");
        $password = $this->input->post("password");
        
        $this->output->set_header('Content-Type: application/json');
       
        // Realizando a comunicação com o Mysql
        $mysqli = @new mysqli($servername, $username, $password);

        // Verificando se há erros
        if ($mysqli->connect_errno){
            
            echo json_encode(
            array(
                    'status' => array(
                        'code' => 'error',
                        'msg' => 'Não foi possível realizar a conexão, verifique se o Host ou Usuário e Senha estão corretas.'
                    )
                )   
            );
            exit;

        } else {
            
            // Verificando se o usuário tem privilégios para criar banco de dados
            $query = "SELECT * FROM mysql.user WHERE user = '".$username."' AND Create_priv = 'Y' ";
            $result = $mysqli->query($query);
            
            if( ! (mysqli_affected_rows ($mysqli) > 0) ){
                
                echo json_encode(
                array(
                        'status' => array(
                            'code' => 'error',
                            'msg' => 'Este usuário não tem privilégios para criar um banco de dados.'
                        )
                    )   
                );
                exit;
            
            }

            echo json_encode(
            array(
                    'status' => array(
                        'code' => 'success',
                        'msg' => 'A conexão foi realizada com sucesso.'
                    )
                )   
            );
            exit;

        }
        

    }

    public function sendEmailTest(){

        $this->load->library( array('email') );

        $email = $this->input->get('email');

        $this->email->from('seminario@ccsa.ufrn.br', 'Sistema do Seminário de Pesquisa do CCSA');
        $this->email->to($email); 

        $this->email->subject('[Email de Teste] Sistema do Seminário de Pesquisa do CCSA');

        $msg = "<h1 style='font-weight:bold;'>Você recebeu o email teste com sucesso!</h1>";
        
        $this->email->message($msg);
        
        $status = @$this->email->send();

        if(!$status){
            echo "A configuração do servidor de email não está correta, pois o email não pôde ser enviado. Corrija as configurações e tente novamente.";
            exit;
        }

        echo "Parece que o email foi enviado com sucesso, verifique na caixa de mensagens do email do administrador.";
        exit;

    }

    public function testDateHour(){

        $this->load->helper( array('date') );
        echo "<h1>".mdate('%Y-%m-%d')."</h1>";

        echo "<h1>".mdate('%H:%i:%s')."</h1>";
    }

    public function modalTemplate(){

        $this->load->library( array('session','rb') );
        $this->load->helper( array('url','form','date') );


        $this->load->view('installation/template/modal');

    }

    public function testCreateDb(){

        $servername = $this->input->get("host");
        $username = $this->input->get("username");
        $password = $this->input->get("password");
        $dbname = $this->input->get("dbname");

        $mysqli = @new mysqli($servername, $username, $password);

        if ($mysqli->connect_errno){
            echo 'connectionfail';
            exit;
        } 

        // Verificando se o usuário tem privilégios para criar banco de dados
        $query = "SELECT * FROM mysql.user WHERE user like '".$username."' AND Create_priv = 'Y' ";
        $result = $mysqli->query($query);

        if( ! (mysqli_affected_rows ($mysqli) > 0) ){
            echo "nopriv";
            exit;
        }

        $result->close();

        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '".$dbname."' ";
        $result = $mysqli->query($query);

        if( mysqli_affected_rows ($mysqli) > 0 ){
            echo "alreadyexists";
        }else{
            echo "ok";
        }

        $result->close();
        
    }
}

/* End of file installation.php */
/* Location: ./application/controllers/installation.php */