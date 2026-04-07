<?php

require_once __DIR__ . '/../Connection/connect.php';

header("Content-Type: application/json");

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {

    switch ($action) {

        /* =========================
           LISTAR ASIGNATURAS
        ========================= */

        case "list":

            $stmt = $myPDO->prepare("
                SELECT id_asig, name, cod, teacher, schedule
                FROM asignaturas
                ORDER BY name
            ");

            $stmt->execute();

            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

        break;


        /* =========================
           GENERAR CODIGO AUTOMATICO
        ========================= */

        case "generateCode":

            $stmt = $myPDO->prepare("
                SELECT cod 
                FROM asignaturas 
                ORDER BY id_asig DESC 
                LIMIT 1
            ");

            $stmt->execute();

            $last = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$last) {

                $codigo = "001";

            } else {

                $num = intval($last["cod"]) + 1;
                $codigo = str_pad($num, 3, "0", STR_PAD_LEFT);

            }

            echo json_encode([
                "cod" => $codigo
            ]);

        break;


        /* =========================
           CREAR ASIGNATURA
        ========================= */

        case "save":

            $name = $_POST["name"] ?? '';
            $teacher = $_POST["teacher"] ?? '';
            $schedule = $_POST["schedule"] ?? '';

            if (!$name || !$teacher || !$schedule) {

                echo json_encode([
                    "success" => false,
                    "error" => "Datos incompletos"
                ]);

                exit;
            }

            /* generar código automáticamente */

            $stmt = $myPDO->prepare("
                SELECT cod
                FROM asignaturas
                ORDER BY id_asig DESC
                LIMIT 1
            ");

            $stmt->execute();

            $last = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$last) {

                $cod = "001";

            } else {

                $num = intval($last["cod"]) + 1;
                $cod = str_pad($num, 3, "0", STR_PAD_LEFT);

            }

            /* insertar */

            $stmt = $myPDO->prepare("
                INSERT INTO asignaturas(name, cod, teacher, schedule)
                VALUES(?, ?, ?, ?)
            ");

            $stmt->execute([$name, $cod, $teacher, $schedule]);

            echo json_encode([
                "success" => true
            ]);

        break;


        /* =========================
           OBTENER ASIGNATURA
        ========================= */

        case "get":

            $id = $_GET["id"] ?? 0;

            $stmt = $myPDO->prepare("
                SELECT *
                FROM asignaturas
                WHERE id_asig = ?
            ");

            $stmt->execute([$id]);

            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));

        break;


        /* =========================
           ACTUALIZAR
        ========================= */

        case "update":

            $id = $_POST["id_asig"];
            $name = $_POST["name"];
            $teacher = $_POST["teacher"];
            $schedule = $_POST["schedule"];

            /* el código NO se modifica */

            $stmt = $myPDO->prepare("
                UPDATE asignaturas
                SET name = ?, teacher = ?, schedule = ?
                WHERE id_asig = ?
            ");

            $stmt->execute([$name, $teacher, $schedule, $id]);

            echo json_encode([
                "success" => true
            ]);

        break;


        /* =========================
           ELIMINAR
        ========================= */

        case "delete":

            $id = $_POST["id"] ?? 0;

            /* primero eliminar asignaciones */

            $stmt = $myPDO->prepare("
                DELETE FROM estudiante_asignatura
                WHERE id_asig = ?
            ");

            $stmt->execute([$id]);

            /* luego eliminar asignatura */

            $stmt = $myPDO->prepare("
                DELETE FROM asignaturas
                WHERE id_asig = ?
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