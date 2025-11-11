<?php
#require_once __DIR__ . '/../../vendor/autoload.php';

#$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
#$dotenv->load();

use GlobalPayments\Api\ServiceConfigs\Gateways\GpApiConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\PaymentMethods\CreditCardData;

function getCn() {
    static $pdo;
    if (!$pdo) {
        try {
            $pdo = new PDO("mysql:host=localhost;port=3306;dbname=ecommerce_db;charset=utf8mb4", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
            return null;
        }
    }
    return $pdo;
}
function isValidTable($table) {
    $allowed = ['footwear', 'clothing_and_accessories', 'beauty_and_skincare', 'logintable','household_items', 'occassional_items','stocks','purchases'];
    return in_array($table, $allowed);
}

function fetchproducts($table){
    if (!isValidTable($table) ) {
    echo ("Invalid table name.");
    exit;
    }
    if(in_array($table, ['logintable', 'stocks', 'purchases'])){
        echo ("Invalid table nam.");
        exit;
    }
    $fetchedproducts = getCn()->prepare("select route from $table");
    $fetchedproducts->execute();
    return $fetchedproducts->fetchAll(PDO::FETCH_COLUMN);
}
function fetchAproduct($table,$id,$typedata){       
    if (!isValidTable($table)) {
    echo ("Invalid table name.");
    exit;
    }
    
    $fetchedproduct=getCn()->prepare("select route from `$table` where id = :id and typedata=:typedata");
    $fetchedproduct->execute(["id"=>$id,"typedata"=>$typedata]);
    return $fetchedproduct->fetchAll(PDO::FETCH_COLUMN);
   
}
function fetchsimilarproducts($table,$arr){       
    if (!isValidTable($table)) {
    echo ("Invalid table name.");
    exit;
    }

    $fetchedproduct=getCn()->prepare("select route from `$table` where genre = :genre AND gender =:gender AND agegroup=:agegroup");
    $fetchedproduct->execute(["genre"=>$arr[0],"gender"=>$arr[1],"agegroup"=>$arr[2]]);
    return $fetchedproduct->fetchAll(PDO::FETCH_COLUMN);
}
function createtable($table){
    if (!isValidTable($table)) {
    echo ("Invalid table name.");
    exit;
    }
    if($table === 'logintable') {
        $stmt = getCn()->prepare("CREATE TABLE IF NOT EXISTS `$table`
        (
            id INT AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(100) NOT NULL,
            lastname VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )");
        $stmt->execute();

    } 
    else if($table === 'stocks' || $table === 'services') {
        return;
    }
    else {

        $stmt = getCn()->prepare("CREATE TABLE IF NOT EXISTS `$table` 
        (
        id INT AUTO_INCREMENT PRIMARY KEY,
        route VARCHAR(255) NOT NULL,
        type VARCHAR(100) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        genre VARCHAR(100) NOT NULL,
        gender VARCHAR(100) NOT NULL,
        agegroup VARCHAR(100) NOT NULL
        )");
        $stmt->execute();
    }
    return true;
}
function verifylogin($firstname,$lastname,$email, $password) {
    $stmt = getCn()->prepare("SELECT password FROM logintable WHERE email = :email and password =:password and firstname=:firstname and lastname=:lastname");
    $stmt->execute(["email" => $email,"password"=>$password,"firstname"=>$firstname,"lastname"=>$lastname]);
    $user = $stmt->fetch(PDO::FETCH_COLUMN);

    if (!empty($user)) {
        return true;
    }

    return false;
}
function logout($email){
    $stmt = getCn()->prepare("DELETE FROM logintable WHERE email = :email");
    $stmt->execute(["email" => $email]);
}
function verifyEntries($table,$columnName, $value) {
    $stmt = getCn()->prepare("SELECT id FROM `$table` WHERE `$columnName` = :value");
    $stmt->execute(['value' => $value]);
    $fetched= $stmt->fetch(PDO::FETCH_COLUMN);
    return !empty($fetched);
}
function registerUser( $firstname,$lastname,$email, $password) {
    $db = getCn();
    $stmt = $db->prepare("INSERT INTO logintable (firstname,lastname,email, password) VALUES (:firstname, :lastname, :email, :password)");
    $stmt->execute([":email"    => $email,":password" => $password,":firstname" => $firstname,":lastname" => $lastname]);
    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}
function showtables() {
    $pdo = getCn(); 
   
    if (!$pdo) {
    die("Database connection failed.");
    }

    $stmt = $pdo->query("SHOW TABLES");
    return $stmt->fetchAll(PDO::FETCH_COLUMN) ;
} 
function handleDetails($path){
     if(file_exists($path)){
                $_SESSION['id'] = $path;
            }else{
                $_SESSION['id'] = isset($_GET['id']) ? htmlspecialchars_decode(urldecode($_GET['id'])) : '';
            }
            echo $_SESSION['id'];
            //if(file_exists($var)){$_SESSION['id'] = $var;}
            //$path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SESSION['id']);
            //$arr = explode(DIRECTORY_SEPARATOR, $path);
            //echo $_SESSION['id'];
            $arr = (DIRECTORY_SEPARATOR === '/') ? explode('/', $_SESSION['id']) : explode('\\', $_SESSION['id']);
            $arr1 =array($arr[5],$arr[3],$arr[6]);
            $price = fetchProductDetails($arr[4], $_SESSION['id']);
            $_SESSION['similar_products'] = fetchsimilarproducts($arr[4],$arr1);
            $_SESSION['details'] = array( $arr[7],$price);
            //"C:\Data\mysql\male\footwear\official\teen_youngAdult\image (1).jpg"
            header("Location: ../frontend/detail.php");
}
function get_monitor_purchase_analysis(){
    $tables = showtables();
    $analysis = [];
    foreach ($tables as $table) {
        if ($table === 'logintable') continue; 
        $stmt = getCn()->prepare("SELECT COUNT(*) AS total_products, AVG(price) AS average_price FROM `$table`");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $analysis[$table] = [
                'total_products' => (int)$result['total_products'],
                'average_price' => (float)$result['average_price']
            ];
        }
    }
    return $analysis;
}
function getValidProductPaths() {
    $arr = showtables();
    $result = [];
    foreach ($arr as $table) {
        if (!in_array($table, ['logintable', 'stocks', 'purchases'])) {
            $products = fetchproducts($table);
            foreach ($products as $path) {
                if (file_exists($path)) {
                    $result[] = $path;
                }
            }
        }
    }

    return $result;
}


function fetchProductDetails($table, $route) {
    if (!isValidTable($table)) {
        echo ("Invalid table name.");
        exit;
    }

    $stmt = getCn()->prepare("SELECT price FROM `$table` WHERE route = :route");
    $stmt->execute(["route" => $route]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}
function deleteFalsefilepaths() {
    $tables = showtables();
    foreach ($tables as $table) {
        if (in_array($table, ['logintable', 'stocks', 'services','purchases'])) continue;
        $stmt = getCn()->prepare("SELECT route FROM `$table`");
        $stmt->execute();
        $paths = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($paths as $path) {
            if (!file_exists($path)) {
                $stmt = getCn()->prepare("DELETE FROM `$table` WHERE route = :route");
                $stmt->execute(["route" => $path]);
            }
        }
    }
    // If all paths exist, return true
    return true;
}
function record_purchase($a, $b) { 
    foreach ($a as $arr) {
        $stmt = getCn();
        if (!is_array($arr)) {
            continue; // Skip non-array elements
        }
        $insert = $stmt->prepare("INSERT INTO `purchases` (transaction_id, purchase_date, email, mobile_number, account_name, price, shipping_address, postal_code, payment_method, product_category, product_name, gender, genre, agegroup) VALUES (:transaction_id, :purchase_date, :email, :mobile_number, :account_name, :price, :shipping_address, :postal_code, :payment_method, :product_category, :product_name, :gender, :genre, :agegroup)");
        $insert->execute(["transaction_id"=>$b[8],"purchase_date"=>$b[6],"email"=>$b[0],"mobile_number"=>$b[3],"account_name"=>$b[4],"price"=>$b[7],"shipping_address"=>$b[1],"postal_code"=>$b[2],"payment_method"=>$b[5],"product_category"=>$arr[0],"product_name"=>$arr[4],"gender"=>$arr[1],"genre"=>$arr[2],"agegroup"=>$arr[3]]);
    }
}

function gather_stock_info($path){
    $reelpath = realpath($path);
    if ($reelpath && is_file($reelpath) && $reelpath !== "." && $reelpath !== "..") {
        #finding its path
        $parent = dirname($reelpath); 
        $split = explode(DIRECTORY_SEPARATOR, $parent);
        $array = array($split[4],$split[3],$split[5],$split[6]);
        $stmt = getCn();
        $fetched = $stmt->prepare("SELECT quantity FROM `stocks` WHERE product = :product AND gender = :gender AND genre = :genre AND agegroup = :agegroup");
        $fetched->execute(["product"=>$split[4],"gender"=>$split[3],"genre"=>$split[5],"agegroup"=>$split[6]]);
        $fetched= $fetched->fetch(PDO::FETCH_COLUMN);
        if (empty($fetched) && $fetched < 0) {
            $quantity = rand(10000,100000);
            $insert = $stmt->prepare("INSERT INTO `stocks` (product, gender, genre, agegroup, quantity) VALUES (:product, :gender, :genre, :agegroup, :quantity)");
            $insert->execute(["product"=>$split[4],"gender"=>$split[3],"genre"=>$split[5],"agegroup"=>$split[6],"quantity"=>$quantity]);
        }

    }else if($reelpath == '.' || $reelpath == '..' || !is_file($reelpath)){
        #do nothing
    }
    else{
       $dirpointer = opendir($reelpath);
       while (($repo = readdir($dirpointer)) !== false) {
            if ($repo === '.' || $repo === '..') continue;
            else{
                $subpath = $reelpath . DIRECTORY_SEPARATOR . $repo;
                $gathered = gather_stock_info($subpath); 
            }
        }
    }
}
function fillstocks() {
    $stmt = getCn();
    $validPaths = getValidProductPaths();

    foreach ($validPaths as $path) {
        $parts = (DIRECTORY_SEPARATOR === '/') ? explode('/', $path) : explode('\\', $path);
        if (!is_file($path)) {
            continue; // Skip malformed paths
        }

        $product = $parts[4];
        $gender = $parts[3];
        $genre = $parts[5];
        $agegroup = $parts[6];

        $fetched = $stmt->prepare("SELECT * FROM `stocks` WHERE product = :product AND gender = :gender AND genre = :genre AND agegroup = :agegroup");
        $fetched->execute(["product" => $product,"gender" => $gender,"genre" => $genre,"agegroup" => $agegroup]);
        $rows = $fetched->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) === 0) {
            $newQty = rand(10000, 100000);
            $insert = $stmt->prepare("INSERT INTO `stocks` (product, gender, genre, agegroup, quantity) VALUES (:product, :gender, :genre, :agegroup, :quantity)");
            $insert->execute(["product" => $product,"gender" => $gender,"genre" => $genre,"agegroup" => $agegroup,"quantity" => $newQty]);
        } 
        else {
            $update = $stmt->prepare("UPDATE `stocks` SET quantity = quantity + 1 WHERE product = :product AND gender = :gender AND genre = :genre AND agegroup = :agegroup");
            $update->execute(["product" => $product,"gender" => $gender,"genre" => $genre,"agegroup" => $agegroup]);
               
        }
    }
}

#product, gender, genre, agegroup, quantity
function getStockData(){
    $stmt = getCn()->prepare("SELECT * FROM `stocks`");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function update_stocks($data) {
    if (empty($data)) return;
    foreach ($data as $item) {
        $stmt = getCn();
        $update = $stmt->prepare("UPDATE `stocks` SET quantity = quantity - 1 WHERE product = :product AND gender = :gender AND genre = :genre AND agegroup = :agegroup");
        $update->bindParam(':product', $item[0]);
        $update->bindParam(':gender', $item[1]);
        $update->bindParam(':genre', $item[2]);
        $update->bindParam(':agegroup', $item[3]);
        $update->execute();
    }
}
function fetchspecificData($gender, $genre, $agegroup, $id) {
    $table = $id;
    if (!isValidTable($table)) {
        echo ("Invalid table name.");
        exit;
    }

    $stmt = null;

    if (!empty($gender) && !empty($genre) && !empty($agegroup)) {
        $stmt = getCn()->prepare("SELECT route FROM `$table` WHERE gender = :gender AND genre = :genre AND agegroup = :agegroup");
        $stmt->execute(["gender" => $gender, "genre" => $genre, "agegroup" => $agegroup]);
    } elseif (!empty($gender) && !empty($genre)) {
        $stmt = getCn()->prepare("SELECT route FROM `$table` WHERE gender = :gender AND genre = :genre");
        $stmt->execute(["gender" => $gender, "genre" => $genre]);
    } elseif (!empty($gender)) {
        $stmt = getCn()->prepare("SELECT route FROM `$table` WHERE gender = :gender");
        $stmt->execute(["gender" => $gender]);
    } else {
        return [];
    }

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function uploadFiles($path) {
    $reelpath = realpath($path);
    if (!$reelpath || !is_dir($reelpath)) return;

    $dirpointer = opendir($reelpath);
    while (($repo = readdir($dirpointer)) !== false) {
        if ($repo === '.' || $repo === '..') continue;

        $subpath = $reelpath . DIRECTORY_SEPARATOR . $repo;
        if (is_dir($subpath)) {
            uploadFiles($subpath); 
        } elseif (is_file($subpath)) {
            $filepath = realpath($subpath);
            $type = mime_content_type($filepath);
            $parts = explode(DIRECTORY_SEPARATOR, $filepath);

            $gender    = $parts[3] ?? 'unknown';
            $table     = $parts[4] ?? 'unknown';
            $genre     = $parts[5] ?? 'unknown';
            $agegroup  = $parts[6] ?? 'unknown';
            $price     = rand(1,4);
            //"C:\Data\mysql\male\footwear\official\teen_youngAdult\image (1).jpg"
            if (isValidTable($table) && verifyEntries($table, 'route', $filepath)=== false) {
                
                $stmt = getCn()->prepare("INSERT INTO `$table` (route, type, price, genre, gender, agegroup) VALUES (:route, :type, :price, :genre, :gender, :agegroup)");
                $stmt->execute(["route"   => $filepath,"type"    => $type,"price"   => $price,"genre"   => $genre,"gender"  => $gender,"agegroup"=> $agegroup]);
            }
        }
    }
}
function credit_card_payment($arr){
    #This is for testing purposes only.
    return [
            'success' => true,
            'transaction_Id' => "HT7hs09929-uus000-9",
            'status' => "approved",
            'cardLast4' => "1234",
            'fraudResult' => "no_fraud",
            'amount'=> $arr['price'],
            'client_email'=>$arr['email'],
            'client_name'=>isset($arr['account_name']) ? $arr['account_name'] : $_SESSION['user']['firstname'].' '.$_SESSION['user']['lastname']
        ];
    #End of testing purposes only.
/*
$config = new GpApiConfig();
$config->appId = getenv('GP_APP_ID');
$config->appKey = getenv('GP_APP_KEY');
$config->channel = Channel::CardNotPresent;
$config->methodNotificationUrl = "https://www.example.com/methodNotificationUrl";
$config->challengeNotificationUrl = "https://www.example.com/challengeNotificationUrl";
$config->merchantContactUrl = "https://www.example.com/about";
$config->country = 'MA';
$config->environment = Environment::TEST;#   change to Environment::LIVE or Environment::PRODUCTION for real-life card processing
$config->requestLogger = new SampleRequestLogger(new Logger("logs"));

ServicesContainer::configureService($config);

$card = new CreditCardData();
$card->number = $arr['bank_account'];
$arr1 = explode('/', $arr['expiry_date']);
$card->expMonth = $arr1[1];
$card->expYear = $arr1[2];
$card->cardHolderName = $arr['account_name'];
$card->cvn = $arr['cvc'];
#$address = new Address();
#$address->streetAddress1 = $arr['shipping_address'] ;
#$address->postalCode = $arr['postal_code'];
$transactionId = date('YmdHis') . '_' . bin2hex(random_bytes(4));
$arr2 = explode(',', $arr['shipping_address']);
$address = (new Address())
    ->withStreet($arr2[0] ?? '')
    ->withCity($arr2[1] ?? '')
    ->withPostalCode($arr['postal_code'])
    ->withCountry("MA") 
    ->withState($arr2[2] ?? '');



$transactionId = $response->transactionId; // API raw response key "id"
$amount = $response->authorizedAmount; // API raw response key "amount"
//API raw response key "batch_id"
$batchId = $response->batchSummary->batchReference;
//API raw response key "type"
$transactionType = $response->originalTransactionType;
$referenceNumber = $response->referenceNumber; // API raw response key "reference"
$transactionStatus = $response->responseMessage;// API raw response key "status"
$timeCreated = $response->timestamp; // API raw response key "time_created"
$responseCode = $response->responseCode; // API raw response key "action->result_code"
//API raw response key "payment_method->card->brand_reference"
$cardBrandReference = $response->cardBrandTransactionId;
// API raw response key "payment_method->card->authcode"
$authCode = $response->authorizationCode;
//API raw response key "payment_method->card->avs_postal_code_result"
$avsResponseCode = $response->avsResponseCode;
//API raw response key "payment_method->card->->avs_address_result"
$avsAddressResponse  = $response->avsAddressResponse;
// API raw response key "payment_method->card->cvv_result"
$cvnResponseMessage = $response->cvnResponseMessage;
// API raw response key "payment_method->card->brand";
$cardType = $response->cardDetails->brand;
// API raw response key "payment_method->card->masked_number_last4"
$maskedNumberLast4 = $response->cardDetails->maskedNumberLast4;
//API raw response key "payment_method->card->provider->result"
$cardIssuerResult = $response->cardIssuerResponse->result;
//API raw response key "payment_method->card->provider->cvv_result"
$cardIssuerCvv = $response->cardIssuerResponse->cvvResult;
//API raw response key "payment_method->card->provider->avs_address_result"
$cardIssuerAvsAddressResult = $response->cardIssuerResponse->avsAddressResult;
//API raw response key "payment_method->card->provider->avs_postal_code_result"
$cardIssuerAvsPostalCodeResult = $response->cardIssuerResponse->avsPostalCodeResult;
//API raw response key "risk_assessment->mode"
$fraudResponseMode = $response->fraudFilterResponse->fraudResponseMode;
//API raw response key "risk_assessment->result"
$fraudResponseResult = $response->fraudFilterResponse->fraudResponseResult;
//API raw response key "risk_assessment->rules"
$fraudResponseRules = $response->fraudFilterResponse->fraudResponseRules;
try {
        $response = $card->charge($arr['price'])
            ->withCurrency($arr['currency'] ?? 'MAD')
            ->withClientTransactionId($transactionId)
            ->withAddress($address)
            ->execute();

        return [
            'success' => true,
            'transaction_Id' => $response->transactionId,
            'status' => $response->responseMessage,
            'cardLast4' => $response->cardDetails->maskedNumberLast4,
            'fraudResult' => $response->fraudFilterResponse->fraudResponseResult,
            'amount'=> $arr['price'],
            'client_email'=>$arr['email'],
            'client_name'=>isset($arr['account_name']) ? $arr['account_name'] : $_SESSION['user']['firstname'].' '.$_SESSION['user']['lastname']
        ];
} catch (ApiException | GatewayException $ex) {
        error_log("Payment error: " . $ex->getMessage());
        return ['success' => false, 'error' => $ex->getMessage()];
}
#$gather_credentials[] = "C:\Data\mysql\female\beauty_and_skincare\casual\teen_youngAdult\image.jpeg";
}
function update_stocks($arr){
   if(empty($arr)) return;
   foreach($arr as $item) {
      $stmt = getCn();
      $update = $stmt->prepare("UPDATE `stocks` SET quantity = quantity - 1 WHERE product = :product AND gender = :gender AND genre = :genre AND agegroup = :agegroup");
      $update->bindParam(':product', $item[0]);
      $update->bindParam(':gender', $item[1]);
      $update->bindParam(':genre', $item[2]);
      $update->bindParam(':agegroup', $item[3]);
      $update->execute();
   }
*/
}

function process_payment($payment_credentials){
    if($payment_credentials['payment_method'] === 'credit_card'){
       return credit_card_payment($payment_credentials);
    }
}

# (1) public methods can be called even without INSTANTIATION of the class-even if the are to be called within the same class DECLARATION.
#       public property or method can be accessed from outside the class declaration using an instance B of the class A.
# (2) static methods can be called without INSTANTIATION of the class. 
#    an instance B of class A can call properties or methods of class A outside(allowed but not recommended) or within the class A declaration.
# (3) private methods can only be called within the class declaration.
#           an instance B of the class A can only call a private method or property of class A if the instance B is within the class A declaration.
#           private property or method can be called outside of the class declaration through its instance B if there was another public or static method within the class A declaration that calls that private method or property.
# (4) protected methods can only be called within the class declaration or by an instance of a subclass of the class.
#           an instance B of the class A can only call a protected method or property of class A if the instance B is within the class A declaration or within a subclass of class A.
#           OR if the was another public or static method within the class A declaration that calls the protected method or property.
# (5) static methods can call public, private or protected methods or properties within the class declaration.
# (6) public methods can call public, private or protected methods or properties within the class declaration.
# (7) private methods can call public, private or protected methods or properties within the class declaration.
