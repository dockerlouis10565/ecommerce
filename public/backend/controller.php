<?php
session_start();
$_SESSION["display_favorite"]=false;
require('model.php');
$act = isset($_GET['act'])? $_GET['act']      : '';
$password = isset($_POST['password']) ? $_POST['password'] : " ";
$email=isset($_POST['email']) ? $_POST['email'] : " ";
$firstname=isset($_POST['firstname']) ? $_POST['firstname'] : " ";
$lastname=isset($_POST['lastname']) ? $_POST['lastname'] : " ";
$_SESSION["user"] = [
    "firstname" => $firstname,
    "lastname"  => $lastname,
    "email"     => $email,
    "password"  => $password
];
#"C:\Data\mysql\female\beauty_and_skincare\casual\teen_youngAdult\image.jpeg"
 $_SESSION["display_favorite"]=false;
switch ($act) {
        case "signup":
            setcookie("user", json_encode($_SESSION["user"]), time() + 3600*24, "/");
            header("Location: ../frontend/login.php?email=" . urlencode($email) . "&password=" . urlencode($password));
            break;
            exit;
        case "login":
            $tables = showtables();
            /*foreach ($tables as $table) {    
                createtable($table);
            }*/
            deleteFalsefilepaths();
            fillstocks();
            
            
            $_SESSION["user"] = isset($_COOKIE["user"]) ? json_decode($_COOKIE["user"], true) : [];
            setcookie("user", json_encode($_SESSION["user"]), time() + 63244800, "/");
            if($_SESSION["user"]["email"] === $email && $_SESSION["user"]["password"] === $password){
                if(!verifylogin($_SESSION["user"]["firstname"],$_SESSION["user"]["lastname"],$email,$password)){
                    registerUser($_SESSION["user"]["firstname"],$_SESSION["user"]["lastname"],$_SESSION["user"]["email"], $_SESSION["user"]["password"]); 
                }
            }
            else{
                $_SESSION["login_error"] = "Invalid email or password.";
                header("Location: ../frontend/login.php?error=" . urlencode($_SESSION["login_error"]));
                exit;
            }
            $dir = [];
            uploadFiles("C:/Data/mysql");
            #registerUser($email, $password);
            $result = getValidProductPaths();
            $_SESSION['tables'] = showtables();
            
            $_SESSION['product_paths'] = getValidProductPaths();
            header("Location: ../frontend/productcategory.php");
            exit;
            
        case "getUser":
            return json_encode($_SESSION["user"]);
            break;
        case "fetch_favorite":
            $_SESSION['favorite'] = isset($_COOKIE['favorite']) ? $_COOKIE['favorite'] : [];
            $_SESSION["display_favorite"] = true;
            header("Location: ../frontend/favorites.php");
            break;
        case "fetch_cookie":
            $_SESSION['favorite'] = isset($_COOKIE[$_SESSION["user"]["firstname"]]) ? json_decode($_COOKIE[$_SESSION["user"]["firstname"]], true) : [];
            if(empty($_SESSION['favorite'][0])) {
                array_splice($_SESSION['favorite'], 0,1);
            }
            $_SESSION["display_favorite"] = true;
            header("Location: ../frontend/productcategory.php");
            break;
        case "FavoriteAndCart":
                $act = $_POST['Name'] ?? '';
                $id = isset($_GET['id']) ? $_GET['id'] : '';
                $path=htmlspecialchars_decode(urldecode($id));
              
                if ($act === 'Add_to_cart' || $act === 'to_cart') {
                    if(!isset($_COOKIE["cart"])) {
                        setcookie("cart", json_encode(array($path)), time() + (86400 * 30), "/"); // 86400 = 1 day
                    }else{
                        if(!in_array($path, json_decode($_COOKIE["cart"], true))) {
                            $cart = json_decode($_COOKIE["cart"], true);
                            $cart[] = $path;
                            foreach($cart as $index => $item) {
                                if(!file_exists($item)) {
                                    unset($cart[$index]);
                                }
                            }
                            $cart = array_values($cart);
                            unset($_COOKIE["cart"]);
                            setcookie("cart", json_encode($cart), time() + (86400 * 30), "/");
                        }
                    }
                } else if ($act === 'delete' || $act === 'delete_favorite') {
                    if(isset($_COOKIE['favorite']) && isset($_GET['boolean']) && $_GET['boolean'] === 'true') {
                        $favorite = json_decode($_COOKIE['favorite'], true);
                        if(($key = array_search($path, $favorite)) !== false) {
                            unset($favorite[$key]);
                            $favorite = array_values($favorite);
                            unset($_COOKIE["favorite"]);
                            setcookie("favorite", json_encode($favorite), time() + (86400 * 30), "/");
                        }
                    }else if(isset($_COOKIE['cart'])) {
                        $cart = json_decode($_COOKIE['cart'], true);
                        if(($key = array_search($path, $cart)) !== false) {
                            unset($cart[$key]);
                            $cart = array_values($cart);
                            unset($_COOKIE["cart"]);
                            setcookie("cart", json_encode($cart), time() + (86400 * 30), "/");
                        }
                    }

                } else if($act === 'details'){

                    handleDetails($path);
                    exit;
                }
                else  {
                    if(!isset($_COOKIE['favorite'])) {
                        setcookie("favorite", json_encode(array($path)), time() + (86400 * 30), "/"); // 86400 = 1 day
                    }else{
                        if(!in_array($path, json_decode($_COOKIE['favorite'], true))) {
                            $favorite = json_decode($_COOKIE['favorite'], true);
                            $favorite[] = $path;
                            foreach($favorite as $index => $item) {
                                if(!file_exists($item)) {
                                    unset($favorite[$index]);
                                }
                            }
                            $favorite = array_values($favorite);
                            unset($_COOKIE["favorite"]);
                            setcookie("favorite", json_encode($favorite), time() + (86400 * 30), "/");
                        }
                    }
                    
                }
            $_SESSION['id'] = $path;
            header("Location: ../frontend/detail.php");
            exit;
        case "details":
            $path =$_GET['id'];
            handleDetails($path);
            exit;
        case "search":
            
        case "fetch_cart":
            $_SESSION['cart'] = isset($_COOKIE['cart']) ? $_COOKIE['cart'] : [];
            $_SESSION["display_cart"] = true;
           
            $total=0;
            $realcarts = json_decode($_SESSION["cart"], true);
            foreach($realcarts as $key => $cart) {
                if(file_exists($cart)) {
                    $arr= (DIRECTORY_SEPARATOR === '/') ? explode('/', $cart) : explode('\\', $cart);
                    //$arr = array_map('trim', $arr);
                    if(isValidTable($arr[4])) {
                    echo "<br>" . $cart . "<br>";
                    $total += fetchProductDetails($arr[4], $cart);
                    }
                    
                }
            }
            echo $total;
            $_SESSION['total'] = $total;
            echo $_SESSION['total'];
            $_SESSION["display_cart"] = true;
            header("Location: ../frontend/cart.php");
            exit;    
        case "update_profile":
            $callerDetail = $_GET['callerDetail'] ;
            $loc = explode("/", $callerDetail);
            // callerDetail ,when tested on xampp, will output   /edsa-fullstack/frontend/cart.php
            $path=  $loc[count($loc)-2]."/".$loc[count($loc)-1];
            //$_SESSION["user"] = isset($_COOKIE["user"]) ? $_COOKIE["user"] : [];
            
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $userData = json_decode($_COOKIE["user"], true);
                $userData["name"] = $_POST["name"];
                $userData["email"] = $_POST["email"];
                setcookie("user", json_encode($userData), time() + 63244800, "/");
                $_SESSION["user"] = $userData;
                $_SESSION['updated_credentials'] = true;
                header('Location: ../' . $path);
                exit;
            }
            break;
        case "checkout":
            header("Location: ../frontend/checkout.php?price=" . urlencode($_GET['price']));
            exit;
        case "process_payment":
            $price = isset($_GET['price']) ? $_GET['price'] : 0.00;
           
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $payment_credentials = [];
                $payment_credentials['price'] = $price;
                $payment_credentials['payment_method'] = $_POST["payment_method"];
                $payment_credentials['shipping_address'] = $_POST["shipping_address"];
                $payment_credentials['postal_code'] = $_POST["postal_code"];
                $payment_credentials['cvc'] = $_POST["cvc"];
                $payment_credentials['expiry_date'] = $_POST["expiry_date"];
                $payment_credentials['coupon_code'] = $_POST["coupon_code"];
                $payment_credentials['bank_account'] = $_POST["bank_account"];
                $payment_credentials['mobile_number'] = $_POST["mobile_number"];
                $payment_credentials['email'] = $_POST["email"];
                $payment_credentials['account_name'] = $_POST["account_name"];
                $arr = process_payment($payment_credentials);
                if (!$arr['success']) {
                    $_SESSION['payment_error'] = $arr['error'];
                    header("Location: ../frontend/productcategory.php?price=" . urlencode($price) . "&error=" . urlencode($_SESSION['payment_error']));
                    exit;
                }else{
                    $_SESSION['payment_status'] = "Payment of $price MAD via {$payment_credentials['payment_method']} was successful.";
                    $cart = isset($_COOKIE["cart"]) ? json_decode($_COOKIE["cart"], true) : [];
                    $arrf = [];
                    $array_stocks = [];
                    foreach($cart as $item) {
                        $arr0 = (DIRECTORY_SEPARATOR === '/') ? explode('/', $item) : explode('\\', $item);
                        $array_stocks[] = array($arr0[4],$arr0[3],$arr0[5],$arr0[6]);
                        $arrf[] = array($arr0[4],$arr0[3],$arr0[5],$arr0[6],$arr0[7]);
                    }
                    
                    #$split = isset($_COOKIE["cart"]) ? json_decode($_COOKIE["cart"], true) : [];
                    $gather_credentials = array($payment_credentials['email'], $payment_credentials['shipping_address'], $payment_credentials['postal_code'], $payment_credentials['mobile_number'], $payment_credentials['account_name'], $payment_credentials['payment_method'],date('Y-m-d H:i:s'),$price,$arr['transaction_Id']);
                    record_purchase($arrf, $gather_credentials);
                    update_stocks($array_stocks);
                    #$gather_credentials[] = "C:\Data\mysql\female\beauty_and_skincare\casual\teen_youngAdult\image.jpeg";
                    unset($_COOKIE["cart"]);
                    setcookie("cart", "", time() - 3600, "/"); 
                    $_SESSION["cart"] = [];
                    $_SESSION["total"] = 0.00;
                    header("Location: ../frontend/productcategory.php");
                }
            }
            break;
        case "edit_profile":
            $callerDetail = $_GET['callerDetail'] ;
            $_SESSION["user"] = isset($_COOKIE["user"]) ? $_COOKIE["user"] : [];
            header("Location: ../frontend/editprofile.php?callerDetail=" . urlencode($callerDetail));
            exit;
        case "logout":
            $_SESSION["user"] = isset($_COOKIE["user"]) ? json_decode($_COOKIE["user"], true) : [];
            logout($_SESSION["user"]["email"]);
            setcookie("user", "", time() - 3600, "/");
            setcookie("cart", "", time() - 3600, "/");
            setcookie("favorite", "", time() - 3600, "/");
            unset($_COOKIE);
            session_unset();
            session_destroy();
            header("Location: ../frontend/category.php");
            exit;
        case "fetchspecificData":
            $gender = isset($_GET['gender']) ? $_GET['gender'] : '';
            $genre = isset($_GET['genre']) ? $_GET['genre'] : '';
            $agegroup = isset($_GET['agegroup']) ? $_GET['agegroup'] : '';
            $table = isset($_GET['id']) ? $_GET['id'] : ''; 
            
            $result = getValidProductPaths();
            $_SESSION['tables'] = showtables();
            $_SESSION['product_paths'] = fetchspecificData($gender, $genre, $agegroup, $table);
            header("Location: ../frontend/productcategory.php");
            exit;
        case "getStockData":
            $data = getStockData();
            header('Content-Type: application/json');
            echo json_encode($data);
            exit;
        default:
            $_SESSION['product_paths'] = fetchproducts($act);
            header("Location: ../frontend/productcategory.php");
            exit;        
}
exit;
?>





