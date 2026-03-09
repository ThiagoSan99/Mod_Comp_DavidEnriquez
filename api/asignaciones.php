<?php

require_once(__DIR__ . "/../Conection/conect.php");

header("Content-Type: application/json");

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {

    switch ($action) {

        /* =========================
           LISTAR ASIGNACIONES
        ========================= */

        case "list":

            $stmt = $myPDO->prepare("
                SELECT 
                    ea.id,
                    e.name AS estudiante,
                    a.name AS asignatura,
                    a.cod,
                    a.teacher,
                    a.schedule,
                    ea.id_est,
                    ea.id_asig
                FROM estudiante_asignatura ea
                JOIN estudiante e ON ea.id_est = e.id_est
                JOIN asignaturas a ON ea.id_asig = a.id_asig
                ORDER BY e.name
            ");

            $stmt->execute();

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

        break;


        /* =========================
           CREAR ASIGNACION
        ========================= */

        case "save":

            $id_est = $_POST["id_est"] ?? null;
            $id_asig = $_POST["id_asig"] ?? null;

            if (!$id_est || !$id_asig) {

                echo json_encode([
                    "success" => false,
                    "error" => "Datos incompletos"
                ]);

                exit;
            }

            /* obtener horario */

            $stmt = $myPDO->prepare("
                SELECT schedule
                FROM asignaturas
                WHERE id_asig = ?
            ");

            $stmt->execute([$id_asig]);

            $materia = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$materia) {

                echo json_encode([
                    "success" => false,
                    "error" => "Asignatura no encontrada"
                ]);

                exit;
            }

            $schedule = $materia["schedule"];

            /* validar choque */

            $check = $myPDO->prepare("
                SELECT ea.id
                FROM estudiante_asignatura ea
                JOIN asignaturas a ON ea.id_asig = a.id_asig
                WHERE ea.id_est = ? AND a.schedule = ?
            ");

            $check->execute([$id_est, $schedule]);

            if ($check->rowCount() > 0) {

                echo json_encode([
                    "success" => false,
                    "error" => "El estudiante ya tiene clase en ese horario"
                ]);

                exit;
            }

            /* insertar */

            $stmt = $myPDO->prepare("
                INSERT INTO estudiante_asignatura (id_est, id_asig)
                VALUES (?, ?)
            ");

            $stmt->execute([$id_est, $id_asig]);

            echo json_encode([
                "success" => true
            ]);

        break;


        /* =========================
           OBTENER ASIGNACION
        ========================= */

        case "get":

            $id = $_GET["id"] ?? 0;

            $stmt = $myPDO->prepare("
                SELECT *
                FROM estudiante_asignatura
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));

        break;


        /* =========================
           ACTUALIZAR
        ========================= */

        case "update":

            $id = $_POST["id"];
            $id_est = $_POST["id_est"];
            $id_asig = $_POST["id_asig"];

            /* validar horario nuevamente */

            $stmt = $myPDO->prepare("
                SELECT schedule
                FROM asignaturas
                WHERE id_asig = ?
            ");

            $stmt->execute([$id_asig]);

            $schedule = $stmt->fetchColumn();

            $check = $myPDO->prepare("
                SELECT ea.id
                FROM estudiante_asignatura ea
                JOIN asignaturas a ON ea.id_asig = a.id_asig
                WHERE ea.id_est = ?
                AND a.schedule = ?
                AND ea.id <> ?
            ");

            $check->execute([$id_est, $schedule, $id]);

            if ($check->rowCount() > 0) {

                echo json_encode([
                    "success" => false,
                    "error" => "Choque de horario"
                ]);

                exit;
            }

            /* actualizar */

            $stmt = $myPDO->prepare("
                UPDATE estudiante_asignatura
                SET id_est = ?, id_asig = ?
                WHERE id = ?
            ");

            $stmt->execute([$id_est, $id_asig, $id]);

            echo json_encode([
                "success" => true
            ]);

        break;


        /* =========================
           ELIMINAR
        ========================= */

        case "delete":

            $id = $_POST["id"] ?? 0;

            $stmt = $myPDO->prepare("
                DELETE FROM estudiante_asignatura
                WHERE id = ?
            ");

            $stmt->execute([$id]);

            echo json_encode([
                "success" => true
            ]);

        break;


        /* =========================
           DEFAULT
        ========================= */

        default:

            echo json_encode([
                "success" => false,
                "error" => "Acción inválida"
            ]);

    }

} catch (PDOException $e) {

    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);

}

$myPDO = null;