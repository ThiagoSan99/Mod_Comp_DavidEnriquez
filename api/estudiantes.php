<?php
require_once(__DIR__ . "/../Conection/conect.php");

header("Content-Type: application/json");

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {

    switch($action){

        // =============================
        // LISTAR
        // =============================
        case "list":

            $stmt = $myPDO->prepare("SELECT id_est, name, identity, age FROM Estudiante");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($data);
            break;

        // =============================
        // OBTENER UNO (EDITAR)
        // =============================
        case "get":

            $id = $_GET['id'] ?? 0;

            $stmt = $myPDO->prepare("SELECT * FROM Estudiante WHERE identity = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode($data);
            break;

        // =============================
        // GUARDAR (INSERT / UPDATE)
        // =============================
        case "save":

            $id = $_POST['id_est'] ?? '';
            $name = $_POST['name'];
            $identity = $_POST['identity'];
            $age = $_POST['age'];

            if(empty($id)){
                // INSERT
                $stmt = $myPDO->prepare(
                    "INSERT INTO Estudiante (name, identity, age) VALUES (?, ?, ?)"
                );
                $stmt->execute([$name, $identity, $age]);
            } else {
                // UPDATE
                $stmt = $myPDO->prepare(
                    "UPDATE Estudiante SET name=?, identity=?, age=? WHERE id_est=?"
                );
                $stmt->execute([$name, $identity, $age, $id]);
            }

            echo json_encode(["success" => true]);
            break;

        // =============================
        // ELIMINAR
        // =============================
        case "delete":

            $id = $_POST['id'] ?? 0;

            $stmt = $myPDO->prepare("DELETE FROM Estudiante WHERE id_est = ?");
            $stmt->execute([$id]);

            echo json_encode(["success" => true]);
            break;

        default:
            echo json_encode(["error" => "Acción no válida"]);
            break;
    }

} catch(PDOException $e){
    echo json_encode(["error" => $e->getMessage()]);
}

$myPDO = null;