<?php
require_once __DIR__ . '/../config/database.php';

class Infante {
    private $conn;
    private $table_name = "infantes";

    public $id;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $responsable_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los infantes con información del responsable
    public function read() {
        $query = "SELECT i.*, r.nombre as responsable_nombre, r.apellido as responsable_apellido 
                  FROM " . $this->table_name . " i 
                  LEFT JOIN responsable r ON i.responsable_id = r.id 
                  ORDER BY i.apellido, i.nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un infante por ID
    public function readOne() {
        $query = "SELECT i.*, r.nombre as responsable_nombre, r.apellido as responsable_apellido 
                  FROM " . $this->table_name . " i 
                  LEFT JOIN responsable r ON i.responsable_id = r.id 
                  WHERE i.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->fecha_nacimiento = $row['fecha_nacimiento'];
            $this->responsable_id = $row['responsable_id'];
            return $row;
        }
        return false;
    }

    // Crear nuevo infante
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, apellido=:apellido, fecha_nacimiento=:fecha_nacimiento, responsable_id=:responsable_id";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->responsable_id = htmlspecialchars(strip_tags($this->responsable_id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":responsable_id", $this->responsable_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Actualizar infante
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, apellido=:apellido, fecha_nacimiento=:fecha_nacimiento, responsable_id=:responsable_id WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->responsable_id = htmlspecialchars(strip_tags($this->responsable_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":responsable_id", $this->responsable_id);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar infante
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

    // Calcular edad del infante
    public function calcularEdad() {
        $fecha_nac = new DateTime($this->fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nac);
        return $edad->y;
    }
}
?>
