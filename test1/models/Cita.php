<?php
require_once __DIR__ . '/../config/database.php';

class Cita {
    private $conn;
    private $table_name = "cita";

    public $id;
    public $infante_id;
    public $fecha_inyeccion;
    public $vacuna_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las citas con información completa
    public function read() {
        $query = "SELECT c.*, i.nombre as infante_nombre, i.apellido as infante_apellido, 
                         v.nombre as vacuna_nombre, v.empresa as vacuna_empresa,
                         r.nombre as responsable_nombre, r.apellido as responsable_apellido
                  FROM " . $this->table_name . " c
                  LEFT JOIN infantes i ON c.infante_id = i.id
                  LEFT JOIN vacuna v ON c.vacuna_id = v.id
                  LEFT JOIN responsable r ON i.responsable_id = r.id
                  ORDER BY c.fecha_inyeccion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Alias para read() - para mantener consistencia
    public function readAll() {
        return $this->read();
    }

    // Obtener una cita por ID
    public function readOne() {
        $query = "SELECT c.*, i.nombre as infante_nombre, i.apellido as infante_apellido, 
                         v.nombre as vacuna_nombre, v.empresa as vacuna_empresa,
                         r.nombre as responsable_nombre, r.apellido as responsable_apellido
                  FROM " . $this->table_name . " c
                  LEFT JOIN infantes i ON c.infante_id = i.id
                  LEFT JOIN vacuna v ON c.vacuna_id = v.id
                  LEFT JOIN responsable r ON i.responsable_id = r.id
                  WHERE c.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->infante_id = $row['infante_id'];
            $this->fecha_inyeccion = $row['fecha_inyeccion'];
            $this->vacuna_id = $row['vacuna_id'];
            return $row;
        }
        return false;
    }

    // Crear nueva cita
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET infante_id=:infante_id, fecha_inyeccion=:fecha_inyeccion, vacuna_id=:vacuna_id";
        $stmt = $this->conn->prepare($query);

        $this->infante_id = htmlspecialchars(strip_tags($this->infante_id));
        $this->fecha_inyeccion = htmlspecialchars(strip_tags($this->fecha_inyeccion));
        $this->vacuna_id = htmlspecialchars(strip_tags($this->vacuna_id));

        $stmt->bindParam(":infante_id", $this->infante_id);
        $stmt->bindParam(":fecha_inyeccion", $this->fecha_inyeccion);
        $stmt->bindParam(":vacuna_id", $this->vacuna_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Actualizar cita
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET infante_id=:infante_id, fecha_inyeccion=:fecha_inyeccion, vacuna_id=:vacuna_id WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->infante_id = htmlspecialchars(strip_tags($this->infante_id));
        $this->fecha_inyeccion = htmlspecialchars(strip_tags($this->fecha_inyeccion));
        $this->vacuna_id = htmlspecialchars(strip_tags($this->vacuna_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":infante_id", $this->infante_id);
        $stmt->bindParam(":fecha_inyeccion", $this->fecha_inyeccion);
        $stmt->bindParam(":vacuna_id", $this->vacuna_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar cita
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener citas por infante
    public function getCitasByInfante($infante_id) {
        $query = "SELECT c.*, v.nombre as vacuna_nombre, v.empresa as vacuna_empresa
                  FROM " . $this->table_name . " c
                  LEFT JOIN vacuna v ON c.vacuna_id = v.id
                  WHERE c.infante_id = ?
                  ORDER BY c.fecha_inyeccion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $infante_id);
        $stmt->execute();
        return $stmt;
    }

    // Obtener citas por fecha
    public function getCitasByFecha($fecha) {
        $query = "SELECT c.*, i.nombre as infante_nombre, i.apellido as infante_apellido, 
                         v.nombre as vacuna_nombre, v.empresa as vacuna_empresa,
                         r.nombre as responsable_nombre, r.apellido as responsable_apellido
                  FROM " . $this->table_name . " c
                  LEFT JOIN infantes i ON c.infante_id = i.id
                  LEFT JOIN vacuna v ON c.vacuna_id = v.id
                  LEFT JOIN responsable r ON i.responsable_id = r.id
                  WHERE DATE(c.fecha_inyeccion) = ?
                  ORDER BY c.fecha_inyeccion";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $fecha);
        $stmt->execute();
        return $stmt;
    }
}
?>
