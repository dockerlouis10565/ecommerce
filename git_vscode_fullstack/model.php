<?php
 
function getCn(){
    static $cn;
    if(!$cn){
        $dsn = 'mysql:host=localhost;dbname=ecommerce_db;charset=utf8mb4';
        $user = 'root';
        $password = '';
        try {
            $cn= new PDO($dsn, $user, $password); 
            $cn->SET_ATTRIBUTE(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $cn;
        }
        catch(EXCEPTION $e){
            echo "error: $e";
            return null;
        }
    }
}
function fetchproduct()


    
