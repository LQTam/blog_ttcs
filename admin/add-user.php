<?php
include('../includes/config.php');

if (!$user->is_logged_in()) {
    header('location:login.php');
}

if (isset($_POST['adduser'])) {


    //collect form data
    extract($_POST);

    if ($username == '') {
        $error[] = 'Please enter the username.';
    }
    if (strlen($password) > 0) {
        if ($password == '') {
            $error[] = 'Please enter the password.';
        }
        if ($passwordConfirm == '') {
            $error[] = 'Please confirm the password.';
        }
        if ($password != $passwordConfirm) {
            $error[] = 'Passwords do not match.';
        }
    }

    if ($email == '') {
        $error[] = 'Please enter the email address.';
    }

    if (!isset($error)) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $cmd = $db->prepare('INSERT INTO blog_members (username,password,email) VALUES (:username,:password,:email)');
            $cmd->execute(array(
                ':username' => $username,
                ':password' => $hash,
                ':email' => $email
            ));
            header('location:users.php?action=added');
            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
    <style>
        #adduser {
            width: 75%;
            margin: 58px auto 0 auto;
        }

        label {
            display: inline-block;
            width: 100px
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php include('menu.php'); ?>
        <div id='adduser'>
            <?php if (isset($error)) {
                foreach ($error as $error) {
                    echo $error . '';
                }
            }
            ?>
            <form action="" method='post'>
                <br>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value='<?php if (isset($error)) {
                                                                            echo $_POST['username'];
                                                                        } ?>' /><br />

                <label for="password">Password</label>
                <input type="password" name="password" id="password" value='<?php if (isset($error)) {
                                                                                echo $_POST['password'];
                                                                            } ?>' /><br />

                <label for="passwordConfirm">Confirm Password</label>
                <input type="password" name="passwordConfirm" id="passwordConfirm" value='<?php if (isset($error)) {
                                                                                                echo $_POST['passwordConfirm'];
                                                                                            } ?>' /><br />

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value='<?php if (isset($error)) {
                                                                        echo $_POST['email'];
                                                                    } ?>' /><br />

                <label>&nbsp;</label>
                <input type="submit" name="adduser" value='Add User'>
            </form>
        </div>
    </div>
</body>

</html>