document.addEventListener("DOMContentLoaded", function () {

    const btnNueva = document.getElementById("btnNueva");
    const formAsignatura = document.getElementById("formAsignatura");
    const formAsignacion = document.getElementById("formAsignacion");

    function parseData(data) {
        if (Array.isArray(data)) return data;
        if (data.data) return data.data;
        return [];
    }

    /* =============================
       CARGAR ASIGNATURAS
    ============================= */

    function cargarAsignaturas() {

        fetch("api/asignaturas.php?action=list")
            .then(res => res.json())
            .then(data => {

                data = parseData(data);

                const tbody = document.getElementById("tablaAsignaturas");
                tbody.innerHTML = "";

                data.forEach(asig => {

                    tbody.innerHTML += `
                    <tr>
                        <td>${asig.name}</td>
                        <td>${asig.cod ?? ''}</td>
                        <td>${asig.teacher}</td>
                        <td>${asig.schedule}</td>

                        <td>

                            <button class="btn btn-warning btn-sm editar" data-id="${asig.id_asig}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm eliminar" data-id="${asig.id_asig}">
                                <i class="fas fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                    `;
                });

                activarBotonesAsignaturas();
            });
    }

    /* =============================
       CARGAR SELECT
    ============================= */

    function cargarSelectAsignaturas() {

        fetch("api/asignaturas.php?action=list")
            .then(res => res.json())
            .then(data => {

                data = parseData(data);

                const select = document.querySelector("select[name='id_asig']");
                select.innerHTML = '<option value="">Seleccione</option>';

                data.forEach(asig => {

                    select.innerHTML += `
                    <option value="${asig.id_asig}">
                        ${asig.name}
                    </option>
                    `;
                });
            });
    }

    /* =============================
       CARGAR ASIGNACIONES
    ============================= */

    function cargarAsignaciones() {

        fetch("api/asignaciones.php?action=list")
            .then(res => res.json())
            .then(data => {

                data = parseData(data);

                const tbody = document.getElementById("tablaAsignaciones");
                tbody.innerHTML = "";

                data.forEach(a => {

                    tbody.innerHTML += `
                    <tr>
                        <td>${a.estudiante}</td>
                        <td>${a.asignatura}</td>
                        <td>${a.cod}</td>
                        <td>${a.teacher}</td>
                        <td>${a.schedule}</td>

                        <td>

                            <button class="btn btn-warning btn-sm editarAsig" data-id="${a.id}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm eliminarAsig" data-id="${a.id}">
                                <i class="fas fa-trash"></i>
                            </button>

                        </td>

                    </tr>
                    `;
                });

                activarBotonesAsignacion();
            });
    }

    /* =============================
       GUARDAR ASIGNATURA
    ============================= */

    formAsignatura.addEventListener("submit", function (e) {

        e.preventDefault();

        const formData = new FormData(this);
        const id = document.getElementById("id_asig").value;

        formData.append("action", id ? "update" : "save");

        fetch("api/asignaturas.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {

                if (data.success) {

                    $('#modalAsignatura').modal('hide');

                    Swal.fire("Guardado", "", "success");

                    this.reset();
                    document.getElementById("id_asig").value = "";

                    cargarAsignaturas();
                    cargarSelectAsignaturas();
                    cargarAsignaciones();
                }
            });
    });

    /* =============================
       GUARDAR ASIGNACION
    ============================= */

    formAsignacion.addEventListener("submit", function (e) {

        e.preventDefault();

        const formData = new FormData(this);
        const id = document.getElementById("id_asignacion").value;

        if (id) {
            formData.append("action", "update");
        } else {
            formData.append("action", "save");
        }

        fetch("api/asignaciones.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
            if(data.success){

                Swal.fire("Guardado","","success");

                this.reset();
                document.getElementById("id_asignacion").value="";

                cargarAsignaciones();

            } else {

                Swal.fire({
                    icon: "warning",
                    title: "No se puede asignar en ese horario",
                    text: data.error
                });

            }

        });
    });

    /* =============================
       BOTONES ASIGNATURA
    ============================= */

    function activarBotonesAsignaturas() {

        document.querySelectorAll(".editar").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                fetch("api/asignaturas.php?action=get&id=" + id)
                    .then(res => res.json())
                    .then(data => {

                        document.getElementById("id_asig").value = data.id_asig;
                        document.getElementById("name").value = data.name;
                        document.getElementById("cod").value = data.cod;
                        document.getElementById("teacher").value = data.teacher;
                        document.getElementById("schedule").value = data.schedule;

                        $('#modalAsignatura').modal('show');
                    });
            };
        });

        document.querySelectorAll(".eliminar").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                Swal.fire({
                    title: "Eliminar?",
                    icon: "warning",
                    showCancelButton: true
                }).then(result => {

                    if (result.isConfirmed) {

                        fetch("api/asignaturas.php?action=delete", {

                            method: "POST",

                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },

                            body: "id=" + id

                        })
                            .then(res => res.json())
                            .then(() => {

                                cargarAsignaturas();
                                cargarSelectAsignaturas();
                                cargarAsignaciones();

                            });
                    }
                });
            };
        });
    }
    
    function cargarSelectAsignaturasEditar(idActual){

        fetch("api/asignaturas.php?action=list")
        .then(res => res.json())
        .then(data => {

            const select = document.querySelector("select[name='id_asig']");
            select.innerHTML = '<option value="">Seleccione</option>';

            data.forEach(asig => {

                // 🔥 FILTRO: excluir la actual
                if(asig.id_asig != idActual){

                    select.innerHTML += `
                        <option value="${asig.id_asig}">
                            ${asig.name}
                        </option>
                    `;

                }

            });

        });

    }

    /* =============================
       BOTONES ASIGNACION
    ============================= */

    function activarBotonesAsignacion() {

        document.querySelectorAll(".editarAsig").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                fetch("api/asignaciones.php?action=get&id=" + id)
                .then(res => res.json())
                .then(data => {

                    // set estudiante
                    document.querySelector("select[name='id_est']").value = data.id_est;

                    // cargar asignaturas SIN la actual
                    cargarSelectAsignaturasEditar(data.id_asig);

                    // guardar id de la asignación
                    document.getElementById("id_asignacion").value = data.id;

                });

            };
        });

        document.querySelectorAll(".eliminarAsig").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                Swal.fire({
                    title: "¿Eliminar asignación?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch("api/asignaciones.php?action=delete", {

                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },

                            body: "id=" + id

                        })
                        .then(res => res.json())
                        .then(data => {

                            if (data.success) {

                                Swal.fire({
                                    icon: "success",
                                    title: "Asignación eliminada",
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                cargarAsignaciones();

                            } else {

                                Swal.fire("Error", data.error, "error");

                            }

                        });

                    }

                });

            };
        });
    }

    /* =============================
       BOTON NUEVA
    ============================= */

    btnNueva.addEventListener("click", function () {

        formAsignatura.reset();
        document.getElementById("id_asig").value = "";

        $('#modalAsignatura').modal('show');

    });

    /* =============================
       INICIO
    ============================= */

    cargarAsignaturas();
    cargarAsignaciones();
    cargarSelectAsignaturas();

});