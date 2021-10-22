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

                $sql = "SELECT u.id_usuario,u.apellido,u.nombre, r.id_proyecto
                        FROM usuarios u
                        LEFT JOIN relaciones_usuarios r ON r.id_usuario = u.id_usuario
                        WHERE u.rol != 1 AND u.id_usuario NOT IN (SELECT id_usuario
                                            FROM relaciones_usuarios)
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

        $app->post('/proyecto/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $id = $_POST['id'];

                $sql = "SELECT p.id_proyecto, p.nombre, p.estado, u.id_usuario
                        FROM proyectos p
                        LEFT JOIN usuarios u ON u.id_proyecto = p.id_proyecto
                        WHERE p.id_proyecto = $id
                        ORDER BY p.id_proyecto ASC";

                $proyectos = consulta($db,$sql);

                $sql = "SELECT u.id_usuario,u.apellido,u.nombre, r.id_proyecto
                        FROM usuarios u
                        LEFT JOIN relaciones_usuarios r ON r.id_usuario = u.id_usuario
                        WHERE u.rol != 1 AND u.id_usuario NOT IN (SELECT id_usuario
                                            FROM relaciones_usuarios) OR u.id_proyecto = $id
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

                $sql = "UPDATE usuarios SET id_proyecto='$id_proyecto', 
                        WHERE id_usuario=$id_usuario";

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

    //BACKLOGS
        $app->get('/backlogs/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT b.nombre,p.nombre as nombre_proyecto,b.estado,b.id_backlog
                        FROM backlogs b
                        JOIN proyectos p ON p.id_proyecto = b.id_proyecto
                        ORDER BY b.id_backlog ASC";

                $backlogs = consulta($db,$sql);

                $sql = "SELECT *
                        FROM proyectos
                        WHERE id_proyecto NOT IN (SELECT id_proyecto
                                                    FROM backlogs)";

                $proyectos = consulta($db,$sql);

                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $backlogs,
                                                    'proyectos' => $proyectos,
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

        $app->post('/backlog/list', function(Request $request, Response $response){
            try {
                $id = $_POST['id'];
                $db = $this->db;

                $sql = "SELECT b.nombre,p.nombre as nombre_proyecto,b.estado,b.id_backlog,b.id_proyecto
                        FROM backlogs b
                        JOIN proyectos p ON p.id_proyecto = b.id_proyecto
                        WHERE b.id_backlog = $id
                        ORDER BY b.id_backlog ASC";

                $backlogs = consulta($db,$sql);

                $sql = "SELECT p.nombre, p.id_proyecto
                        FROM proyectos p
                        LEFT JOIN backlogs b ON (b.id_proyecto != p.id_proyecto AND b.id_proyecto = '') OR b.id_backlog = $id
                        ORDER BY p.id_proyecto ASC";

                $proyectos = consulta($db,$sql);

                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'data' => $backlogs,
                                                    'proyectos' => $proyectos,
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

        $app->post('/backlogs/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $nombre = $_POST['nombre'];
                $id_proyecto = $_POST['id_proyecto'];
                $estado = $_POST['estado'];

                $sql = "INSERT INTO backlogs (nombre, estado, id_proyecto) 
                                            VALUES
                                            ('$nombre', '$estado', '$id_proyecto')";
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

        $app->post('/backlogs/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $id_backlog = $_POST['id_backlog'];
                $nombre = $_POST['nombre'];
                $estado = $_POST['estado'];
                $id_proyecto = $_POST['id_proyecto'];

                $sql = "UPDATE backlogs SET nombre='$nombre', 
                                        estado='$estado',
                                        id_proyecto='$id_proyecto'
                        WHERE id_backlog='$id_backlog'";

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

        $app->post('/backlogs/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_backlog = $_POST['id_backlog'];

                $sql = "DELETE FROM backlogs WHERE id_backlog = $id_backlog";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                // $sql = "DELETE FROM relaciones WHERE id_backlog='$id_backlog'";
                // $stmt = $db->prepare($sql);
                // $stmt->execute();
            
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

    //USERSTURY
        $app->get('/userstories/list', function(Request $request, Response $response){
            try {
                $db = $this->db;

                $sql = "SELECT us.descripcion, us.id_user_storie, b.nombre, u.nombre as nombre_usuario, u.apellido
                        FROM user_stories us
                        JOIN backlogs b ON b.id_backlog = us.id_backlog
                        JOIN usuarios u ON u.id_usuario = us.id_usuario
                        ORDER BY us.id_user_storie ASC";

                $user_stories = consulta($db,$sql);

                $sql = "SELECT *
                        FROM backlogs
                        ORDER BY id_backlog ASC";

                $backlogs = consulta($db,$sql);

                $sql = "SELECT *
                        FROM usuarios
                        ORDER BY id_usuario ASC";

                $usuarios = consulta($db,$sql);

                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'user_stories' => $user_stories,
                                                    'backlogs' => $backlogs,
                                                    'usuarios' => $usuarios,
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

        $app->post('/userstorie/list', function(Request $request, Response $response){
            try {
                $id = $_POST['id'];
                $db = $this->db;

                $sql = "SELECT *
                        FROM user_stories us
                        JOIN backlogs b ON b.id_backlog = us.id_backlog
                        WHERE us.id_user_storie = $id
                        ORDER BY us.id_user_storie ASC";

                $user_stories = consulta($db,$sql);

                $sql = "SELECT *
                        FROM backlogs
                        ORDER BY id_backlog ASC";

                $backlogs = consulta($db,$sql);

                return $this->response->withJson([
                                                    'code' => 200,
                                                    'status' => true, 
                                                    'message' => '',
                                                    'user_stories' => $user_stories,
                                                    'backlogs' => $backlogs,
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

        $app->post('/userstories/create', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $descripcion = $_POST['descripcion'];
                $id_backlog = $_POST['id_backlog'];
                $id_usuario = $_POST['id_usuario'];

                $sql = "INSERT INTO user_stories (descripcion, id_backlog, id_usuario) 
                                            VALUES
                                            ('$descripcion', '$id_backlog', '$id_usuario')";
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

        $app->post('/userstories/edit', function(Request $request, Response $response){
            try {
                $db = $this->db;
                
                $id_user_storie = $_POST['id_user_storie'];
                $descripcion = $_POST['descripcion'];
                $id_backlog = $_POST['id_backlog'];
                $id_usuario = $_POST['id_usuario'];

                $sql = "UPDATE user_stories SET descripcion='$descripcion', 
                                        id_backlog='$id_backlog',
                                        id_usuario='$id_usuario'
                        WHERE id_user_storie='$id_user_storie'";

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

        $app->post('/userstories/delete', function(Request $request, Response $response){
            try {
                $db = $this->db;
                $id_user_storie = $_POST['id_user_storie'];

                $sql = "DELETE FROM user_stories WHERE id_user_storie = $id_user_storie";
                $stmt = $db->prepare($sql);
                $stmt->execute();

                // $sql = "DELETE FROM relaciones WHERE id_backlog='$id_backlog'";
                // $stmt = $db->prepare($sql);
                // $stmt->execute();
            
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