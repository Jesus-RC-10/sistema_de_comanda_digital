<?php
require_once __DIR__ . '/../models/MesaModel.php';
require_once __DIR__ . '/../models/User.php';

class MesaController {
    public function index() {
        $mesaModel = new MesaModel();
        $mesas = $mesaModel->obtenerMesasActivas();

        $userModel = new User();
        $meseros = $userModel->obtenerMeseros();

        // Obtener el mesero asignado a cada mesa (desde el modelo Mesa que sí tiene LEFT JOIN en getAll())
        require_once __DIR__ . '/../models/Mesa.php';
        $mesaObj = new Mesa();
        $mesasDetalle = $mesaObj->getAll(); // Esto nos da mesas con el nombre_mesero
        
        // Mapear el nombre del mesero y mesero_id a $mesas
        foreach ($mesas as &$m) {
            $m['mesero_id'] = null;
            $m['nombre_mesero'] = null;
            foreach ($mesasDetalle as $md) {
                if ($m['id'] == $md['id']) {
                    $m['mesero_id'] = $md['mesero_id'];
                    $m['nombre_mesero'] = $md['nombre_mesero'];
                    break;
                }
            }
        }

        // Pasar datos a la vista
        $data = [
            'mesas' => $mesas,
            'meseros' => $meseros,
            'assets_url' => ASSETS_URL
        ];

        require_once __DIR__ . '/../views/mesas/mesas.php';
    }

    public function asignarMesero() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mesa_id = isset($_POST['mesa_id']) ? intval($_POST['mesa_id']) : 0;
            $mesero_id = isset($_POST['mesero_id']) ? intval($_POST['mesero_id']) : 0;

            if ($mesa_id > 0 && $mesero_id > 0) {
                $db = Database::getConnection();
                $sql = "UPDATE mesas SET mesero_id = ? WHERE id = ?";
                $stmt = $db->prepare($sql);
                $success = $stmt->execute([$mesero_id, $mesa_id]);
                
                header('Content-Type: application/json');
                echo json_encode(['success' => $success]);
                exit();
            }
        }
        
        header('Content-Type: application/json', true, 400);
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit();
    }
}
?>
