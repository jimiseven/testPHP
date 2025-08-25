<?php
require_once __DIR__ . '/../models/Vacuna.php';
require_once __DIR__ . '/../config/database.php';

class VacunaController {
    private $vacuna;
    private $database;

    public function __construct() {
        $this->database = new Database();
        $this->vacuna = new Vacuna($this->database->getConnection());
    }

    public function index() {
        $stmt = $this->vacuna->read();
        $vacunas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $vacunas;
    }

    public function show($id) {
        $this->vacuna->id = $id;
        if($this->vacuna->readOne()) {
            return $this->vacuna;
        }
        return false;
    }

    public function store($data) {
        $this->vacuna->nombre = $data['nombre'];
        $this->vacuna->empresa = $data['empresa'];

        if($this->vacuna->create()) {
            return ['success' => true, 'message' => 'Vacuna creada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al crear la vacuna'];
    }

    public function update($id, $data) {
        $this->vacuna->id = $id;
        $this->vacuna->nombre = $data['nombre'];
        $this->vacuna->empresa = $data['empresa'];

        if($this->vacuna->update()) {
            return ['success' => true, 'message' => 'Vacuna actualizada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar la vacuna'];
    }

    public function delete($id) {
        $this->vacuna->id = $id;
        if($this->vacuna->delete()) {
            return ['success' => true, 'message' => 'Vacuna eliminada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar la vacuna'];
    }
}
?>
