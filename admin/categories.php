<?php
require_once('../includes/config.php');
if (!$user->is_logged_in()) {
    header('location: login.php');
}

if (isset($_GET['delcat'])) {
    $stmt = $db->prepare('DELETE FROM blog_cats WHERE catID = :catID');
    $stmt->execute(array('catID' => $_GET['delcat']));

    header('location:categories.php?action=deleted');
    exit;
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
    <script>
        function delcat(id, title) {
            if (confirm("Are you sure you want to delete '" + title + "'")) {
                window.location.href = 'categories.php?delcat=' + id;
            }
        }
    </script>
</head>

<body>
    <div id="wrapper">
        <h3>Đã đăng nhập với <?php echo $_SESSION['username'] ?></h3>
        <?php include('menu.php') ?>
        <?php //show message from add/ or edit page
        if (isset($_GET['action'])) {
            echo '<h3>Category ' . $_GET['action'] . '</h3>';
        }
        ?>
        <table>
            <tr>
                <th>Tiêu đề</th>
                <th>Hành động</th>
            </tr>
            <?php
            try {
                $stmt = $db->query('SELECT catID,catTitle,catSlug FROM blog_cats ORDER BY catTitle DESC ');
                while ($row = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . htmlentities($row['catTitle']) . '</td>';
            ?>
                    <td>
                        <a href="edit-category.php?id=<?php echo $row['catID'] ?>">Sửa</a> |
                        <a href="javascript:delcat('<?php echo $row['catID']; ?>','<?php echo $row['catSlug']; ?>')">Xóa</a>
                    </td>

            <?php echo '</tr>';
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </table>
        <p><a href="add-category.php">Thêm thể loại</a></p>
    </div>
</body>

</html>