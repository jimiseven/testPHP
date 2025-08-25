<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Infante.php';
require_once __DIR__ . '/../models/Responsable.php';

class InfanteController {
    private $db;
    private $infanteModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->infanteModel = new Infante($this->db);
    }

    // Obtiene todos los infantes para la vista principal
    public function index() {
        $stmt = $this->infanteModel->read();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un infante para el formulario de edición
    public function show($id) {
        $this->infanteModel->id = $id;
        $this->infanteModel->readOne();
        return [
            'id' => $this->infanteModel->id,
            'nombre' => $this->infanteModel->nombre,
            'apellido' => $this->infanteModel->apellido,
            'fecha_nacimiento' => $this->infanteModel->fecha_nacimiento,
            'responsable_id' => $this->infanteModel->responsable_id,
        ];
    }

    // Guarda un nuevo infante
    public function store($data) {
        if (empty($data['nombre']) || empty($data['apellido']) || empty($data['fecha_nacimiento']) || empty($data['responsable_id'])) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        $this->infanteModel->nombre = $data['nombre'];
        $this->infanteModel->apellido = $data['apellido'];
        $this->infanteModel->fecha_nacimiento = $data['fecha_nacimiento'];
        $this->infanteModel->responsable_id = $data['responsable_id'];

        if ($this->infanteModel->create()) {
            return ['success' => true, 'message' => 'Infante creado exitosamente.'];
        }
        return ['success' => false, 'message' => 'No se pudo crear el infante.'];
    }
    
    // Actualiza un infante existente
    public function update($id, $data) {
        if (empty($data['nombre']) || empty($data['apellido']) || empty($data['fecha_nacimiento']) || empty($data['responsable_id'])) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios.'];
        }

        $this->infanteModel->id = $id;
        $this->infanteModel->nombre = $data['nombre'];
        $this->infanteModel->apellido = $data['apellido'];
        $this->infanteModel->fecha_nacimiento = $data['fecha_nacimiento'];
        $this->infanteModel->responsable_id = $data['responsable_id'];

        if ($this->infanteModel->update()) {
            return ['success' => true, 'message' => 'Infante actualizado exitosamente.'];
        }
        return ['success' => false, 'message' => 'No se pudo actualizar el infante.'];
    }

    // Elimina un infante
    public function delete($id) {
        $this->infanteModel->id = $id;
        if ($this->infanteModel->delete()) {
            return ['success' => true, 'message' => 'Infante eliminado exitosamente.'];
        }
        return ['success' => false, 'message' => 'No se pudo eliminar el infante.'];
    }

    // --- Métodos auxiliares para los formularios ---

    // Obtiene todos los responsables para el dropdown
    public function getResponsables() {
        $responsableModel = new Responsable($this->db);
        $stmt = $responsableModel->readAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calcula la edad a partir de la fecha de nacimiento
    public function calcularEdad($fecha_nacimiento) {
        if (!$fecha_nacimiento) return '?';
        try {
            $hoy = new DateTime();
            $nacimiento = new DateTime($fecha_nacimiento);
            $edad = $hoy->diff($nacimiento);
            return $edad->y;
        } catch (Exception $e) {
            return '?';
        }
    }
}
?>
