document.addEventListener("DOMContentLoaded", function () {

    const btnAgregar = document.getElementById("btnAgregar");
    const form = document.getElementById("formEstudiante");
    const btnBuscar = document.getElementById("btnBuscarCedula");
    const btnMostrarTodos = document.getElementById("btnMostrarTodos");
    const btnNueva = document.getElementById("btnNueva");
    console.log(btnNueva)
    // =========================
    // FUNCIÓN PARA LISTAR
    // =========================
    function cargarEstudiantes() {

        fetch("/api/estudiantes.php?action=list")
        .then(res => res.json())
        .then(data => {

            const tbody = document.querySelector("tbody");
            tbody.innerHTML = "";

            data.forEach(est => {

                const fila = `
                    <tr>
                        <td>${est.name}</td>
                        <td>${est.identity}</td>
                        <td>${est.age}</td>
                        <td>
                            <button class="btn btn-warning btn-sm btn-editar" data-id="${est.id_est}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btn-eliminar" data-id="${est.id_est}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                tbody.innerHTML += fila;
            });

            activarBotones();
        });
    }

    // =========================
    // ABRIR MODAL AGREGAR
    // =========================
    btnAgregar.addEventListener("click", function () {
        form.reset();
        document.getElementById("id_est").value = "";
        $('#modalEstudiante').modal('show');
    });

    // =========================
    // GUARDAR (INSERT / UPDATE)
    // =========================
    form.addEventListener("submit", function(e){

        e.preventDefault();

        const formData = new FormData(form);
        formData.append("action", "save");

        fetch("api/estudiantes.php?action=save", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            if(data.success){
                Swal.fire("Estudiante agregado", "", "success");
                $('#modalEstudiante').modal('hide');
                cargarEstudiantes();
            } else {
                alert("Error: " + data.error);
            }

        });
    });

    // =========================
    // EDITAR / ELIMINAR
    // =========================
    function activarBotones(){

        // EDITAR
        document.querySelectorAll(".btn-editar").forEach(btn => {

            btn.onclick = function(){

                const id = this.dataset.id;

                fetch("api/estudiantes.php?action=getById&id=" + id)
                .then(res => res.json())
                .then(data => {

                    document.getElementById("id_est").value = data.id_est;
                    document.getElementById("name").value = data.name;
                    document.getElementById("identity").value = data.identity;
                    document.getElementById("age").value = data.age;

                    $('#modalEstudiante').modal('show');

                });
            };
        });

        // ELIMINAR
        document.querySelectorAll(".btn-eliminar").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                Swal.fire({
                    title: "¿Seguro que deseas eliminar este registro?",
                    text: "Esta acción no se puede deshacer",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6"
                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch("api/estudiantes.php?action=delete", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "id=" + id + "&action=delete"
                        })
                        .then(res => res.json())
                        .then(data => {

                            if (data.success) {

                                Swal.fire(
                                    "Eliminado",
                                    "El registro fue eliminado correctamente",
                                    "success"
                                );

                                cargarEstudiantes();

                            } else {

                                Swal.fire(
                                    "Error",
                                    data.error,
                                    "error"
                                );

                            }

                        });

                    }

                });

            };

        });
    }

    // =========================
    // BUSCAR POR ID
    // =========================
    btnBuscar.addEventListener("click", function(){

        const id = document.getElementById("buscarCedula").value;

        if(id === ""){
            Swal.fire(
                                    "Error",
                                    "Debe ingresar la cedula",
                                    "error"
                                );
            return;
        }

        fetch("api/estudiantes.php?action=get&id=" + id)
        .then(res => res.json())
        .then(data => {

            const tbody = document.querySelector("tbody");
            tbody.innerHTML = "";

            if(!data){
                tbody.innerHTML = "<tr><td colspan='4'>No encontrado</td></tr>";
                return;
            }

            const fila = `
                <tr>
                    <td>${data.name}</td>
                    <td>${data.identity}</td>
                    <td>${data.age}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-editar" data-id="${data.id_est}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-eliminar" data-id="${data.id_est}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            tbody.innerHTML = fila;

            activarBotones();
        });

    });

    // =========================
    // MOSTRAR TODOS
    // =========================
    btnMostrarTodos.addEventListener("click", function(){
        cargarEstudiantes();
    });

    // =========================
    // INICIALIZAR
    // =========================
    cargarEstudiantes();

});