<?php require_once('../includes/config.php');
    if(!$user->is_logged_in()){ header('location:login.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Add Category</title>
    <link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <div id="wrapper">
        <?php include('menu.php'); ?>
        <p><a href="categories.php">Categories Index</a></p>
        <h2>Add Category</h2>

        <?php
            if(isset($_POST['submit'])){
                $_POST = array_map('stripslashes',$_POST);
                
                //collect form data
                extract($_POST);

                if($catTitle == ''){
                    $error[] = 'Please enter the Category.';
                }

                if(!isset($error)){
                    try{
                        $catSlug = slug($catTitle);

                        $stmt = $db->prepare('INSERT INTO blog_cats(catTitle,catSlug) VALUES (:catTitle,:catSlug)');
                        $stmt->execute(array(
                            ':catTitle'=>$catTitle,
                            ':catSlug'=>$catSlug
                        ));

                        header('Location: categories.php?action=added');
                        exit();
                    }
                    catch(PDOException $e){
                        echo $e->getMessage();
                    }
                }
            }

            if(isset($error)){
                foreach($error as $error){
                    echo "<p class='error'>".$error."</p>";
                }
            }
        ?>
        <form action="" method = 'post'>
            <p><label for="">Title</label></p>
            <input type="text" name="catTitle" id="" value='<?php if(isset($error)) echo $_POST['catTitle']; ?>'>
            <p><input type="submit" name="submit" value='Submit' id=""></p>
        </form>
    </div>
</body>
</html>