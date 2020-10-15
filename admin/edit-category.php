<?php require_once('../includes/config.php'); 
        if(!$user->is_logged_in()) header('location: login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Edit Category</title>
  <link rel="stylesheet" href="../style/main.css?v=<?php echo time();?>">
</head>
<body>
    <div id="wrapper">
        <?php include('menu.php'); ?>
        <p><a href="categories.php">Categories Index</a></p>

        <h2>Edit Category</h2>
        <?php
            if(isset($_POST['submit'])){
                $_POST = stripslashes_deep($_POST);
                extract($_POST);

                if($catID == ''){
                    $error[] = 'This post is missing a valid id!';
                }
                if($catTitle == ''){
                    $error[] ='Please enter the title.';
                }
                if(!isset($error)){
                    try{
                        //update category
                        $catSlug = slug($catTitle);
                        $stmt =$db->prepare('UPDATE blog_cats SET catTitle = :catTitle,catSlug = :catSlug WHERE catID = :catID');
                        $stmt->execute(array(
                            ':catTitle'=>$catTitle,
                            ':catSlug'=>$catSlug,
                            ':catID'=>$catID
                        ));

                        header('location:categories.php?action=updated');
                        exit;
                    }
                    catch(PDOException $e){
                        echo $e->getMessage();
                    }
                }
            }

            if(isset($error)){
                foreach($error as $error){
                    echo $error.'<br />';
                }
            }
        ?>
        <?php
            try{
                $stmt = $db->prepare('SELECT catID,catTitle,catSlug FROM blog_cats WHERE catID = :catID');
                $stmt->execute(array(':catID'=>$_GET['id']));
                $row=$stmt->fetch();
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        ?>
        <form action="" method='post'>
            <input type="hidden" name="catID" value='<?php echo $row['catID'] ?>' id="">
            <p><label for="">Title</label></p>
            <input type="text" name="catTitle" value= '<?php echo $row['catTitle'] ?>' id="">
            <p><input type="submit" name='submit' value="Update"></p>
        </form>
    </div>
</body>
</html>
