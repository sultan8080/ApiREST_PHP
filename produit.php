<?php
include("db_connect.php");

$request_method = $_SERVER["REQUEST_METHOD"];

//getProducts();

switch($request_method) {
    case 'GET':
        // Retrive Products
        if (!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            getProduct($id);
        } else {
            getProducts();
        }
        break;

    case 'POST':
			// Ajouter un produit
			AddProduct();
			break;
    case 'PUT':
            // Modifier un produit
            $id = intval($_GET["id"]);
            updateProduct($id);
            break;

    case 'DELETE':
        // Supprimer un produit
        $id = intval($_GET["id"]);
        deleteProduct($id);
        break;
    
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function deleteProduct($id)
{
    global $conn;
    $query = "DELETE FROM produit WHERE id=".$id;
    if(mysqli_query($conn, $query))
    {
        $response=array(
            'status' => 1,
            'status_message' =>'Produit supprime avec succes.'
        );
    }
    else
    {
        $response=array(
            'status' => 0,
            'status_message' =>'La suppression du produit a echoue. '. mysqli_error($conn)
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function AddProduct()
{
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
   
    $name = $data["name"];
    $description = $data["description"];
    $price = $data["price"];
    $category = $data["category"];
    
    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');


    //echo 
    $query="INSERT INTO produit(name, description, price, category_id, created, modified) VALUES('".$name."', '".$description."', '".$price."', '".$category."', '".$created."', '".$modified."')";
    
    if(mysqli_query($conn, $query))
    {
        $response=array(
            'status' => 1,
            'status_message' =>'Produit ajouté avec succès.'
        );
    }
    else
    {
        $response=array(
            'status' => 0,
            'status_message' =>'ERREUR!.'. mysqli_error($conn)
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getProducts()
	{
		global $conn;
		$query = "SELECT * FROM produit";
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}

    function getProduct($id=0)
	{
		global $conn;
		$query = "SELECT * FROM produit";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}

    function updateProduct($id)
	{
		global $conn;
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data["name"];
        $description = $data["description"];
        $price = $data["price"];
        $category = $data["category"];

		$created = 'NULL';
		$modified = date('Y-m-d H:i:s');
		$query="UPDATE produit SET name='".$name."', description='".$description."', price='".$price."', category_id='".$category."', modified='".$modified."' WHERE id=".$id;
		
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Produit mis a jour avec succes.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Echec de la mise a jour de produit. '. mysqli_error($conn)
			);
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}

?>