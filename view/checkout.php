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
        <style>
       html,body {
display: grid;
grid-template-areas:
    "header header header"
    "section main main"
    "footer footer footer";
grid-template-columns: 1fr 9fr 1fr;
grid-template-rows: 2fr 10fr 3fr;
height: 100vh;
width: 100vw;
margin: 0;
}
header {
display: flex;
flex-flow: column nowrap;
grid-area: header;
justify-content: space-around;
align-items: center;
border: 2px solid brown;
}
header .headerdiv {
display: flex;
flex-flow: row nowrap;
justify-content: space-evenly;
align-items: center;
width: 90%;
min-height: 40px;
margin: 3px 15px;
border: 1px solid black;
border-radius: 5px;
}
header .headerdiv .dropdown {
display: flex;
align-items: center;
justify-content: space-evenly;
height: 80%;
min-width: 40px; 
border-radius: 5px;
border: 0px solid blue;
}
section {
display: grid;
grid-area: section;
border: 5px solid green;
}
main  {
display: flex;
flex-flow: row nowrap;
justify-content: center;
align-items: center;
grid-area: main;
border: 5px solid yellow;
}
main .maindiv {
width: 80%;
display: grid;
grid-template-areas:
    "a b"
    ;
grid-template-columns: 2fr 8fr;
gap: 10px;
margin: auto;
height:auto;
width: 100%;
border: 1px solid green;
}
main .maindiv .aArea {
grid-area: a;
border: 1px solid green;
height: auto;
}
main .maindiv .bArea {
grid-area: b;
grid-template-areas:
    "b1"
    "b2"
    "b3"
    ;
grid-template-columns: auto auto auto;
border: 1px solid red;
height: auto;
}
main .maindiv .bArea .b1 {
grid-area: b1;
border: 1px solid blue;
text-align: center;
}
main .maindiv .bArea .b2 {
grid-area: b2;
border: 1px solid blue;
}
main .maindiv .bArea .b3 {
grid-area: b3;
border: 1px solid blue;
}
footer {
display: grid;
grid-area: footer;
margin: 0px;
border: 2px solid blue;
}
main .imageframe{
display: flex;
flex-flow: row wrap;
justify-content: flex-start;
align-items: center;
margin: auto;
min-height: 120px;
min-width: 120px;
border: 0px;

}
main .imageframe .image{
object-fit: cover;


margin: auto;
margin: 0px;
border: 0px;
filter: grayscale(50%); /* Apply grayscale effect */
transition: transform 3s ease-in-out;
}

