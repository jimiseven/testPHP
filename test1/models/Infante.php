<?php
class Infante {
    private $conn;
    private $table_name = "infantes";

    // Propiedades del Objeto
    public $id;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $responsable_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Alias para readAllWithDetails() - para mantener consistencia con el controlador
    public function read() {
        return $this->readAllWithDetails();
    }

    // Leer todos los infantes para dropdowns (simple)
    public function readAll() {
        $query = "SELECT id, nombre, apellido FROM " . $this->table_name . " ORDER BY apellido, nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Leer todos los infantes con detalles del responsable (para la vista principal)
    public function readAllWithDetails() {
        $query = "SELECT
                    i.id, i.nombre, i.apellido, i.fecha_nacimiento,
                    r.nombre as responsable_nombre, r.apellido as responsable_apellido
                  FROM " . $this->table_name . " i
                  LEFT JOIN responsable r ON i.responsable_id = r.id
                  ORDER BY i.apellido, i.nombre";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Crear Infante
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, apellido=:apellido, fecha_nacimiento=:fecha_nacimiento, responsable_id=:responsable_id";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->responsable_id = htmlspecialchars(strip_tags($this->responsable_id));

        // Vincular datos
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":responsable_id", $this->responsable_id);

        return $stmt->execute();
    }

    // Leer un solo infante
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->fecha_nacimiento = $row['fecha_nacimiento'];
            $this->responsable_id = $row['responsable_id'];
        }
    }

    // Actualizar Infante
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, apellido = :apellido, fecha_nacimiento = :fecha_nacimiento, responsable_id = :responsable_id
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->responsable_id = htmlspecialchars(strip_tags($this->responsable_id));

        // Vincular datos
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
        $stmt->bindParam(':responsable_id', $this->responsable_id);

        return $stmt->execute();
    }

    // Eliminar Infante
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        return $stmt->execute();
    }
}
?>
