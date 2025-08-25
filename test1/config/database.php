<?php
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'bd_vac';
    private $username = 'root'; // Usuario por defecto de XAMPP
    private $password = '';     // Contraseña por defecto de XAMPP
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4';
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // En un entorno de producción, no muestres errores detallados al usuario.
            // Regístralos en un archivo de log.
            die('Error de Conexión: ' . $e->getMessage());
        }
        return $this->conn;
    }
}
?>
