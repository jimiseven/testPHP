<?php
require_once __DIR__ . '/controllers/ResponsableController.php';
require_once __DIR__ . '/controllers/InfanteController.php';
require_once __DIR__ . '/controllers/VacunaController.php';
require_once __DIR__ . '/controllers/CitaController.php';

$responsableController = new ResponsableController();
$infanteController = new InfanteController();
$vacunaController = new VacunaController();
$citaController = new CitaController();

$totalResponsables = count($responsableController->index());
$totalInfantes = count($infanteController->index());
$totalVacunas = count($vacunaController->index());
$totalCitas = count($citaController->index());

$citasRecientes = array_slice($citaController->index(), 0, 5);
$infantesRecientes = array_slice($infanteController->index(), 0, 5);

$page_title = 'Dashboard';
include __DIR__ . '/views/layout/header.php';
?>

<div class="row">
    <!-- Estadísticas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Responsables</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalResponsables; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Infantes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalInfantes; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-baby fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Vacunas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalVacunas; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-droplet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Citas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalCitas; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Citas Recientes -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="bi bi-calendar-event"></i> Citas Recientes
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($citasRecientes)): ?>
                    <p class="text-muted text-center">No hay citas registradas</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Infante</th>
                                    <th>Vacuna</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citasRecientes as $cita): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($cita['infante_nombre'] . ' ' . $cita['infante_apellido']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($cita['vacuna_nombre']); ?></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo date('d/m/Y', strtotime($cita['fecha_inyeccion'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-center mt-3">
                    <a href="citas.php" class="btn btn-primary btn-sm">
                        Ver Todas las Citas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Infantes Recientes -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="bi bi-baby"></i> Infantes Recientes
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($infantesRecientes)): ?>
                    <p class="text-muted text-center">No hay infantes registrados</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Responsable</th>
                                    <th>Edad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($infantesRecientes as $infante): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($infante['nombre'] . ' ' . $infante['apellido']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($infante['responsable_nombre'] . ' ' . $infante['responsable_apellido']); ?></td>
                                        <td>
                                            <?php 
                                            $edad = $infanteController->calcularEdad($infante['fecha_nacimiento']);
                                            echo $edad . ' años';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <div class="text-center mt-3">
                    <a href="infantes.php" class="btn btn-primary btn-sm">
                        Ver Todos los Infantes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Acciones Rápidas -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="bi bi-lightning"></i> Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <a href="responsables.php?action=create" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-person-plus"></i><br>
                            <small>Nuevo Responsable</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="infantes.php?action=create" class="btn btn-outline-success btn-lg w-100">
                            <i class="bi bi-baby"></i><br>
                            <small>Nuevo Infante</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="vacunas.php?action=create" class="btn btn-outline-info btn-lg w-100">
                            <i class="bi bi-droplet"></i><br>
                            <small>Nueva Vacuna</small>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="citas.php?action=create" class="btn btn-outline-warning btn-lg w-100">
                            <i class="bi bi-calendar-plus"></i><br>
                            <small>Nueva Cita</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/layout/footer.php'; ?>
