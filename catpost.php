<?php
require('includes/config.php');

$cmd = $db->prepare('SELECT catID, catTitle FROM blog_cats WHERE catSlug = :catSlug');
$cmd->execute(array(':catSlug' => $_GET['id']));
$row = $cmd->fetch();

//if post does not exists redirect user.
if ($row['catID'] == '') {
    header('location:./');
    exit;
}

?>
<?php include('model1/header.php'); ?>


<main id='content' class="mt-5">
    <div class="row container-fluid">
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
            <?php
            try {
                $cmd = $db->prepare('SELECT blog_posts.postID,blog_posts.image,blog_posts.postTitle,blog_posts.postSlug,blog_posts.postDesc, blog_posts.postDate  
                        FROM blog_posts, blog_post_cats 
                        WHERE blog_posts.postID = blog_post_cats.postID 
                        AND blog_post_cats.catID = :catID ORDER BY postID DESC');
                $cmd->execute(array(':catID' => $row['catID']));
                echo ("<div class='clearfix'>");
                echo ("<div class='row'>");
                while ($row = $cmd->fetch()) {
                    echo ("<div class='col-xl-6 col-lg-6 col-md-12 col-sm-12' style='max-width:370px'>");
                    echo '<div class="clearfix">
                                    <a style="float:right" href="' . $row['postSlug'] . '.html#disqus_thread"></a>
                                </div>';
                    echo '<a href="' . $row['postSlug'] . '.html"><div style="max-width:370px;height:210px;"><img src="images/' . $row['image'] . '"  width="100%" height="100%"></div></a>';
                    echo '<h5><a href="' . $row['postSlug'] . '.html">' . htmlentities($row['postTitle']) . '</a></h5>';
                    echo '<p>Posted on ' . date('Y-m-h', strtotime(($row['postDate']))) . ' in ';

                    $cmd1 = $db->prepare('SELECT catTitle,catSlug FROM blog_cats,blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                    $cmd1->execute(array(':postID' => $row['postID']));
                    $catRow = $cmd1->fetchAll(PDO::FETCH_ASSOC);
                    $links = array();
                    foreach ($catRow as $cat) {
                        $links[] = '<a href="c-' . $cat['catSlug'] . '">' . htmlentities($cat['catTitle']) . '</a>';
                    }
                    echo implode(', ', $links);

                    echo '</p>';
                    echo '<p>' . $row['postDesc'] . '</p>';
                    echo '<p><a href="' . $row['postSlug'] . '.html">Đọc Thêm</a></p>';
                    echo '</div>';
                }
                echo ("</div>");
                echo ("</div>");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 p-0">
            <?php include('model1/aside.php') ?>
        </div>
    </div>
</main>
<?php include('model1/footer.php') ?>