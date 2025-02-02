<?php 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
require './config.php';

$method = $_SERVER['REQUEST_METHOD'];

function query_db() {
    global $conn;
    $q_string = "SELECT * from users";
    $result = $conn->query($q_string);

    if($result->num_rows)
    {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    else 
    { 
        return "No data found";
    }

}

function post_db() {
    global $conn;
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'];
    $email = $input['email'];
    $gender = $input['gender'];
    $dob = $input['dob'];

    try 
    {
        $q_string = "INSERT INTO users (username, email, gender, dob) values (?, ?, ?, ?)";
        $q_stmt = $conn->prepare($q_string);
        $q_stmt->bind_param('ssss', $username, $email, $gender, $dob);
        $q_stmt->execute();
        return true;
    }
    catch(Exception $e) 
    {
        echo $e;
        return false;
    }

}

if ($method === 'OPTIONS')
{
    http_response_code(204);
    exit;
}



switch ($method) {
    case 'GET':
        $res = query_db();
        break;
    case 'POST':
        if (post_db())
        {
            $res = ['status'=> 'success', 'message'=>'user added'];
        }
        else 
        {
            $res = ['status'=> 'failed', 'message'=>'invalid request'];
        }
        break;
    default:
            $res = ['status'=> 'failed', 'message'=>'Unsupported request method'];
    }
    echo json_encode($res);
?>