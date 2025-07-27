<?php


?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        
        .container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100vh;
            flex-direction:column;
        }
        
        
        </style>
        <script>
            const images = ['images/bg1.jpg', 'images/bg2.jpg', 'images/bg3.jpg', 'images/bg4.jpg', 'images/bg5.jpg', 'images/bg6.jpg'];
            let index = 0;

            /**setInterval(() => {
                index = (index + 1) % images.length;
                document.body.style.backgroundImage = `url('${images[index]}')`;
            },5000);**/ // updates every 5 seconds
        </script>
        <!-- Bootstrap CSS -->
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
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another </a></li>
                        <li><a class="dropdown-item" href="#">Something</a></li>
                    </ul>
                </div>
                <input type="search" name="search" placeholder="search" style="height: 95%; border-radius: 10px;" >
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> settings </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another </a></li>
                        <li><a class="dropdown-item" href="#">Something </a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"> client info </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another </a></li>
                        <li><a class="dropdown-item" href="#">Something</a></li>
                    </ul>
                </div>
            </div>
            <div id="display"></div>
        </header>
        <section> 
            
        </section>
        <main>
            
            <div class="row row-cols-1 row-cols-md-4 g-4 maindiv">
                <div class="col">
                    <div class="card">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
       
        <footer></footer>
        <script src="productcategoryjs.js"></script>
    </body>
</html> 