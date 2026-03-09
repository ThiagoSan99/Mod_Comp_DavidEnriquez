document.addEventListener("DOMContentLoaded", function () {

    const btnNueva = document.getElementById("btnNueva");
    const formAsignatura = document.getElementById("formAsignatura");
    const formAsignacion = document.getElementById("formAsignacion");

    /* =========================
       UTIL
    ========================= */

    function parseData(data) {
        if (Array.isArray(data)) return data;
        if (data.data) return data.data;
        return [];
    }

    /* =========================
       LISTAR ASIGNATURAS
    ========================= */

    function cargarAsignaturas() {

        fetch("api/asignaturas.php?action=list")
            .then(res => res.json())
            .then(data => {

                data = parseData(data);

                const tbody = document.getElementById("tablaAsignaturas");
                if (!tbody) return;

                tbody.innerHTML = "";

                data.forEach(asig => {

                    const fila = `
                    <tr>
                        <td>${asig.name}</td>
                        <td>${asig.cod ?? ''}</td>
                        <td>${asig.teacher}</td>
                        <td>${asig.schedule}</td>

                        <td>
                            <button class="btn btn-warning btn-sm btn-editar" data-id="${asig.id_asig}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm btn-eliminar" data-id="${asig.id_asig}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;

                    tbody.innerHTML += fila;

                });

                activarBotonesAsignaturas();

            });

    }

    /* =========================
       LISTAR ASIGNACIONES
    ========================= */

    function cargarAsignaciones() {

        fetch("api/asignaciones.php?action=list")
            .then(res => res.json())
            .then(data => {

                data = parseData(data);

                const tbody = document.getElementById("tablaAsignaciones");
                if (!tbody) return;

                tbody.innerHTML = "";

                data.forEach(a => {

                    const fila = `
                    <tr>
                        <td>${a.estudiante}</td>
                        <td>${a.asignatura}</td>
                        <td>${a.cod ?? ''}</td>
                        <td>${a.teacher}</td>
                        <td>${a.schedule}</td>

                        <td>
                            <button class="btn btn-warning btn-sm btn-editar-asig" data-id="${a.id}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-danger btn-sm btn-eliminar-asig" data-id="${a.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;

                    tbody.innerHTML += fila;

                });

                activarBotonesAsignacion();

            });

    }

    /* =========================
       SELECT ASIGNATURAS
    ========================= */

    function cargarSelectAsignaturas() {

        fetch("api/asignaturas.php?action=list")
            .then(res => res.json())
            .then(data => {

                data = parseData(data);

                const select = document.querySelector("select[name='id_asig']");
                if (!select) return;

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

    /* =========================
       GUARDAR ASIGNATURA
    ========================= */

    if (formAsignatura) {

        formAsignatura.addEventListener("submit", function (e) {

            e.preventDefault();

            const formData = new FormData(this);
            const id = document.getElementById("id_asig").value;

            if (id) {
                formData.append("action", "update");
            } else {
                formData.append("action", "save");
            }

            fetch("api/asignaturas.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {

                    if (data.success) {

                        $('#modalAsignatura').modal('hide');

                        Swal.fire(
                            'Éxito',
                            'Asignatura guardada',
                            'success'
                        );

                        this.reset();
                        document.getElementById("id_asig").value = "";

                        cargarAsignaturas();
                        cargarSelectAsignaturas();
                        cargarAsignaciones();

                    } else {

                        Swal.fire("Error", data.error, "error");

                    }

                });

        });

    }

    /* =========================
       GUARDAR ASIGNACION
    ========================= */

    if (formAsignacion) {

        formAsignacion.addEventListener("submit", function (e) {

            e.preventDefault();

            const formData = new FormData(this);
            const id = document.getElementById("id_asig").value;

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

                    if (data.success) {

                        Swal.fire(
                            'Éxito',
                            'Asignación guardada',
                            'success'
                        );

                        this.reset();
                        document.getElementById("id_asig").value = "";

                        cargarAsignaciones();

                    } else {

                        Swal.fire("Error", data.error, "error");

                    }

                });

        });

    }

    /* =========================
       BOTONES ASIGNATURAS
    ========================= */

    function activarBotonesAsignaturas() {

        /* EDITAR */

        document.querySelectorAll(".btn-editar").forEach(btn => {

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

        /* ELIMINAR */

        document.querySelectorAll(".btn-eliminar").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                Swal.fire({
                    title: "¿Eliminar asignatura?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Eliminar"
                }).then((result) => {

                    if (result.isConfirmed) {

                        fetch("api/asignaturas.php?action=delete", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "id=" + id
                        })
                            .then(res => res.json())
                            .then(data => {

                                if (data.success) {

                                    Swal.fire("Eliminado", "", "success");

                                    cargarAsignaturas();
                                    cargarSelectAsignaturas();
                                    cargarAsignaciones();

                                }

                            });

                    }

                });

            };

        });

    }

    /* =========================
       BOTONES ASIGNACIONES
    ========================= */

    function activarBotonesAsignacion() {

        /* EDITAR */

        document.querySelectorAll(".btn-editar-asig").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                fetch("api/asignaciones.php?action=get&id=" + id)
                    .then(res => res.json())
                    .then(data => {

                        document.querySelector("select[name='id_est']").value = data.id_est;
                        document.querySelector("select[name='id_asig']").value = data.id_asig;

                        document.getElementById("id_asig").value = data.id;

                    });

            };

        });

        /* ELIMINAR */

        document.querySelectorAll(".btn-eliminar-asig").forEach(btn => {

            btn.onclick = function () {

                const id = this.dataset.id;

                Swal.fire({
                    title: "¿Eliminar asignación?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Eliminar"
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

                                    Swal.fire("Eliminado", "", "success");

                                    cargarAsignaciones();

                                }

                            });

                    }

                });

            };

        });

    }

    /* =========================
       BOTON NUEVA ASIGNATURA
    ========================= */

    if (btnNueva) {

        btnNueva.addEventListener("click", function () {

            formAsignatura.reset();
            document.getElementById("id_asig").value = "";

            $('#modalAsignatura').modal('show');

        });

    }

    /* =========================
       INICIALIZAR
    ========================= */

    cargarAsignaturas();
    cargarAsignaciones();
    cargarSelectAsignaturas();

});