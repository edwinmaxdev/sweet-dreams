<?php
include_once __DIR__ . '/../../config/database.php';

class Usuario{
    private $db;

    public function __construct() {
        $this->db = Database::getConexion();
    }

    public function registrar($nombre , $email , $rol, $password , $telefono , $direccion , $pregunta , $respuesta)  {
        try{
            $nombre = trim($nombre);
            $email = trim($email);
            $rol = trim($rol);
            $password = trim($password);
            $telefono= trim($telefono);
            $direccion = trim($direccion);
            $pregunta = trim($pregunta);
            $respuesta = trim($respuesta);

            //validaciones
            if( $nombre === '' || $email === '' || $direccion ==''){
                return ['Exito' => false , 'mensaje'=>'Llenar los campos obligatorios'];
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                return ['Exito' => false , 'mensaje'=>'El correo esta incorrecto'];
            }
            if(strlen($password) < 6){
                return ['Exito' => false , 'mensaje'=>'La contraseña debe tener mas de 6 caracteres'];
            }
            $rolesPermitidos = ['cliente', 'empleado', 'admin'];
            if(!in_array($rol,$rolesPermitidos)){
                return ['Exito' => false , 'mensaje'=>'Rol no valido'];
            }
            if($pregunta === '' || $respuesta ===''){
                return ['Exito' => false , 'mensaje'=>'Debe completas las preguntas y respuesta de seguridad'];
            }

            if($this->buscarPorEmail($email)){
                return ['Exito' => false , 'mensaje'=>'El email ya esta registrado'];
            }

            $password_cifrado = password_hash($password , PASSWORD_DEFAULT);

            $respuesta_cifrado = password_hash(strtolower(trim($respuesta)), PASSWORD_DEFAULT);

            $sql = 'INSERT INTO usuarios (nombre , email , password , rol , telefono , direccion , pregunta_seguridad , respuesta_seguridad )
            VALUES(?,?,?,?,?,?,?,?)';
            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $nombre , $email , $password_cifrado ,$rol ,$telefono , $direccion , $pregunta , $respuesta_cifrado
            ]);

            return ['Exito'=> true , 'mensaje'=>'Registro de usuario exito'];


        }catch(PDOException $e){
            return ['Exito'=>false , 'mensaje'=>'Error al registrase'. $e->getMessage()];

        }
        
    }

    public function buscarPorEmail($email){
        try{
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch();

        }catch(PDOException $e){
            return false;
        }

    }
    public function obtenerTodos(){
        try{
            $sql = "SELECT id , nombre , email , rol , telefono , direccion , estado , fecha_registro
            FROM usuarios ORDER BY fecha_registro DESC ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();

        }catch(PDOException $e){
            return [];

        }
    }
    public function obtenerPorId($id){
        try{
            $sql = "SELECT id,nombre, email, rol, telefono, direccion, estado , pregunta_seguridad, fecha_registro 
            FROM usuarios WHERE id = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de obtner por id ". $e->getMessage()];
        }

    }
    public function actualizar($id, $nombre, $direccion ,$telefono){
        try{
            $sql = "UPDATE usuarios SET nombre = ? , direccion = ? , telefono = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nombre, $direccion ,$telefono, $id]);
            return ["Exito"=> true , "mensaje"=>"Actualizacion exitosa"];
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error a la actualizacion de datos" . $e->getMessage()];
        }
    }
    public function eliminarUsuario($id){
        try{
            $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return ["Exito"=> true , "mensaje"=>"Eliminacion de usuario exitosa"];

        }catch(PDOException $e){
        return ["Exito"=> false , "mensaje"=>"No se encontró el usuario" . $e->getMessage()];
        }
    }

    public function verificarPassword($passwordIngresada, $password_cifrado){
        return password_verify($passwordIngresada, $password_cifrado);
    }

    public function verificaRespuestaSeguridad($respuestaIngresada , $respuesta_cifrado){
        return password_verify(strtolower(trim($respuestaIngresada)) , $respuesta_cifrado);
    }

    public function cambiarRol($id , $nuevo_rol){
        try{
            $roles = ["cliente", "empleado","admin"];
            if(!in_array($nuevo_rol, $roles)){
                return ["Exito"=> false , "mensaje"=>"Rol no econtrado"];
            }
            $sql = "UPDATE usuarios SET rol = ? where id = ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nuevo_rol, $id]);
            return ["Exito"=> true , "mensaje"=>"Rol cambiado con exito"];
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Cambio de rol fallido" . $e->getMessage()];
        }
    }

    public function cambiarPassword($id ,$nuevaPassword){
        try{
            $password_cifrado = password_hash($nuevaPassword , PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET password = ? WHERE id = ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$password_cifrado, $id]);
            return ["Exito"=> true , "mensaje"=>"Cambio de contraseña exitoso"];
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de cambio de contraseña". $e->getMessage()];
        }
    }

    public function cambiarEstado($id , $estado){
        try{
            $estadoValido = ['activo', 'inactivo'];
            if(!in_array($estado , $estadoValido)){
                return ["Exito"=> false , "mensaje"=>"Estado no valido"];
            }
            $sql = "UPDATE usuarios SET estado = ? where id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$estado, $id]);
            return ["Exito"=> true , "mensaje"=>"Cambio de estado exitoso"];
        }catch(PDOException $e){
            return ["Exito"=> false , "mensaje"=>"Error de cambio de estado"];
        }
    }
}
?>