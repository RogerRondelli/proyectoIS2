<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;
use Api\DataTables;
use Cocur\BackgroundProcess\BackgroundProcess;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


// Render Twig template in route
$app->get('/', function ($request, $response, $args) {
    return $this->renderer->render($response, 'index.phtml', [
        'name' => 'Escribe algo en la barra de navegacion'
    ]);
});

$app->group('/api', function(\Slim\App $app) {
    //INICIO DE SESION NORMAL
        $app->post('/users/signin', function(Request $request, Response $response){
            try {
                $usuario = $_POST['usu'];
                $password = md5($_POST['password']);
                $db = $this->db;

                $sql = "SELECT id_usuario,nombre_usuario,rol,nombre 
                        FROM usuarios 
                        WHERE nombre_usuario = '$usuario' AND password = '$password' AND estado = 1";
                $user = consulta($db,$sql);
        
                // verify email address.
                if(!$user) {
                    return $this->response->withJson([
                                                        'status' => false, 
                                                        'code' => 500, 
                                                        'message' => 'Estas credenciales no coinciden con nuestros registros!',
                                                        'data' => []
                                                    ]);
                }
        
                $data = [
                    'id_usuario'=> $user[0]->id_usuario, 
                    'nombre'=> $user[0]->nombre,
                    'nombre_usuario'=> $user[0]->nombre_usuario,
                    'local'=> $user[0]->local,
                    'rol'=> $user[0]->rol,
                    'redirect' => 'inicio.php'
                ];
        
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $data
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

    //USUARIOS
        $app->get('/users/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT SQL_CALC_FOUND_ROWS u.id_usuario,u.nombre_usuario, u.password, u.nombre
                    , u.apellido, u.fecha_registro, u.estado
                    , ro.id_rol, ro.rol
                    FROM usuarios u
                    LEFT JOIN roles ro ON u.rol = ro.id_rol
                    ORDER BY u.id_usuario ASC";

                $usuarios = consulta($db,$sql);
                $total_registros = consulta($db,$sql,1);

                // verify email address.
                if(!$usuarios) {
                    return $this->response->withJson([
                                                        'status' => false, 
                                                        'code' => 500, 
                                                        'message' => 'Ocurrio un error!',
                                                        'data' => []
                                                    ]);
                }

                $salida[] = array(
                    'TotalRows' => $total_registros,
                    'Rows' => $usuarios
                );
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $usuarios
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->get('/users/roles', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT id_rol, rol from roles where estado = 1 order by 2";

                $roles = consulta($db,$sql);

                // verify email address.
                if(!$roles) {
                    return $this->response->withJson([
                                                        'status' => false, 
                                                        'code' => 500, 
                                                        'message' => 'Ocurrio un error!',
                                                        'data' => []
                                                    ]);
                }
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $roles
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/users/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $usuario = $_POST['usuario'];
                $rol = $_POST['rol'];
                $password = md5($_POST['password']);
                $sql = "INSERT INTO usuarios (nombre, apellido, nombre_usuario, rol, password, estado) 
                                            VALUES
                                            ('$nombre', '$apellido', '$usuario', $rol, '$password', 1)";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/users/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_usuario = $_POST['id_usuario'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $usuario = $_POST['usuario'];
                $rol = $_POST['rol'];
                $params = '';

                if (isset($_POST['password']) && $_POST['password'] != '') {
                    $params = ",password ='".md5($_POST['password'])."'";
                }

                $sql = "UPDATE usuarios SET nombre='$nombre', 
                                            apellido='$apellido',
                                            nombre_usuario='$usuario', 
                                            rol= '$rol', 
                                            estado=1 
                                            $params
                                            where id_usuario='$id_usuario'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/users/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_usuario = $_POST['id_usuario'];

                $sql = "DELETE FROM usuarios WHERE id_usuario='$id_usuario'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

    //ROLES
        $app->get('/roles/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT SQL_CALC_FOUND_ROWS id_rol, rol, estado, acceso AS id_acceso,
                        (CASE 
                            WHEN acceso = 1 THEN 'Semi-Total'
                            WHEN acceso = 2 THEN 'Local y Sublocales'
                            WHEN acceso = 1 THEN 'Local'
                            ELSE 'Total'
                        END) AS acceso 
                        FROM roles
                        ORDER BY id_rol ASC";

                $roles = consulta($db,$sql);
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $roles
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/roles/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $rol = $_POST['rol'];
                $acceso = $_POST['acceso'];

                $sql = "INSERT INTO roles (rol, acceso, estado) 
                                            VALUES
                                            ('$rol', '$acceso', 1)";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/roles/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $estado = 0;
                $id_rol = $_POST['id_rol'];
                $rol = $_POST['rol'];
                $acceso = $_POST['acceso'];

                $sql = "UPDATE roles SET rol='$rol', 
                                        acceso=$acceso 
                        WHERE id_rol=$id_rol";

                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/roles/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_rol = $_POST['id_rol'];

                $sql = "DELETE FROM roles WHERE id_rol='$id_rol'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

    //PROYECTOS
        $app->get('/proyectos/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT SQL_CALC_FOUND_ROWS id_proyecto, nombre, estado
                        FROM proyectos
                        ORDER BY id_proyecto ASC";

                $proyectos = consulta($db,$sql);

                $sql = "SELECT SQL_CALC_FOUND_ROWS u.id_usuario,u.apellido,u.nombre, r.id_proyecto
                        FROM usuarios u
                        LEFT JOIN relaciones_usuarios r ON r.id_usuario = u.id_usuario
                        WHERE u.rol != 1
                        ORDER BY u.id_usuario ASC";

                $usuarios = consulta($db,$sql);
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $proyectos,
                                                    'usuarios' => $usuarios
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/proyectos/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $id_usuario = $_POST['id_usuario'];

                $sql = "INSERT INTO proyectos 
                        (nombre, estado) 
                        VALUES
                        ('$nombre', '$estado')";

                $stmt = $db->prepare($sql);
                $stmt->execute();
                $id_proyecto = $db->lastInsertId();

                for ($i=0; $i < count($id_usuario); $i++) { 
                    $sql = "INSERT INTO relaciones_usuarios 
                            (id_proyecto, id_usuario) 
                            VALUES
                            ('$id_proyecto', '$id_usuario[$i]')";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                }
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/proyectos/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $estado = 0;
                $id_proyecto = $_POST['id_proyecto'];
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $id_usuario = $_POST['id_usuario'];

                $sql = "UPDATE proyectos SET nombre='$nombre', 
                                        estado='$estado'
                        WHERE id_proyecto=$id_proyecto";

                $stmt = $db->prepare($sql);
                $stmt->execute();

                $limpiar = "DELETE FROM relaciones_usuarios WHERE id_proyecto = '$id_proyecto'";
                $stmt = $db->prepare($limpiar);
                $stmt->execute();

                for ($i=0; $i < count($id_usuario); $i++) { 
                    $sql = "INSERT INTO relaciones_usuarios 
                            (id_proyecto, id_usuario) 
                            VALUES
                            ('$id_proyecto', '$id_usuario[$i]')";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                }
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/proyectos/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_proyecto = $_POST['id_proyecto'];

                $sql = "DELETE FROM proyectos WHERE id_proyecto='$id_proyecto'";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                $sql = "DELETE FROM relaciones_usuarios WHERE id_proyecto='$id_proyecto'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });
    
    //TAREAS
        $app->get('/tareas/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT SQL_CALC_FOUND_ROWS t.id_tarea, t.nombre, t.estado,t.descripcion,t.version,t.prioridad,t.observacion,
                        p.nombre as proyecto, p.id_proyecto,
                        r.id_tarea AS 'existe'
                        FROM tareas t
                        JOIN proyectos p ON p.id_proyecto = t.id_proyecto
                        LEFT JOIN relaciones r ON t.id_tarea = r.id_tarea
                        ORDER BY t.id_tarea ASC";

                $tareas = consulta($db,$sql);

                $sql = "SELECT t.id_tarea,
                        (
                            SELECT ta.nombre
                            FROM relaciones_tareas re
                            JOIN tareas ta ON re.id_tarea = ta.id_tarea
                            WHERE re.id_tarea = (
                                                SELECT MAX(id_tarea) 
                                                FROM relaciones_tareas
                                                WHERE id_tarea < t.id_tarea AND id_proyecto = t.id_proyecto
                                            )
                        ) AS nombre
                        FROM relaciones_tareas r
                        JOIN proyectos p ON r.id_proyecto = p.id_proyecto
                        JOIN tareas t ON r.id_tarea = t.id_tarea
                        ORDER BY r.id_relacion ASC";
                
                $padres = consulta($db,$sql);
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $tareas,
                                                    'padres' => $padres,
                                                ]);
            
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/tareas/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $descripcion = $_POST['descripcion'];
                $prioridad = $_POST['prioridad'];
                $observacion = $_POST['observacion'];
                $id_proyecto = $_POST['id_proyecto'];
                $version = $_POST['version'];

                $sql_ = "SELECT id_tarea
                        FROM tareas
                        WHERE id_proyecto = '$id_proyecto'
                        ORDER BY id_tarea DESC LIMIT 1";

                $id_tarea_padre = consulta($db,$sql_);

                if($id_tarea_padre){
                    $id_tarea_padre = $id_tarea_padre[0]->id_tarea;
                }else{
                    $id_tarea_padre = '';
                }

                $sql = "INSERT INTO tareas (nombre, estado, descripcion, prioridad, observacion, id_tarea_padre,id_proyecto,version) 
                                            VALUES
                                            ('$nombre', '$estado', '$descripcion', '$prioridad', '$observacion', '$id_tarea_padre','$id_proyecto','$version')";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $id_tarea = $db->lastInsertId();

                $sql = "INSERT INTO relaciones_tareas (id_proyecto,id_tarea) 
                                            VALUES
                                            ('$id_proyecto','$id_tarea')";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/tareas/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $estado = 0;
                $id_tarea = $_POST['id_tarea'];
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $descripcion = $_POST['descripcion'];
                $prioridad = $_POST['prioridad'];
                $observacion = $_POST['observacion'];
                $id_proyecto = $_POST['id_proyecto'];
                $edicion_tarea = $_POST['edicion_tarea'];
                $version = $_POST['version'];

                if($edicion_tarea == 1){
                    $sql = "DELETE FROM relaciones_tareas WHERE id_tarea='$id_tarea'";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();

                    $sql = "INSERT INTO relaciones_tareas (id_proyecto,id_tarea) 
                                            VALUES
                                            ('$id_proyecto','$id_tarea')";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                }

                $sql = "UPDATE tareas SET nombre='$nombre', 
                                        estado='$estado',
                                        descripcion='$descripcion', 
                                        prioridad='$prioridad', 
                                        observacion='$observacion', 
                                        version='$version', 
                                        id_proyecto='$id_proyecto' 
                        WHERE id_tarea='$id_tarea'";

                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/tareas/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_tarea = $_POST['id_tarea'];

                $sql = "DELETE FROM tareas WHERE id_tarea='$id_tarea'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

    //LINEAS BASE
        $app->get('/lineasbase/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT SQL_CALC_FOUND_ROWS l.id_linea_base, l.nombre, l.estado,
                        p.nombre as proyecto, p.id_proyecto
                        FROM lineas_base l
                        JOIN proyectos p ON p.id_proyecto = l.id_proyecto
                        ORDER BY l.id_linea_base ASC";

                $lineas_base = consulta($db,$sql);

                $sql = "SELECT SQL_CALC_FOUND_ROWS t.id_tarea, t.nombre, t.estado,t.descripcion,t.version,
                        l.nombre as linea_base, l.id_linea_base
                        FROM relaciones r
                        JOIN tareas t ON r.id_tarea = t.id_tarea
                        JOIN lineas_base l ON r.id_linea_base = l.id_linea_base
                        ORDER BY r.id_linea_base ASC";

                $tareas_tabla = consulta($db,$sql);

                $sql = "SELECT DISTINCT t.id_tarea, t.nombre, t.id_proyecto,
                        r.id_tarea AS 'existe'
                        FROM tareas t
                        LEFT JOIN relaciones r ON r.id_tarea = t.id_tarea 
                        WHERE t.estado != 'Finalizado'
                        ORDER BY r.id_linea_base ASC";

                $tareas_select = consulta($db,$sql);

                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $lineas_base,
                                                    'tareas_tabla' => $tareas_tabla,
                                                    'tareas_select' => $tareas_select
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/lineasbase/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $id_proyecto = $_POST['id_proyecto'];
                $id_tarea = $_POST['id_tarea'];

                $sql = "INSERT INTO lineas_base (nombre, estado, id_proyecto) 
                                            VALUES
                                            ('$nombre', '$estado', '$id_proyecto')";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $id_linea_base = $db->lastInsertId();

                for ($i=0; $i < count($id_tarea); $i++) { 
                    $sql = "INSERT INTO relaciones (id_linea_base, id_tarea) 
                                                VALUES
                                                ('$id_linea_base', '$id_tarea[$i]')";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                }
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/lineasbase/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $id_linea_base = $_POST['id_linea_base'];
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $id_proyecto = $_POST['id_proyecto'];
                $id_tarea = $_POST['id_tarea'];

                $sql = "UPDATE lineas_base SET nombre='$nombre', 
                                        estado='$estado',
                                        id_proyecto='$id_proyecto'
                        WHERE id_linea_base='$id_linea_base'";

                $stmt = $db->prepare($sql);
                $stmt->execute();
                
                $limpiar = "DELETE FROM relaciones WHERE id_linea_base = '$id_linea_base'";
                $stmt = $db->prepare($limpiar);
                $stmt->execute();

                for ($i=0; $i < count($id_tarea); $i++) { 
                    $sql = "INSERT INTO relaciones (id_linea_base, id_tarea) 
                                                VALUES
                                                ('$id_linea_base', '$id_tarea[$i]')";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                }

                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });

        $app->post('/lineasbase/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_linea_base = $_POST['id_linea_base'];

                $sql = "DELETE FROM lineas_base WHERE id_linea_base='$id_linea_base'";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                $sql = "DELETE FROM relaciones WHERE id_linea_base='$id_linea_base'";
                $stmt = $db->prepare($sql);
                $stmt->execute();
            
                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => ''
                                                ]);
            } 
            catch(PDOException $e)
            {
                return json_encode( array( 
                                            'code' => 505, 
                                            'status' => false, 
                                            'message' => '',
                                            'data' => $e ) 
                                        );
            }
        });
});