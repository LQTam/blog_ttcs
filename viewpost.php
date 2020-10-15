<?php require('includes/config.php') ?>
<?php
//  The prepare will 'prepare' the database for query to be run then when
//  $stmt->execute is ran the items from the array will be bound and sent to the database server,
// change postID = postID and stmtExcute
$cmd = $db->prepare('SELECT postID,postTitle,postCont,postDate FROM blog_posts WHERE postSlug=:postSlug');
$cmd->execute(array(':postSlug' => $_GET['id']));
$row = $cmd->fetch();

//if not postID come then redirect user index page
if ($row['postID'] == '') {
    header('location:./');
    exit;
}
?>
<?php include('model1/header.php'); ?>
<main id='content' style='margin:56px auto 0 auto' class=" container-fluid">
    <div class="row  p-0">
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 p-0">
            <div class="thecontent">
                <?php
                //display select post
                echo '<div>';
                echo '<h1>' . htmlentities($row['postTitle']) . '</h1>';
                echo '<p>' . date('l, F jS, Y', strtotime($row['postDate'])) . ' in ';
                $cmd1 = $db->prepare('SELECT catTitle,catSlug FROM blog_cats,blog_post_cats WHERE blog_cats.catID = blog_post_cats.catID AND blog_post_cats.postID = :postID');
                $cmd1->execute(array(':postID' => $row['postID']));
                $catRow = $cmd1->fetchAll(PDO::FETCH_ASSOC);
                $link = array();
                foreach ($catRow as $cat) {
                    $link[] = "<a href='c-" . $cat['catSlug'] . "' class='pill'>" . htmlentities($cat['catTitle']) . "</a>";
                }
                echo implode(', ', $link);
                echo "</p>";
                echo '<p>' . ($row['postCont']) . '</p>';
                echo '</div>';
                ?>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 p-0">
            <?php include('model1/aside.php') ?>
        </div>
    </div>
</main>
<div id="disqus_thread"></div>
<script>
    (function() { // DON'T EDIT BELOW THIS LINE
        var d = document,
            s = d.createElement('script');
        s.src = 'https://blog-qx34bgck2z.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

<?php include('model1/footer.php') ?>
</body>

</html>