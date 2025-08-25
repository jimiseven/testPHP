<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Cita.php'; // Incluir el nuevo modelo
require_once __DIR__ . '/../models/Infante.php';
require_once __DIR__ . '/../models/Vacuna.php';

class CitaController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtiene todas las citas con información relacionada para la vista principal
    public function index() {
        $citaModel = new Cita($this->db);
        $stmt = $citaModel->read();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene una cita específica para la edición
    public function show($id) {
        $citaModel = new Cita($this->db);
        $citaModel->id = $id;
        $citaModel->readOne(); // El método readOne carga los datos en las propiedades del objeto
        // Devolvemos un array para mantener la compatibilidad con la vista
        return ['id' => $citaModel->id, 'infante_id' => $citaModel->infante_id, 'vacuna_id' => $citaModel->vacuna_id, 'fecha_inyeccion' => $citaModel->fecha_inyeccion];
    }

    // Guarda una nueva cita
    public function store($data) {
        // Validación del lado del servidor
        if (empty($data['infante_id']) || empty($data['vacuna_id']) || empty($data['fecha_inyeccion'])) {
            return ['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios.'];
        }

        try {
            $citaModel = new Cita($this->db);

            // Asignar valores al objeto del modelo
            $citaModel->infante_id = $data['infante_id'];
            $citaModel->vacuna_id = $data['vacuna_id'];
            $citaModel->fecha_inyeccion = $data['fecha_inyeccion'];

            if ($citaModel->create()) {
                return ['success' => true, 'message' => 'Cita creada exitosamente.'];
            } else {
                return ['success' => false, 'message' => 'No se pudo crear la cita.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    // Actualiza una cita existente
    public function update($id, $data) {
        // Validación del lado del servidor
        if (empty($data['infante_id']) || empty($data['vacuna_id']) || empty($data['fecha_inyeccion'])) {
            return ['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios.'];
        }

        try {
            $citaModel = new Cita($this->db);

            $citaModel->id = $id;
            $citaModel->infante_id = $data['infante_id'];
            $citaModel->vacuna_id = $data['vacuna_id'];
            $citaModel->fecha_inyeccion = $data['fecha_inyeccion'];

            if ($citaModel->update()) {
                return ['success' => true, 'message' => 'Cita actualizada exitosamente.'];
            } else {
                return ['success' => false, 'message' => 'No se pudo actualizar la cita.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    // Elimina una cita
    public function delete($id) {
        try {
            $citaModel = new Cita($this->db);
            $citaModel->id = $id;

            if ($citaModel->delete()) {
                return ['success' => true, 'message' => 'Cita eliminada exitosamente.'];
            } else {
                return ['success' => false, 'message' => 'No se pudo eliminar la cita.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    // --- Métodos auxiliares para los formularios ---

    // Obtiene todos los infantes para el dropdown
    public function getInfantes() {
        $infanteModel = new Infante($this->db);
        $stmt = $infanteModel->readAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene todas las vacunas para el dropdown
    public function getVacunas() {
        $vacunaModel = new Vacuna($this->db);
        $stmt = $vacunaModel->readAll();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calcula la edad a partir de la fecha de nacimiento
    public function calcularEdad($fechaNacimiento) {
        if (!$fechaNacimiento) return '?';
        try {
            $hoy = new DateTime();
            $nacimiento = new DateTime($fechaNacimiento);
            $edad = $hoy->diff($nacimiento);
            return $edad->y;
        } catch (Exception $e) {
            return '?';
        }
    }
}
?>
