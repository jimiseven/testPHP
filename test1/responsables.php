<?php
require_once __DIR__ . '/controllers/ResponsableController.php';

$controller = new ResponsableController();
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
                    header('Location: responsables.php?success=1');
                    exit;
                } else {
                    $message = $result['message'];
                }
                break;
            case 'update':
                $result = $controller->update($_POST['id'], $_POST);
                if ($result['success']) {
                    $message = $result['message'];
                    header('Location: responsables.php?success=1');
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
                        header('Location: responsables.php?success=1');
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
        $page_title = 'Nuevo Responsable';
        break;
    case 'edit':
        $responsable = $controller->show($_GET['id']);
        $page_title = 'Editar Responsable';
        break;
    default:
        $responsables = $controller->index();
        $page_title = 'Gestión de Responsables';
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
    <!-- Lista de Responsables -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-people"></i> Lista de Responsables
            </h6>
            <a href="?action=create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nuevo Responsable
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($responsables)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-people display-1 text-muted"></i>
                    <p class="text-muted mt-3">No hay responsables registrados</p>
                    <a href="?action=create" class="btn btn-primary">Registrar el primer responsable</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Carnet</th>
                                <th>Celular</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($responsables as $responsable): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($responsable['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($responsable['apellido']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($responsable['carnet']); ?></span></td>
                                    <td><?php echo htmlspecialchars($responsable['celular']); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?action=edit&id=<?php echo $responsable['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteResponsable(<?php echo $responsable['id']; ?>)">
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
    <!-- Formulario de Responsable -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-person-plus"></i> 
                <?php echo $action === 'create' ? 'Nuevo Responsable' : 'Editar Responsable'; ?>
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $responsable->id; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($responsable->nombre) : ''; ?>" 
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellido" class="form-label">Apellido *</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($responsable->apellido) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="carnet" class="form-label">Carnet de Identidad *</label>
                        <input type="text" class="form-control" id="carnet" name="carnet" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($responsable->carnet) : ''; ?>" 
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="celular" class="form-label">Celular *</label>
                        <input type="tel" class="form-control" id="celular" name="celular" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($responsable->celular) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="responsables.php" class="btn btn-secondary">
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
function deleteResponsable(id) {
    if (confirmDelete('¿Está seguro de que desea eliminar este responsable?')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
