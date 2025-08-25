<?php
require_once __DIR__ . '/../models/Responsable.php';
require_once __DIR__ . '/../config/database.php';

class ResponsableController {
    private $responsable;
    private $database;

    public function __construct() {
        $this->database = new Database();
        $this->responsable = new Responsable($this->database->getConnection());
    }

    public function index() {
        $stmt = $this->responsable->read();
        $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $responsables;
    }

    public function show($id) {
        $this->responsable->id = $id;
        if($this->responsable->readOne()) {
            return $this->responsable;
        }
        return false;
    }

    public function store($data) {
        $this->responsable->nombre = $data['nombre'];
        $this->responsable->apellido = $data['apellido'];
        $this->responsable->carnet = $data['carnet'];
        $this->responsable->celular = $data['celular'];

        if($this->responsable->create()) {
            return ['success' => true, 'message' => 'Responsable creado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al crear el responsable'];
    }

    public function update($id, $data) {
        $this->responsable->id = $id;
        $this->responsable->nombre = $data['nombre'];
        $this->responsable->apellido = $data['apellido'];
        $this->responsable->carnet = $data['carnet'];
        $this->responsable->celular = $data['celular'];

        if($this->responsable->update()) {
            return ['success' => true, 'message' => 'Responsable actualizado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar el responsable'];
    }

    public function delete($id) {
        $this->responsable->id = $id;
        if($this->responsable->delete()) {
            return ['success' => true, 'message' => 'Responsable eliminado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar el responsable'];
    }
}
?>
