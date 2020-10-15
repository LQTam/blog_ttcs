<?php
include('../includes/config.php');

    //if not login redirect to login page
    if(!$user->is_logged_in()){ header('location:login.php');}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Edi Post</title>
    <link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
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
</head>
<body>
    <div id="wrapper">
        <?php include('menu.php');?>
        
        <h2>Sửa bài viết</h2>
        <?php 
        if(isset($_POST['update'])){
            //delete slashes
            $_POST = stripslashes_deep($_POST);

            //collect form data
            extract($_POST);

            $image = $_FILES['image']['name'];

            if($postID==''){
                $error[] = 'This post is missing a valid id.';
            }
            if($postTitle==''){
                $error[] = 'Please enter the title.';
            }
            if($postDesc==''){
                $error[] = 'Please enter the description.';
            }
            if($postCont==''){
                $error[] = 'Please enter the content.';
            }
            if(!isset($error)){
                try{
                    $postSlug = slug($postTitle);
                    
                    //insert into database
                    $cmd = $db->prepare('UPDATE blog_posts SET postTitle= :postTitle,postSlug=:postSlug,image= :image,postDesc= :postDesc,postCont= :postCont,postTags= :postTags WHERE postID= :postID');
                    $cmd->execute(array(
                        ':postTitle'=>$postTitle,
                        ':postSlug'=>$postSlug,
                        ':image' =>$image,
                        ':postDesc'=>$postDesc,
                        ':postCont'=>$postCont,
                        ':postTags'=>$postTags,
                        ':postID'=>$postID
                    ));

                    $stmt = $db->prepare('DELETE FROM blog_post_cats WHERE postID =:postID');
                    $stmt->execute(array(":postID"=>$postID));
                    if(is_array($catID)){
                        foreach($catID as $catID){
                            $stmt= $db->prepare('INSERT INTO blog_post_cats(postID,catID) VALUES (:postID,:catID)');
                            $stmt->execute(array(
                                ":postID"=>$postID,
                                ":catID"=>$catID
                            ));
                        }
                    }

                    //move image to folder images
                    move_uploaded_file($_FILES['image']['tmp_name'],"../images/$image");
                    header('location:index.php?action=updated');
                    exit;
                }
                catch(PDOException $e){
                    echo $e->getMessage();
                }
            }
        }

        //check for any error
        if(isset($error)){
            foreach($error as $error){
                echo $error .'<br>';
            }
        }

        try{
            $cmd = $db->prepare('SELECT postID,image,postTitle,postDesc,postCont,postTags FROM blog_posts WHERE postID= :postID');
            $cmd->execute(array(':postID'=>$_GET['id']));
            $row=$cmd->fetch();
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    ?>
        <form action="" method='post' enctype='multipart/form-data'>
            <input type="hidden" name="postID" id="postID" value='<?php echo $row['postID'];?>'>
            
            <p><label for="title">Tiêu đề</label><br>
            <input type="text" name="postTitle" id="postTitle" value='<?php echo $row['postTitle'];?>'></p>

            <p><label for="image">Hình ảnh</label><br>
            <input type="file" name="image" id="image"></p>
            
            <p><label for="desc">Mô tả</label><br>
            <textarea name="postDesc" id="postDesc" cols="30" rows="10"><?php echo $row['postDesc'];?></textarea></p>

            <p><label for="content">Nội dung</label><br>
            <textarea name="postCont" id="postCont" cols="30" rows="10"><?php echo $row['postCont'];?></textarea></p>

            <fieldset>
                <legend>Thể loại</legend>
                <?php
                    $stmt2 = $db->query('SELECT catID, catTitle FROM blog_cats ORDER BY catTitle');
                    while($row2 = $stmt2->fetch()){
                        $stmt3 = $db->prepare('SELECT catID FROM blog_post_cats WHERE catID = :catID AND postID = :postID') ;
                        $stmt3->execute(array(':catID' => $row2['catID'], ':postID' => $row['postID']));
                        $row3 = $stmt3->fetch(); 
                        if($row3['catID'] == $row2['catID']){
                            $checked = 'checked=checked';
                        } else {
                            $checked = null;
                        }
                        echo "<input type='checkbox' name='catID[]' value='".$row2['catID']."' $checked> ".$row2['catTitle']."<br />";
                    }
                ?>
            </fieldset>
            <p><label>Thẻ (Phân cách bằng dấu phảy)</label>
            <input type='text' name='postTags' value="<?php echo $row['postTags']; ?>"></p>
            <p><input type="submit" name="update" id="update" value='Cập nhật'></p>
        </form>
    </div>
</body>
</html>
