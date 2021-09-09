<?php
    function Aud(){
        $aud = '';
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        return sha1($aud);
    }

    function consulta($db,$query,$count=0){
        $stmt = $db->prepare($query);
        $stmt->execute();
        $datos_c = $stmt->fetchAll(PDO::FETCH_OBJ);
        if ($count != 0) {
            $datos_c =  $stmt->rowCount();
        }
        return $datos_c;
    }
    
    function consultaArray($db,$query){
        $stmt = $db->prepare($query);
        $stmt->execute();
        $datos_c = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $datos_c;
    }
?>