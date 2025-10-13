<?php
session_start();
$message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
         
        <link href="view/design1.css" rel="stylesheet">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap JavaScript (optional, for interactive components) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body>
        <div class="container">
            <?php echo isset($message)? $message: ""; ?>
            <div class="login-content">
                <div> 
                    <div style="padding: 20px; color: blue;font-size: 32px;"> client login credentials </div>
                    <form method ="POST" action = "../backend/controller.php?act=login" >
                        Email <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" required><br>
                        Password <input type="password" name="password" class="form-control" id="exampleFormControlInput1" placeholder="********" required><br>
                        <input type="submit" name="submit" value="login" style="background-color: dodgerblue; border-radius: 5px;margin: 0 px;">
                    </form>  
                </div>
            </div>
            <button style="border-radius: 5px;float: left;background-color: dodgerblue; width: 100px; margin-bottom: auto; margin-top: 15px;"> signup </button>
        </div>
    </body>
</html>