<?php
session_start();
$message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
#gggg
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
         
        <link href="view/design1.css" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap JavaScript (optional, for interactive components) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="frontend/js/monitor.js" defer></script>
        <style>
            html,body{
                height: 100vh;
                width: 100vw;
                background-color: hsla(179, 66%, 88%, 1.00);
                display: grid;
                grid-template-areas:
                    "left right";
                grid-template-columns: 1fr 8fr;
                grid-template-rows: 1fr;
            }
            .left{
                grid-area: left;
                background-color: hsla(118, 92%, 28%, 0.60);
                display: flex;
                flex-flow: column nowrap;
                justify-content: space-around;
                align-items: center;
                margin: 6px;
                border: 2px solid black;
                border-radius: 5px;
            }
            .right{
                grid-area: right;
                background-color: hsla(118, 54%, 79%, 0.60);
                display: flex;
                flex-flow: column nowrap;
                justify-content: space-around;
                align-items: center;
                border: 2px solid black;
                border-radius: 5px;
                margin: 6px;
            }
            .right .block{
                display: block;
            }
        </style>
    </head>
    <body>
        <div class="left">
            <div id="stock" style="color: gold; cursor: pointer;" onclick="getStockData()"> stock </div>
            <div>first</div>
            <div>first</div>
        </div>
        <div class="right">
            <div id="display" class="block"> second </div>
            <div id="display" class="block"> second </div>
        </div>
    </body>
</html>