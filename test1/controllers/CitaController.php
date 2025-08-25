<?php
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/Infante.php';
require_once __DIR__ . '/../models/Vacuna.php';
require_once __DIR__ . '/../config/database.php';

class CitaController {
    private $cita;
    private $infante;
    private $vacuna;
    private $database;

    public function __construct() {
        $this->database = new Database();
        $this->cita = new Cita($this->database->getConnection());
        $this->infante = new Infante($this->database->getConnection());
        $this->vacuna = new Vacuna($this->database->getConnection());
    }

    public function index() {
        $stmt = $this->cita->read();
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $citas;
    }

    public function show($id) {
        $this->cita->id = $id;
        $cita = $this->cita->readOne();
        if($cita) {
            return $cita;
        }
        return false;
    }

    public function store($data) {
        $this->cita->infante_id = $data['infante_id'];
        $this->cita->fecha_inyeccion = $data['fecha_inyeccion'];
        $this->cita->vacuna_id = $data['vacuna_id'];

        if($this->cita->create()) {
            return ['success' => true, 'message' => 'Cita creada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al crear la cita'];
    }

    public function update($id, $data) {
        $this->cita->id = $id;
        $this->cita->infante_id = $data['infante_id'];
        $this->cita->fecha_inyeccion = $data['fecha_inyeccion'];
        $this->cita->vacuna_id = $data['vacuna_id'];

        if($this->cita->update()) {
            return ['success' => true, 'message' => 'Cita actualizada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al actualizar la cita'];
    }

    public function delete($id) {
        $this->cita->id = $id;
        if($this->cita->delete()) {
            return ['success' => true, 'message' => 'Cita eliminada exitosamente'];
        }
        return ['success' => false, 'message' => 'Error al eliminar la cita'];
    }

    public function getInfantes() {
        $stmt = $this->infante->read();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVacunas() {
        $stmt = $this->vacuna->read();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCitasByInfante($infante_id) {
        $stmt = $this->cita->getCitasByInfante($infante_id);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCitasByFecha($fecha) {
        $stmt = $this->cita->getCitasByFecha($fecha);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
