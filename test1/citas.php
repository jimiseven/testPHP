<?php
require_once __DIR__ . '/controllers/CitaController.php';

$controller = new CitaController();
$action = $_GET['action'] ?? 'index';
$message = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $result = $controller->store($_POST);
                if ($result['success']) {
                    $message = $result['message'];
                    header('Location: citas.php?success=1');
                    exit;
                } else {
                    $message = $result['message'];
                }
                break;
            case 'update':
                $result = $controller->update($_POST['id'], $_POST);
                if ($result['success']) {
                    $message = $result['message'];
                    header('Location: citas.php?success=1');
                    exit;
                } else {
                    $message = $result['message'];
                }
                break;
            case 'delete':
                if (isset($_POST['id'])) {
                    $result = $controller->delete($_POST['id']);
                    if ($result['success']) {
                        $message = $result['message'];
                        header('Location: citas.php?success=1');
                        exit;
                    } else {
                        $message = $result['message'];
                    }
                }
                break;
        }
    }
}

// Obtener datos según la acción
switch ($action) {
    case 'create':
        $infantes = $controller->getInfantes();
        $vacunas = $controller->getVacunas();
        $page_title = 'Nueva Cita';
        break;
    case 'edit':
        $cita = $controller->show($_GET['id']);
        $infantes = $controller->getInfantes();
        $vacunas = $controller->getVacunas();
        $page_title = 'Editar Cita';
        break;
    default:
        $citas = $controller->index();
        $page_title = 'Gestión de Citas';
        break;
}

include __DIR__ . '/views/layout/header.php';

// Mostrar mensaje de éxito
if (isset($_GET['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> Operación realizada exitosamente
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
}
?>

<?php if ($action === 'index'): ?>
    <!-- Lista de Citas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-calendar-check"></i> Lista de Citas
            </h6>
            <a href="?action=create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nueva Cita
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($citas)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-event display-1 text-muted"></i>
                    <p class="text-muted mt-3">No hay citas registradas</p>
                    <a href="?action=create" class="btn btn-primary">Registrar la primera cita</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Infante</th>
                                <th>Vacuna</th>
                                <th>Fecha de Inyección</th>
                                <th>Responsable</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($citas as $cita): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-baby text-success"></i>
                                        <strong><?php echo htmlspecialchars($cita['infante_nombre'] . ' ' . $cita['infante_apellido']); ?></strong>
                                    </td>
                                    <td>
                                        <i class="bi bi-droplet-fill text-primary"></i>
                                        <?php echo htmlspecialchars($cita['vacuna_nombre']); ?>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($cita['vacuna_empresa']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo date('d/m/Y', strtotime($cita['fecha_inyeccion'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <i class="bi bi-person text-warning"></i>
                                        <?php echo htmlspecialchars($cita['responsable_nombre'] . ' ' . $cita['responsable_apellido']); ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?action=edit&id=<?php echo $cita['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteCita(<?php echo $cita['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ($action === 'create' || $action === 'edit'): ?>
    <!-- Formulario de Cita -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-calendar-plus"></i> 
                <?php echo $action === 'create' ? 'Nueva Cita' : 'Editar Cita'; ?>
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $cita['id']; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="infante_id" class="form-label">Infante *</label>
                        <select class="form-select" id="infante_id" name="infante_id" required>
                            <option value="">Seleccione un infante</option>
                            <?php foreach ($infantes as $infante): ?>
                                <option value="<?php echo $infante['id']; ?>" 
                                        <?php echo ($action === 'edit' && $cita['infante_id'] == $infante['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($infante['nombre'] . ' ' . $infante['apellido']); ?>
                                    (<?php echo $controller->calcularEdad($infante['fecha_nacimiento']); ?> años)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Seleccione el infante que recibirá la vacuna</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="vacuna_id" class="form-label">Vacuna *</label>
                        <select class="form-select" id="vacuna_id" name="vacuna_id" required>
                            <option value="">Seleccione una vacuna</option>
                            <?php foreach ($vacunas as $vacuna): ?>
                                <option value="<?php echo $vacuna['id']; ?>" 
                                        <?php echo ($action === 'edit' && $cita['vacuna_id'] == $vacuna['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vacuna['nombre']); ?>
                                    - <?php echo htmlspecialchars($vacuna['empresa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Seleccione la vacuna a aplicar</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inyeccion" class="form-label">Fecha de Inyección *</label>
                        <input type="date" class="form-control" id="fecha_inyeccion" name="fecha_inyeccion" 
                               value="<?php echo $action === 'edit' ? $cita['fecha_inyeccion'] : date('Y-m-d'); ?>" 
                               required>
                        <div class="form-text">Fecha en que se aplicará la vacuna</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Información:</strong><br>
                            <small>
                                • La fecha debe ser igual o posterior a hoy<br>
                                • Se registrará la aplicación de la vacuna
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="citas.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check"></i> 
                        <?php echo $action === 'create' ? 'Crear Cita' : 'Actualizar Cita'; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Formulario oculto para eliminación -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

<script>
function deleteCita(id) {
    if (confirmDelete('¿Está seguro de que desea eliminar esta cita? Esta acción no se puede deshacer.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fechaInyeccion = new Date(document.getElementById('fecha_inyeccion').value);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);
            
            if (fechaInyeccion < hoy) {
                e.preventDefault();
                alert('La fecha de inyección no puede ser anterior a hoy.');
                return false;
            }
            
            const infanteId = document.getElementById('infante_id').value;
            const vacunaId = document.getElementById('vacuna_id').value;
            
            if (!infanteId || !vacunaId) {
                e.preventDefault();
                alert('Debe seleccionar tanto el infante como la vacuna.');
                return false;
            }
        });
    }
    
    // Establecer fecha mínima como hoy
    const fechaInput = document.getElementById('fecha_inyeccion');
    if (fechaInput) {
        const hoy = new Date().toISOString().split('T')[0];
        fechaInput.setAttribute('min', hoy);
    }
});
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
