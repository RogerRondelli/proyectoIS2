<?php
set_time_limit(0);
try {
    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/../../admin/client/server.php';
    require __DIR__ . '/../../admin/client/'.CLIENT_SERVER.'/config/names.php';
    // Set up tools
    require __DIR__ . '/tools.php';



    class db{
        //Offline
        private $dbhost = 'localhost';
        private $dbuser = DB_USER;
        private $dbpass = DB_PSW;
        private $dbname = DB_BASE;
       
        // Connect
        public function connect(){
            $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
            $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $dbConnection;
        }
    }

    function saveNotificacion( $db, $data ){
        try {            
            $title = $data['titulo'];
            $notif_msg = $data['descripcion'];
            $notif_time = date('Y-m-d H:i:s');
            $query = "  INSERT INTO notificaciones_mensajes (
                            title,
                            notif_msg,
                            notif_time
                        )
                        VALUES
                            (
                            '$title',
                            '$notif_msg',
                            '$notif_time'
                            );";
            $sth = $db->prepare($query);
            $sth->execute();
            return $db->lastInsertId();
        } catch (PDOException $th) {
            throw $th;
        }
    }

    function saveNotificacionUser( $db, $data ){
        try {

            $tipo_nu = $data['tipo_nu'];
            $id_usu = $data['id_usu'];
            $id_nm = $data['id_nm'];
            $id_cat = $data['id_cat'];
            $msj_texto = $data['msj_texto'];
            $email = $data['email'];
            $app = $data['app'];
            $estado_msj = $data['estado_msj'];
            $estado_email = $data['estado_email'];
            $estado_app = $data['estado_app'];
            $id_denuncia = $data['id_denuncia'];

            $query = "  INSERT INTO noticaciones_usuarios (
                                                        tipo_nu,
                                                        id_usu,
                                                        id_nm,
                                                        id_cat,
                                                        msj_texto,
                                                        email,
                                                        app,
                                                        estado_msj,
                                                        estado_email,
                                                        estado_app,
                                                        id_denuncia
                                                    )
                                                    VALUES
                                                        (
                                                        '$tipo_nu',
                                                        '$id_usu',
                                                        '$id_nm',
                                                        '$id_cat',
                                                        '$msj_texto',
                                                        '$email',
                                                        '$app',
                                                        '$estado_msj',
                                                        '$estado_email',
                                                        '$estado_app',
                                                        '$id_denuncia'
                                                        );";
    
            $sth = $db->prepare($query);
            $sth->execute();

        } catch(PDOException $th) {
            throw $th;
        }
    }

    function sendMsjUser($data){
        $number = validationNumber($data['tel']);
        $texto =  $data['title'].": ".$data['body'];
        return sendMsg("$number", "$texto" ,1, time());
    }

    function sendEmailUser($db,$id_cat,$data){
        
        $myService = new SendMail();
        $title = $data['title'];
        $from = ['info@denunciaspy.org' => 'Sistema de Gestión'];
        $to = $data['to'];
        $body = $data['body'];
        return $myService->Send($title,$from,$to,$body);        
    }

    function responseLog($response){
        file_put_contents(__DIR__.'/../logs/logBackground/'.date("j.n.Y").'.log', "[".date('H:i:s')."] ".$response."\n", FILE_APPEND);
    }
    

    // // var_dump($argv);
    responseLog("*****************Inicio*******************");

    if( isset( $argv[1] ) ){

        $db = new db();
        $db = $db->connect();

        $id_cat = $argv[1];
        $id_denuncia = $argv[2];

        $sql = "SELECT * FROM  ".DB_BASE.".categorias where id_cat = '$id_cat' ";
        $datos = consultaArray($db,$sql);
        $id_ente = $datos[0]['id_ente'];

        $data = [];
        $data['titulo'] = "DenunciasPy";
        $data['descripcion'] = "Tiene una nueva notificación. N°: $id_denuncia";
        $id_not = saveNotificacion( $db, $data );

        //////////// Para los jefes y subjefes
        $sql1 = "SELECT u.*
                    FROM categorias c 
                    JOIN direccionamientos dir ON dir.id_cat=c.id_cat
                    JOIN departamentos dep ON dep.id_departamento=dir.id_departamento
                    JOIN usuarios u ON u.id_departamento = dep.`id_departamento`
                    WHERE c.id_cat = '$id_cat' AND u.id_rol != 1 AND u.id_rol !=2
                ";
        $datos1 = consultaArray($db,$sql1);
        $usuAuxi = '';
        if( count($datos1) ){

            foreach ($datos1 as $key => $value) {
            $res = json_encode($value);
                responseLog(" usuario1 : $res ");
                $id_usu = $value['id_agente'];
                $responseMsj = "";
                $responseEmail = "";
                $responseApp = "";

                if( $datos[0]['msj_texto'] == '1' ){
                    try {
                        if( $value['telefono_age'] != ""){
                            $dataMsj = [
                                'tel' => $value['telefono_age'],
                                'title' => "DenunciasPY",
                                'body' => "Tiene una nueva notificación. N°: $id_denuncia"
                            ];
                            $responseMsj = sendMsjUser($dataMsj);
                        }
                    } catch (\Throwable $th) {
                        $responseMsj = ["error" => $th->getMessage()];
                    }
                    responseLog("ResponseMsj: ".json_encode($responseMsj));
                }    
        
                if( $datos[0]['email'] == '1' ){
                    try {
                        if( $value['email_age'] != "" ){
                            $dataEmail = [
                                'title' => '📌 DenunciasPY', 
                                'to' => [$value['email_age']],
                                'body' => "Tiene una nueva notificación. N°: $id_denuncia"
                            ];
                            $responseEmail = sendEmailUser($db,$id_cat,$dataEmail);
                        }
                    } catch (\Throwable $th) {
                        $responseEmail = ["error" => $th->getMessage()];
                    }
                    responseLog("ResponseEmail: ".json_encode($responseEmail));
                }
        
                if( $datos[0]['app'] == '1' ){
                    try {
                        $sqlApp = "SELECT * FROM usuarios_config WHERE id_usu = '$id_usu' ";
                        $datosApp = consultaArray($db,$sqlApp);
                        foreach ($datosApp as $k => $v) {
                            $token = $v['key_firebase'];
                            if( $token != ""){
                                $title = "DenunciasPy";
                                $desc = 'Hay una nueva notificacion N° '.$id_denuncia;
                                $dataApp = ['title' => $title, 'body' => $desc];
                                $responseApp = sendApp($token,$title,$desc, $dataApp);
                                responseLog("ResponseApp: ".json_encode($responseApp));
                            } 
                        }
                    } catch (\Throwable $th) {
                        $responseApp = ["error" => $th->getMessage()];
                    }
                    responseLog("ResponseApp: ".json_encode($responseApp));
                }


                $data = [];              
                $data['tipo_nu'] = $id_cat;
                $data['id_usu'] =  $id_usu;
                $data['id_nm'] = $id_not;
                $data['id_cat'] =  $id_cat;
                $data['msj_texto'] = $datos[0]['msj_texto'];
                $data['email'] = $datos[0]['email'];
                $data['app'] = $datos[0]['app'];
                $data['estado_msj'] = $responseMsj;
                $data['estado_email'] = $responseEmail;
                $data['estado_app'] = $responseApp;
                $data['id_denuncia'] = $id_denuncia;
               
                saveNotificacionUser( $db, $data );
            }
        }else{
            responseLog(" no hay usuarios1");
        }

        //////////// Para los admin y gerentes
        $sql2 = "SELECT u.*
                    FROM usuarios u 
                    WHERE (u.id_rol = 1 OR u.id_rol =2)
                    AND id_ente = '$id_ente'
                ";
                //  responseLog("");
        $datos2 = consultaArray($db,$sql2);
        if( count($datos2) ){

            foreach ($datos2 as $key => $value) {
            $res = json_encode($value);
                responseLog(" usuario2 : $res ");
                $id_usu = $value['id_agente'];
                $responseMsj = "";
                $responseEmail = "";
                $responseApp = "";

                if( $datos[0]['msj_texto'] == '1' ){
                    try {
                        if( $value['telefono_age'] != ""){
                            $dataMsj = [
                                'tel' => $value['telefono_age'],
                                'title' => "DenunciasPY",
                                'body' => "Tiene una nueva notificación. N°: $id_denuncia"
                            ];
                            $responseMsj = sendMsjUser($dataMsj);
                        }
                    } catch (\Throwable $th) {
                        $responseMsj = ["error" => $th->getMessage()];
                    }
                    responseLog("ResponseMsj: ".json_encode($responseMsj));
                }    
        
                if( $datos[0]['email'] == '1' ){
                    try {
                        if( $value['email_age'] != "" ){
                            $dataEmail = [
                                'title' => '📌 DenunciasPY', 
                                'to' => [$value['email_age']],
                                'body' => "Tiene una nueva notificación. N°: $id_denuncia"
                            ];
                            $responseEmail = sendEmailUser($db,$id_cat,$dataEmail);
                        }
                    } catch (\Throwable $th) {
                        $responseEmail = ["error" => $th->getMessage()];
                    }
                    responseLog("ResponseEmail: ".json_encode($responseEmail));
                }
        
                if( $datos[0]['app'] == '1' ){
                    try {
                        $sqlApp = "SELECT * FROM usuarios_config WHERE id_usu = '$id_usu' ";
                        $datosApp = consultaArray($db,$sqlApp);
                        foreach ($datosApp as $k => $v) {   
                            $token = $v['key_firebase'];
                            if( $token != ""){
                                $title = "DenunciasPy";
                                $desc = 'Hay una nueva notificacion N° '.$id_denuncia;
                                $dataApp = ['title' => $title, 'body' => $desc];
                                $responseApp = sendApp($token,$title,$desc, $dataApp);
                                responseLog("ResponseApp: ".json_encode($responseApp));
                            } 
                        }
                    } catch (\Throwable $th) {
                        $responseApp = ["error" => $th->getMessage()];
                    }
                    responseLog("ResponseApp: ".json_encode($responseApp));
                }

                $data = [];              
                $data['tipo_nu'] = $id_cat;
                $data['id_usu'] =  $id_usu;
                $data['id_nm'] = $id_not;
                $data['id_cat'] =  $id_cat;
                $data['msj_texto'] = $datos[0]['msj_texto'];
                $data['email'] = $datos[0]['email'];
                $data['app'] = $datos[0]['app'];
                $data['estado_msj'] = $responseMsj;
                $data['estado_email'] = $responseEmail;
                $data['estado_app'] = $responseApp;
                $data['id_denuncia'] = $id_denuncia;
                saveNotificacionUser( $db, $data );        
            }
        }else{
            responseLog(" no hay usuarios2");
        }

        $db = null;
        responseLog("no hay datos 3.");
        // file_put_contents(__DIR__.'/../logs/logBackground_'.date("j.n.Y").'.log', "argument: {$argv[1]} && {$argv[2]}  ", FILE_APPEND);
    }else{
        responseLog("no hay datos.");
    }
    responseLog("******************Fin*********************");

} catch (\Throwable $th) {
    file_put_contents(__DIR__.'/../logs/logBackground_'.date("j.n.Y").'.log', $th, FILE_APPEND);
}
