<?php
// Compatible con PHP 7.4+
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Panel de Estudiantes</title>

<!-- ============================= -->
<!-- AdminLTE 3 CSS -->
<!-- ============================= -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php include "navbar.php"; ?>
    <?php include "sidebar.php"; ?>

    <!-- ============================= -->
    <!-- CONTENIDO PRINCIPAL -->
    <!-- ============================= -->
    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <h1>Listado de Estudiantes</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <!-- CARD -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between">

                        <!-- BOTÓN AGREGAR -->
                        <button id="btnAgregar" class="btn btn-success">
                            <i class="fas fa-plus"></i> Agregar Estudiante
                        </button>

                        <!-- BUSCADOR -->
                        <div class="d-flex">
                            <input type="text" id="buscarCedula" 
                                   class="form-control mr-2"
                                   placeholder="Buscar por Cédula">
                            <button id="btnBuscarCedula" 
                                    class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <button id="btnMostrarTodos" 
                                    class="btn btn-secondary">
                                Mostrar Todos
                            </button>
                        </div>

                    </div>

                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Edad</th>
                                    <th width="150">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se llena dinámicamente por JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <!-- ============================= -->
    <!-- FOOTER -->
    <!-- ============================= -->
    <footer class="main-footer text-center">
        <strong>Proyecto CRUD API &copy; <?php echo date("Y"); ?></strong>
    </footer>

</div>

<!-- ============================= -->
<!-- MODAL (AdminLTE / Bootstrap) -->
<!-- ============================= -->
<div class="modal fade" id="modalEstudiante">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Estudiante</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <form id="formEstudiante">
        <div class="modal-body">

            <input type="hidden" name="id_est" id="id_est">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" id="name" 
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label>Cédula</label>
                <input type="text" name="identity" id="identity" 
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label>Edad</label>
                <input type="number" name="age" id="age" 
                       class="form-control" required>
            </div>

        </div>

        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" 
                    data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">
                Guardar
            </button>
        </div>
      </form>

    </div>
  </div>
</div>


<!-- ============================= -->
<!-- SCRIPTS ADMINLTE -->
<!-- ============================= -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- TU SCRIPT -->
<script src="js/scripts.js"></script>


</body>
</html>