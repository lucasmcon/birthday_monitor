<?php

    include ('database.php');


    class birthdayMonitor{

        public function verifyBirthday(){


            $cont = 0;
            date_default_timezone_set('America/Sao_Paulo');

            $currentDay = date('d');
            $currentMonth = date('m');
            
            $Rs = $this->searchCustomerBirthday();
            $rows = count($Rs);

            $to = ''; //E-mail para qual o lembrete será enviado
            $headers = 'From: <remetente>'; //Caso seu servidor SMTP tenha bloqueio SKF/DKIM é importante manter o rementente identico ao e-mail configurado
            $subject = ''; //Assunto do e-mail
            
            $body = 'AVISO: Aniversariantes dos proximos dias: '.PHP_EOL.PHP_EOL;
            

            for($i=0; $i<$rows; $i++){
   
                $dt_nascimento_explode = explode("-", $Rs[$i]['<coluna data de nascimento>']);

                if($dt_nascimento_explode[1] - $currentMonth == 0 && $dt_nascimento_explode[0] - $currentDay == 5){
                    
                    //Abaixo você monta o email de acordo com sua estrutura de banco de dados. abaixo é só um exemplo
                    $body.= 'Cliente: '.$Rs[$i]['razao_social'].PHP_EOL;
                    $body.= 'UF: '.$Rs[$i]['sigla_uf'].PHP_EOL;
                    $body.= 'Qualificação: '.$Rs[$i]['qualificacao'].PHP_EOL;
                    $body.= 'Contato: '.$Rs[$i]['nome_contato'].' - Dt Nascimento: '.$Rs[$i]['dt_nascimento'].PHP_EOL.PHP_EOL;

                    $cont++;    
                    
                } 
            } 
            
            if($cont > 0){
                mail($to, $subject, $body, $headers);
            }
        }

        public function searchCustomerBirthday(){
            
            $database = new database();
            
            $StrSQL = ""; //String SQL, montar de acordo com sua estrutura de banco de dados

            $Rs = $database->Consult($StrSQL);

            return $Rs;

        }

    }
