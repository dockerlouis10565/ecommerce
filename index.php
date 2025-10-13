<?php
session_start();
##
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        <link href="design1.css" rel="stylesheet">
       
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
                    <form method ="POST" action ="controller.php?act=signup" >
                        First Name <input type="text" name="firstname" class="form-control" id="firstname" placeholder="John" required><br>
                        Last Name <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Doe" required><br>
                        Email <input type="email" name="email"class="form-control" id="email" placeholder="name@example.com" required><br>
                        Password <input type="password" name="password" class="form-control" id="password" placeholder="********" required><br>
                        <input type="submit" name="submit" value="signup" style="background-color: dodgerblue; border-radius: 5px;margin: 0 px;">
                    </form>  
                </div>
            </div><br><br><br>
            <button style="border-radius: 5px;float: left;background-color: dodgerblue; width: 100px;" class="btn btn-primary"> <a href="view/login.php">login </a></button>
        </div>
    </body>
</html>