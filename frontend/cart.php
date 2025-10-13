<?php

session_start();



#$arr =isset($_GET['arr'])?urldecode($_GET['arr']) : '';
#$result = json_decode($arr,true);
#$img=[]; 

?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Security-Policy" >

        <script>
            const images = ['images/bg1.jpg', 'images/bg2.jpg', 'images/bg3.jpg', 'images/bg4.jpg', 'images/bg5.jpg', 'images/bg6.jpg'];
            let index = 0;
        </script>
       
        <script src="productcategory.js" defer></script>
        <style>
            @import url('cartdesign.css');
        </style>
        <link rel="stylesheet" href="cartdesign.css">
        <!-- Bootstrap CSS -->
        <!--<link href="design.css" rel="stylesheeet">-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap JavaScript (optional, for interactive components) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <header > 
            <div class="headerdiv"> 
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> product categories </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../api/controller.php?act=beauty_and_skincare">beauty and skincare</a></li>
                        <li><a class="dropdown-item" href="../api/controller.php?act=clothing_and_accessories">clothing and accessories </a></li>                       
                        <li><a class="dropdown-item" href="../api/controller.php?act=footwear">footwear</a></li>
                        <li><a class="dropdown-item" href="../api/controller.php?act=household_items">household items</a></li>
                        <li><a class="dropdown-item" href="../api/controller.php?act=occassional_items">occassional items</a></li>
                    </ul>
                </div>
                <input type="search" name="search" placeholder="search" style="height: 95%; border-radius: 10px;" >
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> settings </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">theme</a></li>
                        <li><a class="dropdown-item" href="#"> client preferrences</a></li>
                        <li><a class="dropdown-item" href="../api/controller.php?act=fetch_cookie">cart </a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> client info </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../backend/controller.php?act=edit_profile">edit/view profile </a></li>
                        <li><a class="dropdown-item" href="../backend/controller.php?act=fetch_cart"> client cart</a></li>
                        <li><a class="dropdown-item" href="../backend/controller.php?act=fetch_favorite"> client favorite</a></li>
                        <li><a class="dropdown-item" href="../backend/controller.php?act=logout">logout</a></li>
                    </ul>
                </div>
            </div>
            <div id="display"></div>
        </header>
        <section> 
            
        </section>
        <main>
            <div class="maindiv">
            <div class="aArea" style='color: green;font-weight: bold;'>  
                Total price to pay: <?php echo (isset($_SESSION["total"]) ? $_SESSION["total"] : 0.00); ?>
                <form method="POST" action="../backend/controller.php?act=checkout&price=<?php echo (isset($_SESSION["total"]) ? $_SESSION["total"] : 0.00); ?>" style="display: inline;">
                    <input type="submit" name="checkout" value="checkout" style="background-color: white; border: 1px solid black; border-radius: 5px;margin: 0 px; margin-left: 40px; padding: 5px 10px; cursor: pointer;">
                </form>
            </div>
            
            <div class="bArea" style='color: green;font-weight: bold;'>  Total items in cart: <?php echo (isset($_SESSION["cart"]) ? count(json_decode($_SESSION["cart"], true)) : 0); ?>
            <div class="row row-cols-1 row-cols-md-5 g-4" style="display: flex; flex-flow: row wrap; justify-content: center; align-items: flex-start; width: 100%;">
                <?php
                
                if (isset($_SESSION["cart"]) && $_SESSION["display_cart"] === true) {
                    $result = isset($_SESSION["cart"]) ? json_decode($_SESSION["cart"], true) : [];
                    if(empty($result)){
                        echo "<div style='color:red;'>No items in cart yet.</div>";
                    }else{
                        echo "<br><br><br>";
                        var_dump($result);
                        foreach ($result as $path) {

                            $fullPath = realpath($path);
                            
                            if ($fullPath && file_exists($fullPath)) {
                                $mime = mime_content_type($fullPath);
                                $data = base64_encode(file_get_contents($fullPath));
                                echo "<div class='col'>
                                    <div class='card'>
                                        <img src='data:$mime;base64,$data' id='$fullPath' class='card-img-top' style='width: 98%; margin: 3px;height: 150px;object-fit: fill;' />
                                        <div class='card-body'>
                                            <form method='POST' action='../backend/controller.php?act=FavoriteAndCart&id=".urlencode(htmlspecialchars($fullPath))."' style='display: flex; flex-flow: row nowrap; height:auto;width:95%; padding: 1px; justify-content: space-evenly;  object-fit:contain;'>
                                                <input type='submit' name='Name' class='btn btn-primary' style='background-color: black;  border: 0px;padding: 0px;width: 30%;' value='delete'>
                                                <input type='submit' name='Name' class='btn btn-primary' style='background-color: black;  border: 0px;padding: 0px;width: auto;' value='to_cart'>
                                                <input type='submit' name='Name' class='btn btn-primary' style='background-color: black;  border: 0px;padding: 0px;width: 30%;' value='details'>
                                            </form>
                                        </div>
                                    </div>
                                </div>";
                            } else {
                                echo "<div style='color:red;'>Image not found: $path</div>";
                            }

                        }
                    }
                    
                }
            ?>
            </div>
            </div>
            </div>
            </div>
            <div>
                
            </div> 
        </main>
        <footer></footer>
        <!--<script src="productcategoryjs.js"></script>-->
    </body>
</html> 