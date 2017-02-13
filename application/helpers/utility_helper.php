<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    if ( ! function_exists('asset_url()'))
    {
        
        function asset_url(){
          return base_url().'assets/';
        }
        
    }

    // RETURN TRUE IF $DATE1 IS LESS OR EQUAL TO $DATE2, RETURN FALSE OTHERWISE
    function dateleq($date1,$date2){
        
        $fdate1 = explode('-',$date1);
        $fdate2 = explode('-',$date2);
        
        if($fdate1[0]<$fdate2[0])
            return true;
        else if($fdate1[0]==$fdate2[0])
            if($fdate1[1]<$fdate2[1])
                return true;
            else if($fdate1[1]==$fdate2[1])
                if($fdate1[2]<=$fdate2[2])
                    return true;
        
        return false;
        
    }

    // RETURN TRUE IF $DATE1 IS BIGGER OR EQUAL TO $DATE2, RETURN FALSE OTHERWISE
    function datebeq($date1,$date2){
        
        $fdate1 = explode('-',$date1);
        $fdate2 = explode('-',$date2);
        
        if($fdate1[0]>$fdate2[0])
            return true;
        else if($fdate1[0]==$fdate2[0])
            if($fdate1[1]>$fdate2[1])
                return true;
            else if($fdate1[1]==$fdate2[1])
                if($fdate1[2]>=$fdate2[2])
                    return true;
        
        return false;
        
    }

    function monthName($monthnum){
        
        $months = array(
            'janeiro',
            'fevereiro',
            'março',
            'abril',
            'maio',
            'junho',
            'julho',
            'agosto',
            'setembro',
            'outubro',
            'novembro',
            'dezembro'
        );
        
        return $months[$monthnum-1];
        
    }

    function customErrorMessages($formValidation){
        
        $formValidation->set_message('required', 'O campo %s é obrigatório.');
        $formValidation->set_message('valid_email', 'O campo %s precisa receber um email válido.');
        $formValidation->set_message('numeric', 'O campo %s precisa ser numérico.');
        $formValidation->set_message('matches', 'O campo %s precisa ser igual ao campo %s.');
        $formValidation->set_message('verifyingAuthors', 'O campo %s deve ter no máximo %s componentes.');
        
    }

    function emailMsg($msg){
        
        $html = "<html>";
        $html .= "<head>";
        $html .= "<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>";
        $html .= "<style>*{font-family: 'Open Sans', sans-serif;}</style>";
        $html .= "</head>";
        $html .= "<body>";
        $html .= $msg;
        $html .= "<p><b>Não responda este email, ele é gerado automaticamente - Assessoria Técnica do CCSA.</b>.</p> ";
        $html .= "</body>";
        $html .= "</html>";
        return $html;
    }

    function insertGetParameter($uri,$name,$value){
        
        $u = explode('/',$uri);
        $index = array_search($name,$u);
        
        if($index!=NULL){
            $u[$index+1] = $value;
        }else{
            array_push($u,$name,$value);
        }
        
        return implode('/',$u);
        
    }

    function insertTwoGetParameters($uri,$name,$value,$name2,$value2){
        
        $u = explode('/',$uri);
        
        $index = array_search($name,$u);
        
        if($index!=NULL){
            $u[$index+1] = $value;
        }else{
            array_push($u,$name,$value);
        }
        
        $index = array_search($name2,$u);
        
        if($index!=NULL){
            $u[$index+1] = $value2;
        }else{
            array_push($u,$name2,$value2);
        }
        
        return implode('/',$u);
        
    }

    function getValueParameter($uri,$name){
        
        $u = explode('/',$uri);
        $index = array_search($name,$u);
        
        if($index!=NULL)
            return $u[$index+1];
        else
            return NULL;
        
    } 
    
    function b2Upper($text)
    {
        
        $minus = ['ç','é','è','ê','ú','ù','û','ó','ò','õ','ô','í','ì','î','á','à','ã','â'];
        $upper = ['Ç','É','È','Ê','Ú','Ù','Û','Ó','Ò','Õ','Ô','Í','Ì','Î','Á','À','Ã','Â'];
        
        for($i = 0 ; $i < mb_strlen($text) ; ++$i ) :
        
            $r = array_search(substr($text, $i, 1), $minus);
            echo substr($text, $i, 1).'('.$i.')'.'<br>';
            
        
            if($r) : 
            
                $text = substr_replace(
                    $text,
                    $upper[$r],
                    $i,
                    1
                );
            
            endif;
        
        endfor;
        
        return $text;
        
    }

    function titleCase(
        $string, 
        $delimiters = array(" ", "-", ".", "'", "O'", "Mc", "Dr", "Dra", "[", "]"), 
        $exceptions = array("um", "uma", "uns", "em","no", "nos", "na", "nas","à" ,"de", "do", "da", "das", "dos", "a", "o", "e","[IFRN]", "[UFPE]","[PPGA/UFPB]", "[PPGP/UFRN]", "[FAIBRA]","[UFRN]", "[ufrn]","[UNIRI]", "I", "II", "III", "IV", "V", "VI")
    ){
        /*
        * Exceptions in lower case are words you don't want converted
        * Exceptions all in upper case are any words you don't want converted to title case
        *   but should be converted to upper case, e.g.:
        *   king henry viii or king henry Viii should be King Henry VIII
        */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
    }//foreach
    return $string;
    }

?>