<?php
require_once __DIR__ . '/../config/database.php';

class Responsable {
    private $conn;
    private $table_name = "responsable";

    public $id;
    public $nombre;
    public $apellido;
    public $carnet;
    public $celular;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los responsables
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY apellido, nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un responsable por ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->carnet = $row['carnet'];
            $this->celular = $row['celular'];
            return true;
        }
        return false;
    }

    // Crear nuevo responsable
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, apellido=:apellido, carnet=:carnet, celular=:celular";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->carnet = htmlspecialchars(strip_tags($this->carnet));
        $this->celular = htmlspecialchars(strip_tags($this->celular));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":carnet", $this->carnet);
        $stmt->bindParam(":celular", $this->celular);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Actualizar responsable
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, apellido=:apellido, carnet=:carnet, celular=:celular WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->carnet = htmlspecialchars(strip_tags($this->carnet));
        $this->celular = htmlspecialchars(strip_tags($this->celular));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":carnet", $this->carnet);
        $stmt->bindParam(":celular", $this->celular);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar responsable
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
}
?>
