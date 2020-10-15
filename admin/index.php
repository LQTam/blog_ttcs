<?php
require_once('../includes/config.php');

//if not login redirect to login page
if (!$user->is_logged_in()) {
    header('location:login.php');
}

if (isset($_GET['delpost'])) {

    //get img and delete
    $result = $db->query("SELECT blog_posts.image FROM blog_posts WHERE postID = " . $_GET['delpost'] . "");
    while ($row = $result->fetch()) {
        $img = $row['image'];
    }
    unlink($img);
    // prepare a statement
    $cmd = $db->prepare('DELETE FROM blog_posts WHERE postID = :postID');
    //delete post where matches id 
    $cmd->execute(array(':postID' => $_GET['delpost']));

    $stmt = $db->prepare('DELETE FROM blog_post_cats WHERE postID = :postID');
    $stmt->execute(array(":postID" => $_GET['delpost']));
    //reload page with a status
    header('location:index.php?action=deleted');
    exit;
}
//if has been an action then display it
if (isset($_GET['action'])) {
    echo '<h3>Post ' . $_GET['action'] . '.</h3>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
    <style>
        td {
            padding: 10px;
        }
    </style>
</head>

<body>
    <div id="wrapper" style='background: #e9dada; padding:20px;'>
        <h3>Đã đăng nhập với <?php echo $_SESSION['username'] ?></h3>
        <?php include('menu.php'); ?>
        <table>
            <tr>
                <th>Tiêu đề</th>
                <th>Ngày đăng</th>
                <th>Hành động</th>
            </tr>
            <?php
            try {
                $cmd = $db->query('SELECT postID,postTitle,postDate FROM blog_posts ORDER BY postID DESC');
                while ($row = $cmd->fetch()) {
                    echo '<tr>';
                    echo '<td>' . htmlentities($row['postTitle']) . '</td>';
                    echo '<td>' . htmlentities(date('jS M Y', strtotime($row['postDate']))) . '</td>';
            ?>

                    <td>
                        <a href="edit-post.php?id=<?php echo $row['postID']; ?>">Sửa</a> |
                        <a href="javascript:delpost('<?php echo $row['postID']; ?>','<?php echo $row['postTitle']; ?>')">
                            Xóa</a>
                    </td>
            <?php echo '</tr>';
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </table>
        <p><a href="add-post.php">Thêm bài viết</a></p>
    </div>
    </div>
    <script>
        //delete Func
        function delpost(id, title) {
            if (confirm("Are you sure you want to delete '" + title + "'")) {
                window.location.href = 'index.php?delpost=' + id;
            }
        }
    </script>
</body>

</html>