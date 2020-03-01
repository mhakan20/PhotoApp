<?php
// initializare sesiune
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// conexiune la BD
require_once "connect.php";
 

$username = $password = "";
$username_err = $password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["username"]))){
        $username_err = "Introduceti username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Introduceti parola.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // verificare username si parola
    if(empty($username_err) && empty($password_err)){
        
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
          
            $param_username = $username;
            
          
            if(mysqli_stmt_execute($stmt)){
              
                mysqli_stmt_store_result($stmt);
                
                //verificare daca exista username
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $bd_password);
                    
                    if(mysqli_stmt_fetch($stmt)){
                        //verificare daca parola matchuieste cu cea din BD 
                        if($password == $bd_password) {
                        
                            session_start();
                            
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            
                            header("location: index.php");
                        } else{
                            
                            $password_err = "Parola introdusa nu este valida.";
                        }
                    }
                } else{
                    
                    $username_err = "Nu s-a gasit niciun cont cu acest username.";
                }
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
    <title>Login</title>
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
        <h2>Logare</h2>
        <p>Introduceti username si parola.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Parola</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Nu aveti cont? <a href="new_account.php">Catre creare cont</a>.</p>
        </form>
    </div> 
</center>   
</body>
</html>