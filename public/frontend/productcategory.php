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
.maindiv {
width: 80%;
display: flex;
flex-flow: row wrap;
justify-content: center;
align-items: flex-start;
margin: auto;
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
                        <li><a class="dropdown-item" href="../backend/controller.php?act=beauty_and_skincare">beauty and skincare</a></li>
                        <li><a class="dropdown-item" href="../backend/controller.php?act=clothing_and_accessories">clothing and accessories </a></li>                       
                        <li><a class="dropdown-item" href="../backend/controller.php?act=footwear">footwear</a></li>
                        <li><a class="dropdown-item" href="../backend/controller.php?act=household">household items</a></li>
                        <li><a class="dropdown-item" href="../backend/controller.php?act=occassional">occassional items</a></li>
                    </ul>
                </div>

                <input type="search" name="search" placeholder="search" style="height: 95%; border-radius: 10px;" >
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> settings </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">theme</a></li>
                        <li><a class="dropdown-item" href="#"> client preferrences</a></li>
                        <li><a class="dropdown-item" href="#">other </a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> client info </button>
                    <ul class="dropdown-menu">                      
                        <li>
                            <a class="dropdown-item" href="../backend/controller.php?act=edit_profile&callerDetail=<?php echo urlencode($_SERVER["PHP_SELF"]); ?>">
                                edit/view profile 
                            </a>
                        </li>
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
            <div class="row row-cols-1 row-cols-md-5 g-4">
                <?php
                if(!isset($_SESSION["display_favorite"]) || !$_SESSION["display_favorite"]) {
                    $result = isset($_SESSION['product_paths']) ? $_SESSION['product_paths'] : [];
                    foreach ($result as $path) {
                    $fullPath = realpath($path);
                    if ($fullPath && file_exists($fullPath)) {
                        $mime = mime_content_type($fullPath);
                        $data = base64_encode(file_get_contents($fullPath));
                        #$newPath = str_replace("C", "!", $fullPath);
                        echo "<div class='col' >
                                <div class='card'>
                                    <img src='data:$mime;base64,$data' id='$fullPath' onmouseover='handleImageClick(this.id)'  class='card-img-top' style='width: 98%; margin: 3px;padding: 0; height: 250px;object-fit: fill;' />
                                    <div class='card-body' style='display: flex; flex-flow: row nowrap; height:5%;width:95%; justify-content: center; object-fit:cover;'>
                                       <form method='POST' action='../backend/controller.php?act=details&id=".urlencode(htmlspecialchars($fullPath))."'>
                                           <input type='hidden' name='path' value='".htmlspecialchars($fullPath)."' />
                                           <input type='submit' class='btn btn-primary' style='background-color: black; border: 0; ' value='detals'>
                                       </form>
                                    </div>
                                </div>
                        </div>";
                    } else {
                        echo "<div style='color:red;'>Image not found: $path</div>";
                    }
                    
                    }
                }
            ?>

            </div>
            <div>
                
            </div> 
        </main>
        <footer></footer>
        <script src="productcategoryjs.js"></script>
    </body>
</html> 