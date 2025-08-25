<?php
require_once __DIR__ . '/controllers/InfanteController.php';

$controller = new InfanteController();
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
                    header('Location: infantes.php?success=1');
                    exit;
                } else {
                    $message = $result['message'];
                }
                break;
            case 'update':
                $result = $controller->update($_POST['id'], $_POST);
                if ($result['success']) {
                    $message = $result['message'];
                    header('Location: infantes.php?success=1');
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
                        header('Location: infantes.php?success=1');
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
        $responsables = $controller->getResponsables();
        $page_title = 'Nuevo Infante';
        break;
    case 'edit':
        $infante = $controller->show($_GET['id']);
        $responsables = $controller->getResponsables();
        $page_title = 'Editar Infante';
        break;
    default:
        $infantes = $controller->index();
        $page_title = 'Gestión de Infantes';
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
    <!-- Lista de Infantes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-baby"></i> Lista de Infantes
            </h6>
            <a href="?action=create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nuevo Infante
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($infantes)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-baby display-1 text-muted"></i>
                    <p class="text-muted mt-3">No hay infantes registrados</p>
                    <a href="?action=create" class="btn btn-primary">Registrar el primer infante</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fecha Nacimiento</th>
                                <th>Edad</th>
                                <th>Responsable</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($infantes as $infante): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($infante['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($infante['apellido']); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo date('d/m/Y', strtotime($infante['fecha_nacimiento'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $edad = $controller->calcularEdad($infante['fecha_nacimiento']);
                                        echo '<span class="badge bg-success">' . $edad . ' años</span>';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($infante['responsable_nombre'] . ' ' . $infante['responsable_apellido']); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?action=edit&id=<?php echo $infante['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteInfante(<?php echo $infante['id']; ?>)">
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
    <!-- Formulario de Infante -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-baby"></i> 
                <?php echo $action === 'create' ? 'Nuevo Infante' : 'Editar Infante'; ?>
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $infante['id']; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($infante['nombre']) : ''; ?>" 
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellido *</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($infante['apellido']) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento *</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                               value="<?php echo $action === 'edit' ? $infante['fecha_nacimiento'] : ''; ?>" 
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="responsable_id" class="form-label">Responsable *</label>
                        <select class="form-select" id="responsable_id" name="responsable_id" required>
                            <option value="">Seleccione un responsable</option>
                            <?php foreach ($responsables as $responsable): ?>
                                <option value="<?php echo $responsable['id']; ?>" 
                                        <?php echo ($action === 'edit' && $infante['responsable_id'] == $responsable['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($responsable['nombre'] . ' ' . $responsable['apellido']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="infantes.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check"></i> 
                        <?php echo $action === 'create' ? 'Crear' : 'Actualizar'; ?>
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
function deleteInfante(id) {
    if (confirmDelete('¿Está seguro de que desea eliminar este infante?')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

// Validar fecha de nacimiento
document.getElementById('fecha_nacimiento').addEventListener('change', function() {
    const fechaNac = new Date(this.value);
    const hoy = new Date();
    const edad = hoy.getFullYear() - fechaNac.getFullYear();
    
    if (edad > 18) {
        alert('La fecha de nacimiento indica que el infante tiene más de 18 años. Por favor, verifique la información.');
    }
});
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
