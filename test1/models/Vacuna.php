<?php
class Vacuna {
    private $conn;
    private $table_name = "vacuna";

    // Propiedades del Objeto
    public $id;
    public $nombre;
    public $empresa;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las vacunas
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener todas las vacunas para los menús desplegables
    public function readAll() {
        $query = "SELECT id, nombre, empresa FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una vacuna por ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->empresa = $row['empresa'];
            return true;
        }
        return false;
    }

    // Crear nueva vacuna
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, empresa=:empresa";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->empresa = htmlspecialchars(strip_tags($this->empresa));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":empresa", $this->empresa);

        return $stmt->execute();
    }

    // Actualizar vacuna
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, empresa=:empresa WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->empresa = htmlspecialchars(strip_tags($this->empresa));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":empresa", $this->empresa);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Eliminar vacuna
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>
