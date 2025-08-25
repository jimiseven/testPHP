<?php
require_once __DIR__ . '/controllers/VacunaController.php';

$controller = new VacunaController();
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
                    header('Location: vacunas.php?success=1');
                    exit;
                } else {
                    $message = $result['message'];
                }
                break;
            case 'update':
                $result = $controller->update($_POST['id'], $_POST);
                if ($result['success']) {
                    $message = $result['message'];
                    header('Location: vacunas.php?success=1');
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
                        header('Location: vacunas.php?success=1');
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
        $page_title = 'Nueva Vacuna';
        break;
    case 'edit':
        $vacuna = $controller->show($_GET['id']);
        $page_title = 'Editar Vacuna';
        break;
    default:
        $vacunas = $controller->index();
        $page_title = 'Gestión de Vacunas';
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
    <!-- Lista de Vacunas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-droplet"></i> Lista de Vacunas
            </h6>
            <a href="?action=create" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nueva Vacuna
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($vacunas)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-droplet display-1 text-muted"></i>
                    <p class="text-muted mt-3">No hay vacunas registradas</p>
                    <a href="?action=create" class="btn btn-primary">Registrar la primera vacuna</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Empresa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vacunas as $vacuna): ?>
                                <tr>
                                    <td>
                                        <i class="bi bi-droplet-fill text-primary"></i>
                                        <strong><?php echo htmlspecialchars($vacuna['nombre']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo htmlspecialchars($vacuna['empresa']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="?action=edit&id=<?php echo $vacuna['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteVacuna(<?php echo $vacuna['id']; ?>)">
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
    <!-- Formulario de Vacuna -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="bi bi-droplet"></i> 
                <?php echo $action === 'create' ? 'Nueva Vacuna' : 'Editar Vacuna'; ?>
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="action" value="<?php echo $action; ?>">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $vacuna->id; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre de la Vacuna *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($vacuna->nombre) : ''; ?>" 
                               placeholder="Ej: Triple Viral, Hepatitis B, etc." required>
                        <div class="form-text">Ingrese el nombre completo de la vacuna</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="empresa" class="form-label">Empresa Fabricante *</label>
                        <input type="text" class="form-control" id="empresa" name="empresa" 
                               value="<?php echo $action === 'edit' ? htmlspecialchars($vacuna->empresa) : ''; ?>" 
                               placeholder="Ej: BioPharma, VaxCorp, etc." required>
                        <div class="form-text">Ingrese el nombre de la empresa fabricante</div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="vacunas.php" class="btn btn-secondary">
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
function deleteVacuna(id) {
    if (confirmDelete('¿Está seguro de que desea eliminar esta vacuna? Esta acción no se puede deshacer.')) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}

// Validación del formulario
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const empresa = document.getElementById('empresa').value.trim();
            
            if (nombre.length < 3) {
                e.preventDefault();
                alert('El nombre de la vacuna debe tener al menos 3 caracteres.');
                return false;
            }
            
            if (empresa.length < 2) {
                e.preventDefault();
                alert('El nombre de la empresa debe tener al menos 2 caracteres.');
                return false;
            }
        });
    }
});
</script>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
