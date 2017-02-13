<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Script extends CI_Controller {

    public function __construct(){
        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));
        
    }
    
    public function createAdminUser(){
        
        $this->load->library(array('rb'));
        $this->load->helper(array('security','date'));
        
        $user = R::dispense("user");
		$user['name'] = 'Administrador';
		$user['email'] = 'admin@ccsa.ufrn.br';
		$user['password'] = do_hash('123456','md5');
		$user['type'] = 'administrator';
		$user['created_at'] = mdate('%Y-%m-%d %H:%i:%s');
		R::store($user);
        
    }

    public function listErrors(){

        $this->load->library( array('rb') );

        echo "<meta charset='utf-8'>";

        $posters = R::find('paper',' evaluation="asPoster" AND asposter="accepted" ');

        echo "<h1>ARTIGOS ACEITOS COMO PÔSTERES</h1>";
        echo "<table border='1' ><tr><th>Título</th><th>Autores</th><th>Grupo Temático</th></tr>";

        foreach ($posters as $p) {
            echo "<tr>";
            echo "<td>";
            echo $p->title;
            echo "</td>";
            echo "<td>";
            $authors = explode("||",$p->authors);
            $i=0;
            foreach ($authors as $author) {
                if($i++!=0) echo ", ";
                echo $author;
            }
            echo "</td>";
            echo "<td>";
            echo $p->thematicgroup->name;
            echo "</td>";


            echo "</tr>";
        }

        echo "</table>";

    }

	public function dateHourTest(){

		$this->load->helper( array('date') );

        echo "<meta charset='utf-8'>";
        echo "<p>Se a hora ou date estiverem erradas, ou você precisa corrigir a hora do servidor, ou você precisar corrigir a TIMEZONE no arquivo index.php.</p>";

		echo mdate('%Y-%m-%d %H:%i');

	}

    public function asPosterPending(){

        $this->load->library( array('rb') );

        echo "<meta charset='utf-8'>";

        $papers = R::find('paper',' evaluation="asPoster" AND asPoster IS NULL ');

        foreach ($papers as $p) {
            echo $p->user->email."<br/>";
        }

    }
    
    public function coordinatorsList(){

        
    }

    public function reportPosters(){

        $this->load->library( array('rb') );
        
        $tgs = R::find('thematicgroup','ORDER BY name');
        
        echo "<meta charset='utf-8'>";

        echo "<style> td {  vertical-align: center; text-align: center;} </style>";

        foreach ($tgs as $tg) {

            echo "<h2>".$tg->name."</h2>";
         
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Título</th>";
            echo "<th>Autores</th>";
            echo "<th>Situação</th>";
            echo "</tr>";

            foreach ($tg->ownPosterList as $poster) {
                if($poster->evaluation=='rejected' || ($poster->evaluation=='asPoster' && $poster->asposter=='rejected') ) continue;
                echo "<tr>";
                echo "<td>".$poster->title."</td>";
                echo "<td>";
                $authors = explode("||",$poster->authors);
                $i=0;
                foreach ($authors as $author) {
                    if($i++!=0) echo ", ";
                    echo $author;
                }
                echo "</td>";
                echo "<td>";
                if($poster->evaluation=='accepted'){
                    echo "<b style='color:green;'>Aceito</b>";
                }else if($poster->evaluation=='pending'){
                    echo "<b style='color:orange;'>Esperando Avaliação</b>";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";

        }

    }

    public function reportAllPapers(){

        $this->load->library( array('rb') );
        
        $tgs = R::find('thematicgroup','ORDER BY name');
        
        echo "<meta charset='utf-8'>";

        echo "<style> td {  vertical-align: center; text-align: center;} </style>";

        foreach ($tgs as $tg) {

            echo "<h2>".$tg->name."</h2>";
         
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Título</th>";
            echo "<th>Autores</th>";
            echo "<th>Contato</th>";
            echo "<th>Situação</th>";
            echo "</tr>";

            foreach ($tg->ownPaperList as $paper) {
            
                echo "<tr>";
                echo "<td>".$paper->title."</td>";
                echo "<td>";
                $authors = explode("||",$paper->authors);
                $i=0;
                foreach ($authors as $author) {
                    if($i++!=0) echo ", ";
                    echo $author;
                }
                echo "</td>";
                echo "<td>".$paper->user->email."</td>";
                echo "<td>";
                if($paper->evaluation=='accepted'){
                    echo "<b style='color:green;'>Aceito</b>";
                }else if($paper->evaluation=='pending'){
                    echo "<b style='color:orange;'>Esperando Avaliação</b>";
                }else if($paper->evaluation=='asPoster' && $paper->asposter=='accepted'){
                    echo "<b style='color:green;'>Aceito como Pôster</b>";
                }
                else if($paper->evaluation=='asPoster' && !isset($paper->asposter)){
                    echo "<b style='color:orange;'>Esperando Avaliação como Pôster</b>";
                    echo $paper->evaluation=='asPoster' && !isset($paper->asposter);
                    echo "olá mundo";
                }else if($paper->evaluation=='asPoster' && $paper->asposter=='rejected'){
                    echo "<b style='color:red;'>Rejeitou apresentar como pôster</b>";
                }else if($paper->evaluation=='rejected'){
                    echo "<b style='color:red;'>Rejeitado</b>";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";

        }

    }

    public function reportAllPosters(){

        $this->load->library( array('rb') );
        
        $tgs = R::find('thematicgroup','ORDER BY name');
        
        echo "<meta charset='utf-8'>";

        echo "<style> td {  vertical-align: center; text-align: center;} </style>";

        foreach ($tgs as $tg) {

            echo "<h2>".$tg->name."</h2>";
         
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Título</th>";
            echo "<th>Autores</th>";
            echo "<th>Contato</th>";
            echo "<th>Situação</th>";
            echo "</tr>";

            foreach ($tg->ownPosterList as $poster) {
                echo "<tr>";
                echo "<td>".$poster->title."</td>";
                echo "<td>";
                $authors = explode("||",$poster->authors);
                $i=0;
                foreach ($authors as $author) {
                    if($i++!=0) echo ", ";
                    echo $author;
                }
                echo "</td>";
                echo "<td>".$poster->user->email."</td>";
                echo "<td>";
                if($poster->evaluation=='accepted'){
                    echo "<b style='color:green;'>Aceito</b>";
                }else if($poster->evaluation=='pending'){
                    echo "<b style='color:orange;'>Esperando Avaliação</b>";
                }else if($poster->evaluation=='rejected'){
                    echo "<b style='color:red;'>Rejeitado</b>";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";

        }

    }

    public function rabstract(){

        $this->load->library( array('rb') );
        
        $tg = R::findOne('thematicgroup','id=18');
        
        echo "<meta charset='utf-8'>";

        echo "<style> td {  vertical-align: center; text-align: center;} </style>";
         
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Título</th>";
            echo "<th>Resumo</th>";
            echo "<th>Avaliação</th>";
            echo "</tr>";

            foreach ($tg->ownPosterList as $poster) {
                echo "<tr>";
                echo "<td>".$poster->title."</td>";
                echo "<td>";
                    echo $poster->abstract; 
                echo "</td>";
                echo "<td>";
                if($poster->evaluation=='accepted'){
                    echo "<b style='color:green;'>Aceito</b>";
                }else if($poster->evaluation=='pending'){
                    echo "<b style='color:orange;'>Esperando Avaliação</b>";
                }else if($poster->evaluation=='rejected'){
                    echo "<b style='color:red;'>Rejeitado</b>";
                }
                echo "</td>";
                echo "</td>";
                echo "</tr>";
            }

            foreach ($tg->ownPaperList as $paper) {
                echo "<tr>";
                echo "<td>".$paper->title."</td>";
                echo "<td>";
                    echo $paper->abstract; 
                echo "</td>";
                echo "<td>";
                if($paper->evaluation=='accepted'){
                    echo "<b style='color:green;'>Aceito</b>";
                }else if($paper->evaluation=='pending'){
                    echo "<b style='color:orange;'>Esperando Avaliação</b>";
                }else if($paper->evaluation=='rejected'){
                    echo "<b style='color:red;'>Rejeitado</b>";
                }
                echo "</td>";
                echo "</td>";
                echo "</tr>";
            }

        echo "</table>";

    }

    public function reportPapers(){

        $this->load->library( array('rb') );
        
        $tgs = R::find('thematicgroup','ORDER BY name');
        
        echo "<meta charset='utf-8'>";

        echo "<style> td {  vertical-align: center; text-align: center;} </style>";

        foreach ($tgs as $tg) {

            echo "<h2>".$tg->name."</h2>";
         
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Título</th>";
            echo "<th>Autores</th>";
            echo "<th>Situação</th>";
            echo "</tr>";

            foreach ($tg->ownPaperList as $paper) {
                if($paper->evaluation=='rejected' || ($paper->evaluation=='asPoster' && $paper->asposter=='rejected') ) continue;
                echo "<tr>";
                echo "<td>".$paper->title."</td>";
                echo "<td>";
                $authors = explode("||",$paper->authors);
                $i=0;
                foreach ($authors as $author) {
                    if($i++!=0) echo ", ";
                    echo $author;
                }
                echo "</td>";
                echo "<td>";
                if($paper->evaluation=='accepted'){
                    echo "<b style='color:green;'>Aceito</b>";
                }else if($paper->evaluation=='pending'){
                    echo "<b style='color:orange;'>Esperando Avaliação</b>";
                }else if($paper->evaluation=='asPoster' && $paper->asposter=='accepted'){
                    echo "<b style='color:green;'>Aceito como Pôster</b>";
                }
                else if($paper->evaluation=='asPoster' && !isset($paper->asposter)){
                    echo "<b style='color:orange;'>Esperando Avaliação como Pôster</b>";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";

        }

    }
    
    public function reportGts(){
        
        $this->load->library( array('rb') );
        
        $tgs = R::find('thematicgroup');
        
        echo "<meta charset='utf-8'>";

        echo "<style> td {  vertical-align: center; text-align: center;} </style>";

        echo "<table border='1'>";
        echo "<tr>";
        echo "<th>GT</th>";
        echo "<th>Coordenadores</th>";
        echo "<th>Artigos (Resumo)</th>";
        echo "<th>Artigos Detalhados</th>";
        echo "<th>Pôsteres (Resumo)</th>";
        echo "<th>Pôsteres Detalhados</th>";
        echo "</tr>";
        
        foreach($tgs as $tg){

        	echo "<tr style='text-transform:uppercase;'>";
            echo "<td><b>".$tg->name."</b></td>";
            
            echo "<td>";
            
            foreach($tg->sharedUserList as $e){
                echo $e->name.", ";
            }
            
            echo "</td>";
            
            echo "<td><b>".count($tg->withCondition(' evaluation="pending" ')->ownPaperList)."</b> artigos esperando avaliação. <b>".count($tg->withCondition(' evaluation="rejected" ')->ownPaperList)."</b> artigos rejeitados. <b>".count($tg->withCondition(' evaluation="asPoster" ')->ownPaperList)."</b> artigos aceitos como pôster. <b>".count($tg->withCondition(' evaluation="accepted" ')->ownPaperList)."</b> artigos aceitos como artigos.</td>";
            
            echo "<td>";

            foreach($tg->all()->ownPaperList as $p){
            	echo $p->title." ";
            	if($p->evaluation=='pending')
            		echo "<span style='color:orange;'>(Esperando Avaliação)</span>";
            	else if($p->evaluation=='rejected')
            		echo "<span style='color:red;'>(Rejeitado)</span>";
            	else if($p->evaluation=='accepted')
            		echo "<span style='color:green;'>(Aceito)</span>";
            	else if($p->evaluation=='asPoster')
            		if(isset($p->asposter)){
            			if($p->aspoter=='accepted')
            				echo "<span style='color:green;'>(Aceito como Pôster - Participante aceitou apresentar como pôster)</span>";
            			else if($p->aspoter=='rejected')
            				echo "<span style='color:red;'>(Aceito como Pôster - Participante rejeitou apresentar como pôster)</span>";
            		}else
            			echo "<span style='color:orange;'>(Aceito como Pôster - Esperando decisão do participante)</span>";
            	echo "<br/><br/>";
            }
            echo "</td>";

            echo "<td><b>".count($tg->withCondition(' evaluation="pending" ')->ownPosterList)."</b> pôsteres esperando avaliação. <b>".count($tg->withCondition(' evaluation="rejected" ')->ownPosterList)."</b> pôsteres rejeitados. <b>".count($tg->withCondition(' evaluation="accepted" ')->ownPosterList)."</b> pôsteres aceitos.</td>";
            
            echo "<td>";
            foreach($tg->all()->ownPosterList as $p){
            	echo $p->title;
            	if($p->evaluation=='pending')
            		echo "<span style='color:orange;'>(Esperando Avaliação)</span>";
            	else if($p->evaluation=='rejected')
            		echo "<span style='color:red;'>(Rejeitado)</span>";
            	else if($p->evaluation=='accepted')
            		echo "<span style='color:green;'>(Aceito)</span>";
            	echo "<br/><br/>";
            }
            echo "</td>";

            echo "</tr>";
            
        }
        
        
    }

    public function installConfigs(){
        
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
        
        // MAX DATA FOR REGISTRATION (?)
        
        // MAX DATE FOR PAPERS SUBMISSIONS
        
        $name = "max_date_paper_submission" ;
        $nickname = "Data limite de submissão de artigo";
        $value = "2015-03-15";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // MAX DATE FOR POSTERS SUBMISSIONS
        
        $name = "max_date_poster_submission" ;
        $nickname = "Data limite de submissão de pôster";
        $value = "2015-03-15";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // MAX DATE FOR MINICOURSE SUBMISSIONS
        
        $name = "max_date_minicourse_submission" ;
        $nickname = "Data limite de submissão de minicurso";
        $value = "2015-03-15";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }

        // MAX DATE FOR WORKSHOP SUBMISSIONS
        
        $name = "max_date_workshop_submission" ;
        $nickname = "Data limite de submissão de oficina";
        $value = "2015-03-15";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // MAX DATE FOR ROUNDTABLE SUBMISSIONS
        
        $name = "max_date_roundtable_submission" ;
        $nickname = "Data limite de submissão de mesa-redonda";
        $value = "2015-03-15";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // MAX DATE FOR CASE STUDY SUBMISSIONS
        
        $name = "max_date_teachingcases_submission" ;
        $nickname = "Data limite de submissão de casos de ensino";
        $value = "2015-03-15";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // MAX DATE FOR ROUNDTABLE CONSOLIDATION
        
        $name = "max_date_roundtable_consolidation" ;
        $nickname = "Data limite para consolidação de mesas-redondas";
        $value = "2015-03-22";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }

        // MAX DATE FOR MINICOURSE CONSOLIDATION
        
        $name = "max_date_minicourse_consolidation" ;
        $nickname = "Data limite para consolidação de minicursos";
        $value = "2015-03-22";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // MAX DATE FOR CONFERENCE CONSOLIDATION
        
        $name = "max_date_conference_consolidation" ;
        $nickname = "Data limite para consolidação de conferências";
        $value = "2015-03-22";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // START DATE FOR MINICOURSE INSCRIPTIONS
        
        $name = "start_date_minicourse_inscription" ;
        $nickname = "Data inicial para inscrições em minicursos";
        $value = "2015-03-23";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }

        // END DATE FOR MINICOURSE INSCRIPTIONS
        
        $name = "end_date_minicourse_inscription" ;
        $nickname = "Data limite de inscrições em minicursos";
        $value = "2015-04-24";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }

        // START DATE FOR WORKSHOP INSCRIPTIONS
        
        $name = "start_date_workshop_inscription" ;
        $nickname = "Data inicial para inscrições em oficinas";
        $value = "2015-03-23";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }

        // END DATE FOR WORKSHOP INSCRIPTIONS
        
        $name = "end_date_workshop_inscription" ;
        $nickname = "Data limite de inscrições em oficinas";
        $value = "2015-04-24";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // START DATE FOR ROUNDTABLE INSCRIPTIONS
        
        $name = "start_date_roundtable_inscription" ;
        $nickname = "Data inicial para inscrições em mesas-redondas";
        $value = "2015-03-23";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // END DATE FOR ROUNDTABLE INSCRIPTIONS
        
        $name = "end_date_roundtable_inscription" ;
        $nickname = "Data limite de inscrições em mesas-redondas";
        $value = "2015-04-24";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // START DATE FOR CONFERENCE INSCRIPTIONS
        
        $name = "start_date_conference_inscription" ;
        $nickname = "Data inicial para inscrições em conferências";
        $value = "2015-03-23";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config);
            
        }
        
        // END DATE FOR CONFERENCE INSCRIPTIONS
        
        $name = "end_date_conference_inscription" ;
        $nickname = "Data limite de inscrições em conferências";
        $value = "2015-04-24";
        $type = "date";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }
        
        // ONE OR TWO AVALIATIONS PER PAPER?
        
        $name = "one_or_two_avaliations_papaer" ;
        $nickname = "Uma ou duas avaliações por artigo?";
        $value = "false";
        $type = "boolean";
        
        if(!R::count('configuration','name=?',array($name))){
            $config = R::dispense('configuration');
            $config->name = $name;
            $config->nickname = $nickname;
            $config->value = $value;
            $config->type = $type;
            R::store($config);
            
        }else{
            $config = R::findOne('configuration','name=?',array($name));
            $config->value = $value;
            R::store($config); 
        }

        echo "All configurations were installed successfully. :D";
        
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */