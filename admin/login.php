<?php
require_once('../includes/config.php');


if($user->is_logged_in()){header('location:login.php');}

if(isset($_POST['login'])){
    //clean code
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if($user->login($username,$password)){
        header('location:index.php');
        exit;
    }
    else{
        $message = '<p class="error">Wrong username or  password!</p>';
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style/style.css?v=<?php echo time();?>">
    <style>
        p.error {
            position: absolute;
            width:80%;
            top: 20px;
            left: 50%;
            text-align:center;
            transform: translateX(-55%);
        }       
    </style>
</head>

<body>
    <div id="wrapper">

        <div id="login" style='position:relative'>
        <?php if(isset($message)){echo $message;}?>
            <form action="" method="post">
                <h1>Login</h1>
                <input placeholder="Username" type="text" name='username' required="">
                <input placeholder="Password" type="password" name='password' required="">
                <input type="submit" name='login' value='Login'> 
            </form>
        </div>
    </div>
</body>

</html>