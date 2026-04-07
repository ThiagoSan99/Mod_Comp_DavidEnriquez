<?php
include __DIR__ . '/../src/Connection/connect.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Asignaturas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

    <?php include __DIR__ . '/../src/views/navbar.php'; ?>
    <?php include __DIR__ . '/../src/views/sidebar.php'; ?>

    <div class="content-wrapper">

        <section class="content">

            <div class="container-fluid">

                <!-- ============================= -->
                <!-- GESTIÓN DE ASIGNATURAS -->
                <!-- ============================= -->

                <div class="card mt-4">

                    <div class="card-header bg-primary">

                        <h3 class="card-title">
                            <i class="fas fa-book"></i> Gestión de Asignaturas
                        </h3>

                        <button class="btn btn-light float-right" id="btnNueva">
                            <i class="fas fa-plus"></i> Nueva
                        </button>

                    </div>

                    <div class="card-body">

                        <table class="table table-bordered table-hover">

                            <thead class="thead-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Código</th>
                                    <th>Profesor</th>
                                    <th>Horario</th>
                                    <th width="150">Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaAsignaturas"></tbody>

                        </table>

                    </div>

                </div>


                <!-- ============================= -->
                <!-- ASIGNAR ASIGNATURA -->
                <!-- ============================= -->

                <div class="card mt-4">

                    <div class="card-header bg-primary">
                        <h3 class="card-title">Asignaciones a Estudiante</h3>
                    </div>

                    <div class="card-body">

                        <form id="formAsignacion">

                            <input type="hidden" id="id_asignacion" name="id">

                            <div class="row">

                                <!-- ESTUDIANTE -->

                                <div class="col-md-6">

                                    <label>Estudiante</label>

                                    <select name="id_est" class="form-control" required>

                                        <option value="">Seleccione</option>

                                        <?php
                                        $stmt = $myPDO->query("SELECT id_est, name FROM estudiante");

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row['id_est']}'>{$row['name']}</option>";
                                        }
                                        ?>

                                    </select>

                                </div>


                                <!-- ASIGNATURA -->

                                <div class="col-md-6">

                                    <label>Asignatura</label>

                                    <select name="id_asig" class="form-control" required>

                                        <option value="">Seleccione</option>

                                        <?php
                                        $stmt = $myPDO->query("SELECT id_asig, name FROM asignaturas");

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row['id_asig']}'>{$row['name']}</option>";
                                        }
                                        ?>

                                    </select>

                                </div>

                            </div>

                            <div class="mt-3">

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Asignar
                                </button>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- ============================= -->
                <!-- LISTA DE ASIGNACIONES -->
                <!-- ============================= -->

                <div class="card mt-4">

                    <div class="card-header bg-dark">
                        <h3 class="card-title">Asignaciones</h3>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Asignatura</th>
                                    <th>Código</th>
                                    <th>Profesor</th>
                                    <th>Horario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody id="tablaAsignaciones"></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </section>

    </div>

</div>


<!-- ============================= -->
<!-- MODAL ASIGNATURA -->
<!-- ============================= -->

<div class="modal fade" id="modalAsignatura">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">Asignatura</h4>

                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>

            </div>

            <form id="formAsignatura">

                <input type="hidden" id="id_asig" name="id_asig">

                <div class="modal-body">

                    <div class="form-group">

                        <label>Nombre</label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label>Código</label>

                        <input
                            type="text"
                            name="cod"
                            id="cod"
                            class="form-control"
                            readonly
                        >

                    </div>


                    <div class="form-group">

                        <label>Profesor</label>

                        <input
                            type="text"
                            name="teacher"
                            id="teacher"
                            class="form-control"
                        >

                    </div>


                    <div class="form-group">

                        <label>Horario</label>

                        <select id="schedule" name="schedule" class="form-control" required>

                            <option value="">Seleccione horario</option>

                            <option value="Lunes 8-10">Lunes 8-10</option>
                            <option value="Lunes 10-12">Lunes 10-12</option>
                            <option value="Martes 8-10">Martes 8-10</option>
                            <option value="Martes 10-12">Martes 10-12</option>
                            <option value="Miércoles 8-10">Miércoles 8-10</option>
                            <option value="Jueves 8-10">Jueves 8-10</option>
                            <option value="Viernes 8-10">Viernes 8-10</option>

                        </select>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Cerrar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Guardar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ============================= -->
<!-- SCRIPTS -->
<!-- ============================= -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="/js/asignaturas.js"></script>

</body>
</html>