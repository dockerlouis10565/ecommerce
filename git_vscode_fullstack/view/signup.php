<?php


?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <style>
        html,body {
            height: 100%;
            margin: 0;
            background-image:url("images/background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }
        .container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100vh;
            flex-direction:column;
        }
        .login-content {
            border: 2px solid blue;
            background-color: aqua;
            display:flex;
            flex-direction: column; 
            height: auto; 
            width: 500px;
            justify-content: center;
            align-items: center; 
            border-radius: 15px; 
            padding: 5px;
        }
        
        </style>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap JavaScript (optional, for interactive components) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="login-content">
                <div> 
                    <div style="padding: 20px; color: blue;font-size: 32px;"> client signup credentials </div>
                    <form method ="POST" action ="#" >
                        First Name <input type="text" name="firstname" class="form-control" id="exampleFormControlInput1" placeholder="John"><br>
                        Last Name <input type="text" name="lastname" class="form-control" id="exampleFormControlInput1" placeholder="Doe"><br>
                        Email <input type="email" name="email"class="form-control" id="exampleFormControlInput1" placeholder="name@example.com"><br>
                        Password <input type="password" name="password" class="form-control" id="exampleFormControlInput1" placeholder="********"><br>
                        <input type="submit" name="submit" value="signup" style="background-color: dodgerblue; border-radius: 5px;margin: 0 px;">
                    </form>  
                </div>
            </div>
            <button style="border-radius: 5px;float: left;background-color: dodgerblue; width: 100px;"> login </button>
        </div>
    </body>
</html>