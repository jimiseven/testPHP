<?php
/**
 * Script de Instalación - Sistema de Gestión de Vacunas
 * Este archivo verifica que el sistema esté correctamente configurado
 */

// Configuración de errores para instalación
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];
$warnings = [];
$success = [];

// Verificar versión de PHP
if (version_compare(PHP_VERSION, '7.4.0', '<')) {
    $errors[] = "PHP 7.4 o superior es requerido. Versión actual: " . PHP_VERSION;
} else {
    $success[] = "PHP " . PHP_VERSION . " ✓";
}

// Verificar extensiones requeridas
$required_extensions = ['pdo', 'pdo_mysql', 'json'];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Extensión PHP requerida no encontrada: $ext";
    } else {
        $success[] = "Extensión $ext ✓";
    }
}

// Verificar permisos de directorios
$directories = ['config', 'models', 'controllers', 'views'];
foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        $errors[] = "Directorio requerido no encontrado: $dir";
    } elseif (!is_readable($dir)) {
        $warnings[] = "Directorio no legible: $dir";
    } else {
        $success[] = "Directorio $dir ✓";
    }
}

// Verificar archivos requeridos
$required_files = [
    'config/database.php',
    'models/Responsable.php',
    'models/Infante.php',
    'models/Vacuna.php',
    'models/Cita.php',
    'controllers/ResponsableController.php',
    'controllers/InfanteController.php',
    'controllers/VacunaController.php',
    'controllers/CitaController.php',
    'views/layout/header.php',
    'views/layout/footer.php'
];

foreach ($required_files as $file) {
    if (!file_exists($file)) {
        $errors[] = "Archivo requerido no encontrado: $file";
    } elseif (!is_readable($file)) {
        $warnings[] = "Archivo no legible: $file";
    } else {
        $success[] = "Archivo $file ✓";
    }
}

// Verificar base de datos
$db_connection = false;
if (file_exists('config/database.php')) {
    try {
        require_once 'config/database.php';
        $database = new Database();
        $conn = $database->getConnection();
        if ($conn) {
            $db_connection = true;
            $success[] = "Conexión a base de datos ✓";
            
            // Verificar tablas
            $tables = ['responsable', 'infantes', 'vacuna', 'cita'];
            foreach ($tables as $table) {
                try {
                    $stmt = $conn->query("SHOW TABLES LIKE '$table'");
                    if ($stmt->rowCount() > 0) {
                        $success[] = "Tabla $table ✓";
                    } else {
                        $warnings[] = "Tabla $table no encontrada";
                    }
                } catch (Exception $e) {
                    $warnings[] = "Error al verificar tabla $table: " . $e->getMessage();
                }
            }
        }
    } catch (Exception $e) {
        $errors[] = "Error de conexión a base de datos: " . $e->getMessage();
    }
}

// Verificar permisos de escritura (para futuras funcionalidades)
$writable_dirs = ['config', 'dbs'];
foreach ($writable_dirs as $dir) {
    if (is_dir($dir) && !is_writable($dir)) {
        $warnings[] = "Directorio no escribible: $dir";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación - Sistema de Gestión de Vacunas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .install-card { max-width: 800px; }
        .status-item { margin-bottom: 0.5rem; }
        .status-success { color: #198754; }
        .status-warning { color: #fd7e14; }
        .status-error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card install-card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-gear"></i> 
                            Verificación de Instalación
                        </h3>
                        <p class="mb-0">Sistema de Gestión de Vacunas</p>
                    </div>
                    <div class="card-body">
                        <?php if (empty($errors)): ?>
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle"></i>
                                <strong>¡Excelente!</strong> El sistema está correctamente configurado.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Se encontraron errores</strong> que deben ser corregidos antes de continuar.
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($warnings)): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Advertencias:</strong> Algunos elementos pueden no funcionar correctamente.
                            </div>
                        <?php endif; ?>

                        <h5 class="mb-3">Estado de la Instalación:</h5>
                        
                        <!-- Errores -->
                        <?php if (!empty($errors)): ?>
                            <div class="mb-4">
                                <h6 class="text-danger">
                                    <i class="bi bi-x-circle"></i> Errores Críticos
                                </h6>
                                <ul class="list-unstyled">
                                    <?php foreach ($errors as $error): ?>
                                        <li class="status-item status-error">
                                            <i class="bi bi-x-circle"></i> <?php echo htmlspecialchars($error); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Advertencias -->
                        <?php if (!empty($warnings)): ?>
                            <div class="mb-4">
                                <h6 class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Advertencias
                                </h6>
                                <ul class="list-unstyled">
                                    <?php foreach ($warnings as $warning): ?>
                                        <li class="status-item status-warning">
                                            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($warning); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Éxitos -->
                        <?php if (!empty($success)): ?>
                            <div class="mb-4">
                                <h6 class="text-success">
                                    <i class="bi bi-check-circle"></i> Verificaciones Exitosas
                                </h6>
                                <ul class="list-unstyled">
                                    <?php foreach ($success as $item): ?>
                                        <li class="status-item status-success">
                                            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($item); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Instrucciones de Instalación -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="bi bi-info-circle"></i> Instrucciones de Instalación:</h6>
                                <ol>
                                    <li>Crear la base de datos <code>bd_vac</code> en MySQL</li>
                                    <li>Importar el archivo <code>dbs/bd_vac.sql</code></li>
                                    <li>Configurar credenciales en <code>config/database.php</code></li>
                                    <li>Verificar permisos de directorios</li>
                                    <li>Acceder al sistema desde <code>index.php</code></li>
                                </ol>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <?php if (empty($errors)): ?>
                                <a href="index.php" class="btn btn-success btn-lg">
                                    <i class="bi bi-arrow-right"></i> Ir al Sistema
                                </a>
                            <?php else: ?>
                                <button class="btn btn-primary btn-lg" onclick="location.reload()">
                                    <i class="bi bi-arrow-clockwise"></i> Verificar Nuevamente
                                </button>
                            <?php endif; ?>
                            
                            <a href="README.md" class="btn btn-outline-secondary btn-lg ms-2">
                                <i class="bi bi-book"></i> Ver Documentación
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
