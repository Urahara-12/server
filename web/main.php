<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // 3shan ay 7ad yaccess backend, using js preflight request parameters to know what kind of requests i can send
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    die(); // end of script and send to client
}
// js will then send get/post
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // respond with content type json

//$conx = mysqli_connect('db4free.net', 'youssef_database', 'password', 'youssef_database'); // establish connection with db
$conx = mysqli_connect('localhost', 'root', '', 'app'); // establish connection with db
if (!$conx) {
    http_response_code(500);
    echo json_encode(['details' => mysqli_connect_error()]);
    mysqli_close($conx);
    die(); //If database connection fails then the whole script is aimles so it should
}          // just die

function xorCrypt($string) {
    $key = ('foreignswedishpenis');
    for($i = 0; $i < strlen($string); $i++)
        $string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
    return $string;
}

$req_uri = strtok($_SERVER["REQUEST_URI"], '?'); // returns value after /, strtok will spilt anything until it reaches questionmark
$resp_data = array(); // will contains response data {id,token,details,etc}

switch ($req_uri) {
    case '/register/':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req_data = json_decode(file_get_contents("php://input"), true); // Sprint f used for formatting, true turns json to php object
            $sql = sprintf('select *
                              from users
                             where email="%s"',
                           $req_data['email']);
            $result = mysqli_query($conx, $sql); // if email alrdy exists
            if (mysqli_num_rows($result)) {
                $resp_data['details'] = 'Email Already Used';
                http_response_code(400);
            } else {
                $sql = sprintf(
                    'insert into users (name, email, password, registration_date)
                     values ("%s", "%s", "%s", "%s");',
                    $req_data['name'],
                    $req_data['email'],
                    password_hash($req_data['password'], PASSWORD_DEFAULT),
                    date('Y-m-d') // format supported by mysql
                );
                $result = mysqli_query($conx, $sql); // if query was successful, data was inserted
                if ($result) {
                    $resp_data['token'] = base64_encode(xorCrypt(json_encode(['id' => mysqli_insert_id($conx)])));
                } else { // if error, will return error that happened in mysql databse
                    $resp_data['details'] = mysqli_error($conx);
                    http_response_code(500);
                }
            }
        } else {
            $resp_data['details'] = 'Method Not Allowed';
            http_response_code(405);
        }

        break;
    case '/login/': // In http after methods POST, PUT, PATCH, we add a "/"
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $req_data = json_decode(file_get_contents("php://input"), true); // get php request content from memory, when true parameter is passed, associative array data type is returned
            $sql = sprintf('select id
                                   , password
                              from users
                             where email="%s"',
                           $req_data['email']);
            $result = mysqli_query($conx, $sql);
            $user_data = mysqli_fetch_assoc($result);
            if (mysqli_num_rows($result)  // Condition checks if email exists
                and password_verify($req_data['password'], $user_data['password'])) {
                $resp_data['token'] = base64_encode(xorCrypt(json_encode(['id' => (int)$user_data['id']])));
            } else { // if email doesnt exist
                $resp_data['details'] = 'Wrong Email or Password';
                http_response_code(401);
            }
        } else {
            $resp_data['details'] = 'Method Not Allowed';
            http_response_code(405);
        }
        break;
    case '/user':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $user = json_decode(xorCrypt(base64_decode($_SERVER['HTTP_AUTHORIZATION'])), true);
            $sql = sprintf('select id
                                   , name
                                   , email
                                   , registration_date
                              from users
                             where id="%s"',
                           $user['id']);
            $result = mysqli_query($conx, $sql);
            $resp_data = mysqli_fetch_assoc($result);
        } else {
            $resp_data['details'] = 'Method Not Allowed';
            http_response_code(405);
        }
        break;
    case '/email_checker':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            parse_str($_SERVER['QUERY_STRING']); // assigns query string value to query property 
            $sql = sprintf('select *
                              from users
                             where email="%s"',
                           $email);
            $result = mysqli_query($conx, $sql);
            if (!mysqli_num_rows($result)) { // if count=0
            
                http_response_code(404);
                $resp_data['details'] = 'Not Found';
            }
        } else {
            $resp_data['details'] = 'Method Not Allowed';
            http_response_code(405);
        }
        break;
    case '/departments':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($_SERVER['HTTP_AUTHORIZATION']) {
                $sql = 'select * from depts';
                $result = mysqli_query($conx, $sql);
                if (mysqli_num_rows($result)) {
                    $resp_data = mysqli_fetch_all($result, MYSQLI_ASSOC); // associative array is a data type that can be json encoded                
                }
            } else {
                $resp_data['details'] = 'Unauthorized';
                http_response_code(401);
            }
        } else {
            $resp_data['details'] = 'Method Not Allowed';
            http_response_code(405);
        }
        break;
    case '/admin': // remote admin on remote db  
        header('Location: https://www.db4free.net/phpMyAdmin/index.php');
        die();
    default:
        http_response_code(404);
        $resp_data['details'] = 'Not Found';
        break;
}


$json = json_encode($resp_data); // encode php object to json string
if ($json === false) { // error in encoding
    $json = json_encode(['details' => json_last_error_msg()]);
    http_response_code(500);
    if ($json === false) { // if json encoding again has error , will return json without encoding
        $json = '{"details": "Internal Server Error"}';
    }
}
echo $json;
mysqli_close($conx);
?>

