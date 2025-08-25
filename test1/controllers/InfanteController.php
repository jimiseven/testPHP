<?php
require_once __DIR__ . '/../models/Infante.php';
require_once __DIR__ . '/../models/Responsable.php';
require_once __DIR__ . '/../config/database.php';

class InfanteController {
    private $infante;
    private $responsable;
    private $database;

    public function __construct() {
        $this->database = new Database();
        $this->infante = new Infante($this->database->getConnection());
        $this->responsable = new Responsable($this->database->getConnection());
    }

    public function index() {
        $stmt = $this->infante->read();
        $infantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $infantes;
    }

    public function show($id) {
        $this->infante->id = $id;
        $infante = $this->infante->readOne();
        if($infante) {
            return $infante;
        }
        return false;
    }

    public function store($data) {
        $this->infante->nombre = $data['nombre'];
        $this->infante->apellido = $data['apellido'];
        $this->infante->fecha_nacimiento = $data['fecha_nacimiento'];
        $this->infante->responsable_id = $data['responsable_id'];

        if($this->infante->create()) {
            return ['success' => true, 'message' => 'Infante creado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al crear el infante'];
    }

    public function update($id, $data) {
        $this->infante->id = $id;
        $this->infante->nombre = $data['nombre'];
        $this->infante->apellido = $data['apellido'];
        $this->infante->fecha_nacimiento = $data['fecha_nacimiento'];
        $this->infante->responsable_id = $data['responsable_id'];

        if($this->infante->update()) {
            return ['success' => true, 'message' => 'Infante actualizado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar el infante'];
    }

    public function delete($id) {
        $this->infante->id = $id;
        if($this->infante->delete()) {
            return ['success' => true, 'message' => 'Infante eliminado exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar el infante'];
    }

    public function getResponsables() {
        $stmt = $this->responsable->read();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularEdad($fecha_nacimiento) {
        $fecha_nac = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nac);
        return $edad->y;
    }
}
?>
