<?php
    require_once('../includes/config.php');
    if(!$user->is_logged_in()){header('location:login.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Add Post</title>
    <link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
</head>
<body>
    <div id="wrapper">

    <?php include('menu.php');?>

    <h2>Add Post</h2>
    <hr>
    <?php 
        if(isset($_POST['addpost'])){

            $_POST=stripslashes_deep($_POST);
            //collect form data
            // bất kỳ phần tử bài nào sau đó có thể truy cập bằng cách sử dụng tên của nó để $ _POST ['postTitle'] trở thành $postTitle.
            extract($_POST);


            //the path to store the uploaded image
            $images = "../images/".$_FILES['image']['name'];
            //GET image submitted data from the form
            $image = $_FILES['image']['name'];
            //very basic validation
            if($postTitle == ''){
                $error[] = 'Please enter the title.';
            }
            if($postDesc==''){
                $error[] = 'Please enter the description.';
            }
            if($postCont == ''){
                $error[] = 'Please enter the content.';
            }
            if(!isset($error)){
                try{
                    $postSlug = slug($postTitle);
                    //insert into database
                    $cmd  = $db->prepare('INSERT INTO blog_posts (postTitle,postSlug,image,postDesc,postCont,postDate,postTags) VALUES (:postTitle,:postSlug,:image,:postDesc,:postCont,:postDate,:postTags)');
                    $cmd->execute(array(
                        ':postTitle'=>$postTitle,
                        ':postSlug'=>$postSlug,
                        ':image' =>$image,
                        ':postDesc'=>$postDesc,
                        ':postCont'=>$postCont,
                        ':postDate'=>date('Y-m-d H:i:s'),
                        ':postTags'=>$postTags
                    ));
                    //add categories
                    $postID = $db->lastInsertId();
                    if(is_array($catID)){
                        foreach($_POST['catID'] as $catID){
                            $stmt = $db->prepare('INSERT INTO blog_post_cats (postID,catID) VALUES (:postID,:catID)');
                            $stmt->execute(array(
                                ':postID' => $postID,
                                ':catID' => $catID
                            ));
                        }
                    }
                    //move the uploaded image into the folder: images
                    move_uploaded_file($_FILES['image']['tmp_name'],"../images/$image");
                    header('location:index.php?action=added');
                    exit;
                }
                catch(PDOException $e){
                    echo $e->getMessage();
                }
            }
            else{
                foreach($error as $error){
                    echo '<p class="error">'.$error.'</p>';
                }
            }
        }

        if(isset($error)){
            foreach($error as $error){
                echo '<p class="error">'. $error.'</p>';
            }
        }
    ?>
        <form action="" method='post' enctype='multipart/form-data'>
            <p><label for="postTitle">Title</label><br>
            <!-- if validate fail show all content entered into the form -->
            <input type="text" name="postTitle" id="postTitle" value='<?php if(isset($error)){ echo $_POST['postTitle'];}?>'></p>

            <p><label for="image">Image</label><br>
            <input type="file" name="image" id="image" ></p>


            <p><label for="postDesc">Description</label><br>
            <textarea name="postDesc" id="postDesc" cols="60" rows="10"><?php if(isset($error)){echo $_POST['postDesc'];}?></textarea></p>

            <p><label for="postCont">Content</label><br>
            <textarea name="postCont" id="postCont" cols="60" rows="10"><?php if(isset($error)){echo $_POST['postCont'];}?></textarea></p>

            <fieldset>
                <legend>Categories</legend>
                <?php 
                    //get all category
                        $stmt = $db->query('SELECT catID,catTitle FROM blog_cats ORDER BY catTitle');
                        while($row=$stmt->fetch()){
                            echo "<input type='checkbox' name='catID[]' value='".$row['catID']."'>".$row['catTitle']."<br />";
                        }
                ?>
            </fieldset>
            <p><label>Tags (comma seperated)</label>
            <input type='text' name='postTags' value ='<?php if(isset($error)) echo $_POST['postTags']; ?>' style='width:400px'></p>
            <p><input type="submit" name="addpost" id="addpost" value='Add'></p>
        </form>
        <!-- sử dụng thư viện trình soạn thảo cho nội dung thay vì để quản trị viên nhập html -->
    </div>
    <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
  <script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
  </script>
  <script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-46100971-1', 'auto');
ga('send', 'pageview');
</script>
</body>
</html>
