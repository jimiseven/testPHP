<?php
require_once __DIR__ . '/../config/Database.php';

class CitaController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Obtiene todas las citas con información relacionada para la vista principal
    public function index() {
        $sql = "SELECT 
                    c.id, c.fecha_inyeccion,
                    i.nombre as infante_nombre, i.apellido as infante_apellido,
                    v.nombre as vacuna_nombre, v.empresa as vacuna_empresa,
                    r.nombre as responsable_nombre, r.apellido as responsable_apellido
                FROM cita c
                JOIN infantes i ON c.infante_id = i.id
                JOIN vacuna v ON c.vacuna_id = v.id
                JOIN responsable r ON i.responsable_id = r.id
                ORDER BY c.fecha_inyeccion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtiene una cita específica para la edición
    public function show($id) {
        $stmt = $this->db->prepare("SELECT * FROM cita WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Guarda una nueva cita
    public function store($data) {
        // Validación del lado del servidor
        if (empty($data['infante_id']) || empty($data['vacuna_id']) || empty($data['fecha_inyeccion'])) {
            return ['success' => false, 'message' => 'Todos los campos marcados con * son obligatorios.'];
        }

        try {
            $sql = "INSERT INTO cita (infante_id, vacuna_id, fecha_inyeccion) VALUES (:infante_id, :vacuna_id, :fecha_inyeccion)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':infante_id', $data['infante_id']);
            $stmt->bindParam(':vacuna_id', $data['vacuna_id']);
            $stmt->bindParam(':fecha_inyeccion', $data['fecha_inyeccion']);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Cita creada exitosamente.'];
            } else {
                return ['success' => false, 'message' => 'Error al crear la cita.'];
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
            $sql = "UPDATE cita SET infante_id = :infante_id, vacuna_id = :vacuna_id, fecha_inyeccion = :fecha_inyeccion WHERE id = :id";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':infante_id', $data['infante_id']);
            $stmt->bindParam(':vacuna_id', $data['vacuna_id']);
            $stmt->bindParam(':fecha_inyeccion', $data['fecha_inyeccion']);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Cita actualizada exitosamente.'];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar la cita.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    // Elimina una cita
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM cita WHERE id = :id");
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Cita eliminada exitosamente.'];
            } else {
                return ['success' => false, 'message' => 'Error al eliminar la cita.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    // --- Métodos auxiliares para los formularios ---

    // Obtiene todos los infantes para el dropdown
    public function getInfantes() {
        $stmt = $this->db->prepare("SELECT id, nombre, apellido, fecha_nacimiento FROM infantes ORDER BY apellido, nombre");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtiene todas las vacunas para el dropdown
    public function getVacunas() {
        $stmt = $this->db->prepare("SELECT id, nombre, empresa FROM vacuna ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll();
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
