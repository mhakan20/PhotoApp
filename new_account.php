<?php
//conexiune bd folosind fisierul auxiliar
require_once "connect.php";

 

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // valodare user
    if(empty(trim($_POST["username"]))){
        $username_err = "Introduceti username.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            
            $param_username = trim($_POST["username"]);
            
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Username-ul a fost deja luat.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "S-a produs o eroare.Va rugam sa incercati mai tarziu.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //verificare parola
    if(empty(trim($_POST["password"]))){
        $password_err = "Introduceti o parola.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Parola trebuie sa contina macar 6 caractere.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirmati parola.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Parolele nu sunt identice.";
        }
    }
    
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // introducere valori in BD
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            
            $param_username = $username;
        
            $param_password = $password; 
             
            
            
            if(mysqli_stmt_execute($stmt)){
                // trimitere catre pagina de login daca s-a creat cu succes contul
                header("location: new_login.php");
            } else{
                echo "S-a produs o eroare.Va rugam sa reincercati.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<center>
    <p><span style="color: #000000;"><span style="background-color: #00ffff;"><strong>Simple Facebook</strong></span></span></p>
    <p><span style="color: #000000; background-color: #00ffff;"><strong>AWJ Project</strong></span></p>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Completati pentru a crea un cont.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Parola</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmati parola</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Detineti deja un cont? <a href="new_login.php">Login</a>.</p>
        </form>
    </div>
</center>   
</body>
</html>