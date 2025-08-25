<?php
// Archivo de prueba para verificar la conexión a la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Prueba de Conexión a la Base de Datos</h2>";

try {
    require_once __DIR__ . '/config/database.php';
    
    echo "<p>✅ Archivo database.php cargado correctamente</p>";
    
    $database = new Database();
    echo "<p>✅ Clase Database instanciada correctamente</p>";
    
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "<p>✅ Conexión a la base de datos establecida correctamente</p>";
        
        // Verificar que las tablas existan
        $tables = ['responsable', 'infantes', 'vacuna', 'cita'];
        
        foreach ($tables as $table) {
            $stmt = $conn->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "<p>✅ Tabla '$table' existe</p>";
            } else {
                echo "<p>❌ Tabla '$table' NO existe</p>";
            }
        }
        
        // Verificar datos en las tablas
        foreach ($tables as $table) {
            $stmt = $conn->query("SELECT COUNT(*) as count FROM $table");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>📊 Tabla '$table': {$result['count']} registros</p>";
        }
        
    } else {
        echo "<p>❌ Error: No se pudo establecer la conexión</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Información del Sistema:</h3>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>PDO Drivers:</strong> " . implode(', ', PDO::getAvailableDrivers()) . "</p>";
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
?>
