<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Script extends CI_Controller {

    public function __construct(){

        parent::__construct();

        if(!verifyingInstallationConf())
            redirect(base_url('install'));

    }

    /*
     * Function : generalReport()
     * Description : Generate a geral report of TG's
    */
    public function generalReport()
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

        $pdf->addPage('L');

        /* *********************************************************
         * BEGIN  - HEADER
         ********************************************************* */

        $pdf->image(
            asset_url().'img/logopdf.png',
            132,
            5
        );

        $pdf->ln(14);
        $pdf->SetFont('Courier','B',12);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'SEMINÁRIO DE PESQUISA DO CCSA' ),
            0,
            0,
            'C'
        );

        $pdf->Ln(7);
        $pdf->SetFont('Courier','',9);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'Relatório Geral de Trabalhos' ),
            0,
            0,
            'C'
        );

        /* *********************************************************
        * END - HEADER
        ********************************************************* */

        /*
         * General Info
        */
        $pdf->Ln(6);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(217,217,217);
        $pdf->SetFillColor(217,217,217);
        $pdf->Cell(
            0,
            10,
            utf8_decode(' INFORMAÇÕES GERAIS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);

        $pdf->Ln(0);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(244,244,244);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( ' '.R::count('paper').' ARTIGOS CADASTRADOS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(244,244,244);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( ' '.R::count('poster').' PÔSTERES CADASTRADOS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);


        /*
         * Loading TG's
        */
        $tgs = R::find(
            'thematicgroup',
            ' is_listable = "Y" ORDER BY name ASC'
        );


        foreach ( $tgs as $tg ) :

            /*
             * Loading Papers
            */
            $papers = $tg->with(' ORDER BY title')->ownPaperList;


            /*
             * Loading Posters
            */
            $posters = $tg->with(' ORDER BY title')->ownPosterList;


            $pdf->Ln(10);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(217,217,217);
            $pdf->SetFillColor(217,217,217);

            $pdf->SetFont('Courier','B',9);

            $pdf->Cell(
                0,
                10,
                utf8_decode(' GT - '.$tg->name),
                'LRT',
                0,
                'C',
                true
            );

            $pdf->Ln(10);

            $users = "";

            $pdf->SetFont('Courier','',9);

            $count = count( $tg->with('ORDER BY name')->sharedUserList );
            $i = 0;

            foreach ( $tg->with('ORDER BY name')->sharedUserList as $u) :

                $users .= $u->name;

                ++$i;

                if ( ! ( $count-1 == $i || $count-2 == $i )  ) :

                    $users .= ', ';

                elseif( $count-2 == $i ) :

                    $users .= ' e ';

                else:

                    $users .= '.';

                endif;

            endforeach;

            $pdf->SetFillColor(217,217,217);

            $pdf->MultiCell(
                277,
                10,
                trim( str_replace ( '||' , ',' , utf8_decode( $users )  ) ),
                'LBR',
                'C',
                true
            );

            $pdf->Ln(0);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(244,244,244);
            $pdf->Cell(
                0,
                10,
                utf8_decode(' ARTIGOS | '.count( $papers ).' Artigo(s) cadastrado(s)'),
                'LRTB',
                0,
                'L',
                true
            );

            $pdf->Ln(10);


            /*
             * Showing papers
            */
            foreach ( $papers as $p ) :

                $pdf->SetFillColor(255,255,255);

                $pdf->SetFont('Courier','B',12);

                $pdf->MultiCell(
                    0,
                    10,
                    strtoupper( /*character_limiter(*/ utf8_decode(' '.trim( $p->title ) ) /*, 70, '...' )*/ ),
                    'LTR',
                    'C',
                    false
                );

                $evaluation = '';

                /* If it's waiting evaluation */
                if ( $p->evaluation == 'pending' ) :

                    $evaluation = 'ESPERANDO AVALIAÇÃO';
                    $pdf->SetFillColor(255, 187, 153);

                elseif ( $p->evaluation == 'accepted' ) :

                    $evaluation = 'ACEITO';
                    $pdf->SetFillColor(174, 234, 174);

                elseif ( $p->evaluation == 'rejected' ) :

                        $evaluation = 'REJEITADO';
                        $pdf->SetFillColor(255, 77, 77);

                endif;

                $pdf->SetFont('Courier','',9);

                $pdf->Cell(
                    0,
                    10,
                    utf8_decode( $evaluation ),
                    'LR',
                    0,
                    'C',
                    true
                );

                $pdf->Ln(10);

                $pdf->SetFont('Courier','',9);

                $pdf->MultiCell(
                    277,
                    10,
                    ' '.trim( str_replace ( '||' , ',' , utf8_decode( $p->authors )  ) ),
                    'LBR',
                    'C',
                    false
                );

            endforeach;

            $pdf->Ln(10);


            /*
             * Showing posters
            */
            $pdf->Ln(0);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(244,244,244);

            $pdf->Cell(
                0,
                10,
                utf8_decode(' PÔSTERES | '.count( $posters ).' Pôster(es) cadastrado(s)'),
                'LRTB',
                0,
                'L',
                true
            );

            $pdf->Ln(10);

            foreach ( $posters as $p ) :

                $pdf->SetFillColor(255,255,255);

                $pdf->SetFont('Courier','B',12);

                $pdf->MultiCell(
                    0,
                    10,
                    strtoupper( /*character_limiter( */ utf8_decode(' '.trim( $p->title ) ) /*, 70, '...' )*/ ),
                    'LT',
                    'C',
                    false
                );

                $evaluation = '';

                /* If it's waiting evaluation */
                if ( $p->evaluation == 'pending' ) :

                    $evaluation = 'ESPERANDO AVALIAÇÃO';
                    $pdf->SetFillColor(255, 187, 153);

                elseif ( $p->evaluation == 'accepted' ) :

                    $evaluation = 'ACEITO';
                    $pdf->SetFillColor(174, 234, 174);

                elseif ( $p->evaluation == 'rejected' ) :

                        $evaluation = 'REJEITADO';
                        $pdf->SetFillColor(255, 77, 77);

                endif;


                $pdf->SetFont('Courier','',9);

                $pdf->Cell(
                    0,
                    10,
                    utf8_decode( $evaluation ),
                    'T',
                    0,
                    'C',
                    true
                );

                $pdf->Ln(10);

                $pdf->SetFont('Courier','',9);

                $pdf->MultiCell(
                    277,
                    10,
                    ' '.trim( str_replace ( '||' , ',' , utf8_decode( $p->authors )  ) ),
                    'LBR',
                    'C',
                    false
                );

            endforeach;

            $pdf->Ln(10);

        endforeach;

        $pdf->Output();

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

    public function reportPosters()
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

        $pdf->addPage('L');

        /* *********************************************************
         * BEGIN  - HEADER
         ********************************************************* */

        $pdf->image(
            asset_url().'img/logopdf.png',
            132,
            5
        );

        $pdf->ln(14);
        $pdf->SetFont('Courier','B',12);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'SEMINÁRIO DE PESQUISA DO CCSA' ),
            0,
            0,
            'C'
        );

        $pdf->Ln(7);
        $pdf->SetFont('Courier','',9);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'Relatório Geral de Trabalhos' ),
            0,
            0,
            'C'
        );

        /* *********************************************************
        * END - HEADER
        ********************************************************* */

        /*
         * General Info
        */
        $pdf->Ln(6);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(244,244,244);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( ' '.R::count('poster').' PÔSTERES CADASTRADOS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);


        /*
         * Loading TG's
        */
        $tgs = R::find(
            'thematicgroup',
            ' is_listable = "Y" ORDER BY name ASC'
        );


        foreach ( $tgs as $tg ) :

            /*
             * Loading Posters
            */
            $posters = $tg->with('ORDER BY title')->ownPosterList;


            $pdf->Ln(10);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(217,217,217);
            $pdf->SetFillColor(217,217,217);
            $pdf->Cell(
                0,
                10,
                utf8_decode(' GT - '.$tg->name),
                'LRTB',
                0,
                'L',
                true
            );

            $pdf->Ln(10);

            /*
             * Showing posters
            */
            $pdf->Ln(0);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(244,244,244);
            $pdf->Cell(
                0,
                10,
                utf8_decode(' PÔSTERES | '.count( $posters ).' Pôster(es) cadastrado(s)'),
                'LRTB',
                0,
                'L',
                true
            );

            $pdf->Ln(10);

            foreach ( $posters as $p ) :

                $pdf->SetFillColor(255,255,255);

                $pdf->Cell(
                    170,
                    10,
                    strtoupper( character_limiter( utf8_decode(' '.trim( $p->title ) ) , 70, '...' ) ),
                    'LT',
                    0,
                    'L',
                    false
                );

                $evaluation = '';

                /* If it's waiting evaluation */
                if ( $p->evaluation == 'pending' ) :

                    $evaluation = 'ESPERANDO AVALIAÇÃO';
                    $pdf->SetFillColor(255, 187, 153);

                elseif ( $p->evaluation == 'accepted' ) :

                    $evaluation = 'ACEITO';
                    $pdf->SetFillColor(174, 234, 174);

                elseif ( $p->evaluation == 'rejected' ) :

                        $evaluation = 'REJEITADO';
                        $pdf->SetFillColor(255, 77, 77);

                endif;


                $pdf->Cell(
                    107,
                    10,
                    utf8_decode( $evaluation ),
                    'T',
                    0,
                    'C',
                    true
                );

                $pdf->Ln(10);

                $pdf->MultiCell(
                    277,
                    10,
                    ' '.trim( str_replace ( '||' , ',' , utf8_decode( $p->authors )  ) ),
                    'LBR',
                    'L',
                    false
                );

            endforeach;

            $pdf->Ln(10);

        endforeach;

        $pdf->Output();

    }


    /*
     * Function : reportTeachingCases()
     * Description : -
    */
    public function reportTeachingCases()
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

        $pdf->addPage('L');

        /* *********************************************************
         * BEGIN  - HEADER
         ********************************************************* */

        $pdf->image(
            asset_url().'img/logopdf.png',
            132,
            5
        );

        $pdf->ln(14);
        $pdf->SetFont('Courier','B',12);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'SEMINÁRIO DE PESQUISA DO CCSA' ),
            0,
            0,
            'C'
        );

        $pdf->Ln(7);
        $pdf->SetFont('Courier','',9);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'Relatório Geral de Casos para Ensino' ),
            0,
            0,
            'C'
        );

        /* *********************************************************
        * END - HEADER
        ********************************************************* */

        /*
         * General Info
        */
        $pdf->Ln(6);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(244,244,244);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( ' '.R::count('teachingcase').' CASOS PARA ENSINO CADASTRADOS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);


        /*
         * Loading Teaching Cases
        */
        $tcs = R::find(
            'teachingcase',
            ' ORDER BY title ASC'
        );

        /*
            * Showing teaching cases
        */

        $pdf->Ln(0);

        foreach ( $tcs as $tc ) :

            $pdf->SetFillColor(255,255,255);

            $pdf->Cell(
                170,
                10,
                strtoupper( character_limiter( utf8_decode(' '.trim( $tc->title ) ) , 70, '...' ) ),
                'LT',
                0,
                'L',
                false
            );

            $evaluation = '';

            /* If it's waiting evaluation */
            if ( $tc->evaluation == 'pending' ) :

                $evaluation = 'ESPERANDO AVALIAÇÃO';
                $pdf->SetFillColor(255, 187, 153);

            elseif ( $tc->evaluation == 'accepted' ) :

                $evaluation = 'ACEITO';
                $pdf->SetFillColor(174, 234, 174);

            elseif ( $tc->evaluation == 'rejected' ) :

                    $evaluation = 'REJEITADO';
                    $pdf->SetFillColor(255, 77, 77);

            endif;


            $pdf->Cell(
                107,
                10,
                utf8_decode( $evaluation ),
                'T',
                0,
                'C',
                true
            );

            $pdf->Ln(10);

            $pdf->MultiCell(
                277,
                10,
                ' '.trim( str_replace ( '||' , ',' , utf8_decode( $tc->authors )  ) ),
                'LBR',
                'L',
                false
            );

        endforeach;

        $pdf->Output();

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


    /*
     * Function : reportPapers()
     * Description : Show a report about papers
    */
    public function reportPapers()
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

        $pdf->addPage('L');

        /* *********************************************************
         * BEGIN  - HEADER
         ********************************************************* */

        $pdf->image(
            asset_url().'img/logopdf.png',
            132,
            5
        );

        $pdf->ln(14);
        $pdf->SetFont('Courier','B',12);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'SEMINÁRIO DE PESQUISA DO CCSA' ),
            0,
            0,
            'C'
        );

        $pdf->Ln(7);
        $pdf->SetFont('Courier','',9);

        $pdf->Cell(
            0,
            0,
            utf8_decode( 'Relatório de Artigos' ),
            0,
            0,
            'C'
        );

        /* *********************************************************
        * END - HEADER
        ********************************************************* */

        /*
         * General Info
        */
        $pdf->Ln(6);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(217,217,217);
        $pdf->SetFillColor(217,217,217);
        $pdf->Cell(
            0,
            10,
            utf8_decode(' INFORMAÇÕES GERAIS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);

        $pdf->Ln(0);

        $pdf->SetFont('Courier','B',9);

        $pdf->SetDrawColor(244,244,244);
        $pdf->SetFillColor(244,244,244);
        $pdf->Cell(
            0,
            10,
            utf8_decode( ' '.R::count('paper').' ARTIGOS CADASTRADOS '),
            'LRTB',
            0,
            'L',
            true
        );

        $pdf->Ln(10);

        /*
         * Loading TG's
        */
        $tgs = R::find(
            'thematicgroup',
            ' is_listable = "Y" ORDER BY name ASC'
        );


        foreach ( $tgs as $tg ) :

            /*
             * Loading Papers
            */
            $papers = $tg->with(' ORDER BY title')->ownPaperList;

            $pdf->Ln(10);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(217,217,217);
            $pdf->SetFillColor(217,217,217);
            $pdf->Cell(
                0,
                10,
                utf8_decode(' GT - '.$tg->name),
                'LRTB',
                0,
                'L',
                true
            );

            $pdf->Ln(10);

            $pdf->Ln(0);

            $pdf->SetFont('Courier','B',9);

            $pdf->SetDrawColor(170,170,170);
            $pdf->SetFillColor(244,244,244);
            $pdf->Cell(
                0,
                10,
                utf8_decode(' ARTIGOS | '.count( $papers ).' Artigo(s) cadastrado(s)'),
                'LRTB',
                0,
                'L',
                true
            );

            $pdf->Ln(10);


            /*
             * Showing papers
            */
            foreach ( $papers as $p ) :

                $pdf->SetFillColor(255,255,255);

                $pdf->Cell(
                    170,
                    10,
                    strtoupper( character_limiter( utf8_decode(' '.trim( $p->title ) ) , 70, '...' ) ),
                    'LT',
                    0,
                    'L',
                    false
                );

                $evaluation = '';

                /* If it's waiting evaluation */
                if ( $p->evaluation == 'pending' ) :

                    $evaluation = 'ESPERANDO AVALIAÇÃO';
                    $pdf->SetFillColor(255, 187, 153);

                elseif ( $p->evaluation == 'accepted' ) :

                    $evaluation = 'ACEITO';
                    $pdf->SetFillColor(174, 234, 174);

                elseif ( $p->evaluation == 'rejected' ) :

                        $evaluation = 'REJEITADO';
                        $pdf->SetFillColor(255, 77, 77);

                endif;


                $pdf->Cell(
                    107,
                    10,
                    utf8_decode( $evaluation ),
                    'T',
                    0,
                    'C',
                    true
                );

                $pdf->Ln(10);

                $pdf->MultiCell(
                    277,
                    10,
                    ' '.trim( str_replace ( '||' , ',' , utf8_decode( $p->authors )  ) ),
                    'LBR',
                    'L',
                    false
                );

            endforeach;

            $pdf->Ln(10);

        endforeach;

        $pdf->Output();

    }

    public function reportGts()
    {

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

    private function installConfig($config) {
        if(!R::count('configuration','name=?',array($config->name))){
            $c = R::dispense('configuration');
            $c->name = $config->name;
            $c->nickname = $config->nickname;
            $c->value = $config->value;
            $c->type = $config->type;
            R::store($c);
            echo "<p style='color: green;'><b>{$config->nickname}</b> was successfully installed.</p>";
        }else{
            echo "<p style='color: orange;'><b>{$config->nickname}</b> wasn't successfully installed, it already exists.</p>";
        }
    }


    /*
     * Function : installConfigs()
     * Description : Install all basic configurations
    */
    public function installConfigs()
    {
        $this->load->library(array('session', 'rb'));
        $this->load->helper(array('url', 'form'));
        $this->load->model('Configuration');

        $config = new Configuration();

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

        echo '<h1>Instalação de Configurações</h1>';

        // MAX DATE FOR PAPERS SUBMISSIONS
        $config->setConfig('max_date_paper_submission', 'Data limite de submissão de artigo',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR POSTERS SUBMISSIONS
        $config->setConfig('max_date_poster_submission', 'Data limite de submissão de pôsteres',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR POSTERS SUBMISSIONS
        $config->setConfig('max_date_poster_file_submission',
          'Data limite de submissão do arquivo de pôster, após aceitação',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR MINICOURSE SUBMISSIONS
        $config->setConfig('max_date_minicourse_submission',
          'Data limite de submissão de minicurso',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR WORKSHOP SUBMISSIONS
        $config->setConfig('max_date_workshop_submission', 'Data limite de submissão de oficina',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR ROUNDTABLE SUBMISSIONS
        $config->setConfig('max_date_roundtable_submission', 'Data limite de submissão de mesa-redonda',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR CASE STUDY SUBMISSIONS
        $config->setConfig('max_date_teachingcases_submission', 'Data limite de submissão de casos de ensino',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR ROUNDTABLE CONSOLIDATION
        $config->setConfig('max_date_roundtable_consolidation', 'Data limite para consolidação de mesas-redondas',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR MINICOURSE CONSOLIDATION
        $config->setConfig('max_date_minicourse_consolidation', 'Data limite para consolidação de minicursos',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // MAX DATE FOR CONFERENCE CONSOLIDATION
        $config->setConfig('max_date_conference_consolidation', 'Data limite para consolidação de conferências',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // START DATE FOR MINICOURSE INSCRIPTIONS
        $config->setConfig('start_date_minicourse_inscription', 'Data inicial para inscrições em minicursos',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // END DATE FOR MINICOURSE INSCRIPTIONS
        $config->setConfig('end_date_minicourse_inscription', 'Data limite de inscrições em minicursos',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // START DATE FOR WORKSHOP INSCRIPTIONS
        $config->setConfig('start_date_workshop_inscription', 'Data inicial para inscrições em oficinas',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // END DATE FOR WORKSHOP INSCRIPTIONS
        $config->setConfig('end_date_workshop_inscription', 'Data limite de inscrições em oficinas',
          '2017-03-26', 'date');
        $this->installConfig($config);


        // START DATE FOR ROUNDTABLE INSCRIPTIONS
        $config->setConfig('start_date_roundtable_inscription', 'Data inicial para inscrições em mesas-redondas',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // END DATE FOR ROUNDTABLE INSCRIPTIONS
        $config->setConfig('end_date_roundtable_inscription', 'Data limite de inscrições em mesas-redondas',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // START DATE FOR CONFERENCE INSCRIPTIONS
        $config->setConfig('start_date_conference_inscription', 'Data inicial para inscrições em conferências',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // END DATE FOR CONFERENCE INSCRIPTIONS
        $config->setConfig('end_date_conference_inscription', 'Data limite de inscrições em conferências',
          '2017-03-26', 'date');
        $this->installConfig($config);

        // ONE OR TWO AVALIATIONS PER PAPER?
        $config->setConfig('two_avaliations_paper', 'Duas avaliações por artigo?',
          'false', 'boolean');
        $this->installConfig($config);

        // WOKRS NEED PAYMENT TO BE SENT?
        $config->setConfig('need_payment', 'Trabalhos precisam de pagamento para serem enviados?',
          'false', 'boolean');
        $this->installConfig($config);

        echo "<p style='color: green; font-weight: bold;'>All configurations were successfully updated or installed. :D</p>";

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
