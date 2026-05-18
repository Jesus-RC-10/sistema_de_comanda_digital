<?php
// controllers/AuthController.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userModel = new User();
            $usuario = $userModel->login($_POST['username'], $_POST['password']);
            
            if ($usuario) {
                session_regenerate_id(true); // Limpia datos de sesión anterior y previene ataques de fijación
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                // Redirección basada en el rol (limpiamos el string por seguridad)
                $rol = strtolower(trim($usuario['rol']));
                
                if ($rol == 'admin') {
                    header("Location: index.php?action=admin&seccion=dashboard");
                } else if ($rol == 'mesero') {
                    header("Location: " . BASE_URL . "index.php?url=mesero");
                } else if ($rol == 'caja') {
                    header("Location: " . BASE_URL . "index.php?url=caja");
                } else if ($rol == 'cocina') {
                    header("Location: " . BASE_URL . "index.php?url=cocina");
                } else {
                    // Fallback para roles desconocidos
                    header("Location: index.php?action=admin&seccion=dashboard");
                }
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos";
                require __DIR__ . '/../views/auth/login.php';
            }
        } else {
            require __DIR__ . '/../views/auth/login.php';
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit();
    }
}
?>