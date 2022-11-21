<?php
include("db_connect.php");

$request_method = $_SERVER["REQUEST_METHOD"];

//getProducts();

switch ($request_method) {
    case 'GET':
        // Retrive Products
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            getUser($id);
        } else {
            getUsers();
        }
        break;

    case 'POST':
        // Ajouter un produit
        addUser();
        break;
    case 'PUT':
        // Modifier un produit
        $id = intval($_GET["id"]);
        updateUser($id);
        break;

    case 'DELETE':
        // Supprimer un produit
        $id = intval($_GET["id"]);
        deleteUser($id);
        break;

    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}



function addUser()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $nom = $data['nom'];
    $prenom = $data['prenom'];
    $email = $data['email'];
    $age = $data['age'];

    //echo 
    $query = "INSERT INTO user(nom, prenom, email, age) VALUES('" . $nom . "', '" . $prenom . "', '" . $email . "', '" . $age . "')";

    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'User ajouté avec succès.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'ERREUR!.' . mysqli_error($conn)
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getUsers()
{
    global $conn;
    $query = "SELECT * FROM user";
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getUser($id = 0)
{
    global $conn;
    $query = "SELECT * FROM user";
    if ($id != 0) {
        $query .= " WHERE id=" . $id . " LIMIT 1";
    }
    $response = array();
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $response[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function updateUser($id)
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $nom = $data["nom"];
    $prenom = $data["prenom"];
    $email = $data["email"];
    $age = $data["age"];

    $query = "UPDATE user SET nom='" . $nom . "', prenom='" . $prenom . "', email='" . $email . "', age='" . $age . "' WHERE id=" . $id;

    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'User mis a jour avec succes.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'Echec de la mise a jour de produit. ' . mysqli_error($conn)
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}



function deleteUser($id)
{
    global $conn;
    $query = "DELETE FROM user WHERE id=" . $id;
    if (mysqli_query($conn, $query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'User supprime avec succes.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'La suppression du User a echoue. ' . mysqli_error($conn)
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