img:hover {
z-index: 1;
transform: scale(1.5); /* Slight zoom on hover */
filter: grayscale(0%); /* Remove grayscale on hover */
}
        </style>
        <script src="productcategory.js" defer></script>
        <script src="https://js.globalpay.com/4.1.3/globalpayments.js"></script>

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
                        <li><a class="dropdown-item" href="../controller.php?act=beauty_and_skincare">beauty and skincare</a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=clothing_and_accessories">clothing and accessories </a></li>                       
                        <li><a class="dropdown-item" href="../controller.php?act=footwear">footwear</a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=household_items">household items</a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=occassional_items">occassional items</a></li>
                    </ul>
                </div>
                <input type="search" name="search" placeholder="search" style="height: 95%; border-radius: 10px;" >
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> settings </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">theme</a></li>
                        <li><a class="dropdown-item" href="#"> client preferrences</a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=fetch_cookie">cart </a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> client info </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../controller.php?act=edit_profile">edit/view profile </a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=fetch_cart"> client cart</a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=fetch_favorite"> client favorite</a></li>
                        <li><a class="dropdown-item" href="../controller.php?act=logout">logout</a></li>
                    </ul>
                </div>
            </div>
            <div id="display"></div>
        </header>
        <section> 
            
        </section>
        <main>
            <div class="maindiv">
            <div class="aArea" style='color: green;font-weight: bold;'>  Total items in cart: <?php echo (isset($_SESSION["cart"]) ? count(json_decode($_SESSION["cart"], true)) : 0); ?>
            <div class="row row-cols-1 row-cols-md-1 g-4" style="display: flex; flex-flow: row wrap; justify-content: center; align-items: flex-start; width: 100%;">
                <?php

                if (isset($_SESSION["cart"]) && $_SESSION["display_cart"] === true) {
                    $result = isset($_SESSION["cart"]) ? json_decode($_SESSION["cart"], true) : [];
                    if(empty($result)){
                        echo "<div style='color:red;'>No items in cart yet.</div>";
                    }else{
                       foreach ($result as $path) {

                            $fullPath = realpath($path);
                            
                            if ($fullPath && file_exists($fullPath)) {
                                $mime = mime_content_type($fullPath);
                                $data = base64_encode(file_get_contents($fullPath));
                                echo "<div class='col'>
                                    <div class='card'>
                                        <img src='data:$mime;base64,$data' id='$fullPath' class='card-img-top' style='width: 98%; margin: 3px;height: 200px;object-fit: fill;' />
                                        <div class='card-body'>
                                            <form method='POST' action='../controller.php?act=FavoriteAndCart&id=".urlencode(htmlspecialchars($fullPath))."' style='display: flex; flex-flow: row nowrap; height:auto;width:95%; padding: 1px; justify-content: space-evenly;  object-fit:contain;'>
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
            <div class="bArea" style='color: green;font-weight: bold;'>  
                <div class="b1">Total price to pay: <?php echo (isset($_SESSION["total"]) ? $_SESSION["total"] : 0.00); ?> MAD</div>
                <div class="b2">
                <form method="POST" action="../controller.php?act=process_payment&price=<?php echo (isset($_SESSION["total"]) ? $_SESSION["total"] : 0.00); ?>" style="display: inline; ">
                    <input type="radio" name="payment_method" value="credit_card" checked> Credit Card
                    <input type="radio" name="payment_method" value="paypal"> PayPal
                    <input type="radio" name="payment_method" value="bank_transfer"> Bank Transfer<br><br>
                    <input type="text" name="shipping_address" placeholder="Enter your address like this: street address, city, province" style="margin: 15px; width: 15%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> shipping address
                    <input type="text" name="postal_code" placeholder="Enter your postal code" style="margin: 15px; width: 15%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> Postal Code<br><br>
                    <input type="number" name="cvc" placeholder="cvc" style="margin: 15px; width: 10%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> cvc
                    <input type="text" name="account_name" placeholder="account name" style="margin: 15px; width: 20%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> account name
                    <input type="date" name="expiry_date" placeholder="expiry date" style="margin: 15px; width: 15%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> expiry date
                    <input type="text" name="coupon_code" placeholder="coupon code (if any)" style="margin: 15px; width: 15%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> coupon code<br><br>
                    <input type="text" name="bank_account" placeholder="..00..399823665368... or A0009uaaS...." style="margin: 15px; width: 60%; padding: 5px; text-align: center; border-radius: 5px; color: black; border: 1px solid #ccc;" pattern="[A-Z0-9]{15,34}" required> bank account<br><br>
                    <input type="text" name="mobile_number" placeholder="Enter your mobile number" style="margin: 15px; width: 30%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> mobile number
                    <input type="email" name="email" placeholder="Enter your email" style="margin: 15px; width: 30%; padding: 5px; border-radius: 5px; color: black; border: 1px solid #ccc;" required> email<br><br>
                    <input type="submit" name="checkout" value="Proceed" style="background-color: white; border: 1px solid black; border-radius: 5px;margin: 0 px; margin-left: 40px; padding: 5px 10px; cursor: pointer;">
                </form>
                </div>
                <div class="b3">
                    <input type="checkbox" name="terms" required> I agree to the terms and conditions
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