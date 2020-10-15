    <div class="container-fluid">
        <h3 id="newArticle" style='border-bottom:5px solid;padding-bottom:5px;'>Các bài viết mới nhất</h3>
        <ul id="list" class='navbar-nav'>
        <?php
                try{
                    $cmd = $db->query('SELECT postID,postSlug,image,postTitle,postDate FROM blog_posts ORDER BY postID DESC LIMIT 5');
                    while($row = $cmd->fetch()){
                        echo("<li class='nav-item'>");
                        echo '<a href="'.$row['postSlug'].'.html">
                        <div class="float-right" style="max-width:100px; height:65px">
                        <img  src="images/'.$row['image'].'" width="100%" height="100%" ></div></a>';
                        echo("<a class='nav-link' href='".$row['postSlug'].".html'>".$row['postTitle']."</a>");
                        echo '<p>'.date('Y-m-jS',strtotime($row['postDate'])).'</p>';
                        echo("</li>");
                    }
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
            ?>
        </ul>
    </div>