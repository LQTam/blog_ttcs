<?php
    include('../includes/config.php');

    //if not login
    if(!$user->is_logged_in()){header('location:login.php');}
    
    //if form has been submitted process it
    if(isset($_POST['edituser'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($username ==''){
			$error[] = 'Please enter the username.';
		}

        //validate password
		if( strlen($password) > 0){

			if($password ==''){
				$error[] = 'Please enter the password.';
			}

			if($passwordConfirm ==''){
				$error[] = 'Please confirm the password.';
			}

			if($password != $passwordConfirm){
				$error[] = 'Passwords do not match.';
			}

		}
		

		if($email ==''){
			$error[] = 'Please enter the email address.';
		}

		if(!isset($error)){

			try {

				if(isset($password)){

					//hash pass
					$hashedpassword = password_hash($password, PASSWORD_BCRYPT);

					//update into database
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, password = :password, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':password' => $hashedpassword,
						':email' => $email,
						':memberID' => $memberID
					));


				} else {

					//update database
					$stmt = $db->prepare('UPDATE blog_members SET username = :username, email = :email WHERE memberID = :memberID') ;
					$stmt->execute(array(
						':username' => $username,
						':email' => $email,
						':memberID' => $memberID
					));

				}
				

				//redirect to index page
				header('Location: users.php?action=updated');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

        }
        else{
            foreach($error as $error){
                echo $error.'<br>';
            }
        }

	}


            try{
                $cmd = $db->prepare('SELECT memberID,username,email FROM blog_members WHERE memberID=:memberID');
                $cmd->execute(array(':memberID'=>$_GET['id']));
                $row = $cmd->fetch();
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Edit User</title>
	<link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <div id="wrapper">

        <?php include('menu.php');?>

        <h2>Sửa tài khoản</h2>
        
        <form action="" method = 'post'>
            <input type="hidden" name="memberID" id="memberID" value='<?php echo $row['memberID'];?>'>
            
            <p><label for="username">Tài khoản</label><br>
            <input type="text" name="username" id="username" value='<?php echo $row['username']?>'></p>

            <p><label for="password">Mật khẩu</label><br>
            <input type="password" name="password" id="password" value=''></p>

            <p><label for="passwordConfirm">Xác nhân mật khẩu</label><br>
            <input type="password" name="passwordConfirm" id="passwordConfirm" value=''></p>

            <p><label for="email">Email</label> <br>
            <input type="email" name="email" id="email" value='<?php echo $row['email']?>'></p>

            <p><input type="submit" name="edituser" id="edituser" value='Cập nhật'></p>
        </form>
    </div>
</body>
</html>
