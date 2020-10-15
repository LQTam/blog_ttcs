<?php
include('../includes/config.php');
?>
<?php
if (isset($_GET['deluser'])) {
    // if user id = 1 ignore
    if ($_GET['deluser'] != '1') {
        $cmd = $db->prepare('DELETE FROM blog_members WHERE memberID=:memberID');
        $cmd->execute(array(':memberID' => $_GET['deluser']));

        header('location:users.php?action=deleted');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Users</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style/main.css?v=<?php echo time(); ?>">
</head>

<body>
    <div id="wrapper">
        <h3>Đã đăng nhập với <?php echo $_SESSION['username'] ?></h3>
        <?php include('menu.php'); ?>

        <?php
        //show message from add/edit page
        if (isset($_GET['action'])) {
            echo '<h3>User ' . $_GET['action'] . '</h3>';
        }
        ?>
        <table>
            <tr>
                <td>Tài khoản</td>
                <td>Email</td>
                <td>Hành động</td>
            </tr>
            <?php
            try {

                $cmd = $db->query('SELECT memberID,username,email FROM blog_members ORDER BY username');
                while ($row = $cmd->fetch()) {
                    echo '<tr>';
                    echo '<td>' . htmlentities($row['username']) . '</td>';
                    echo '<td>' . htmlentities($row['email']) . '</td>';
            ?>
                    <td>

                        <a href="edit-user.php?id=<?php echo $row['memberID']; ?>">Sửa</a>
                        <?php
                        if ($row['memberID'] != 1) {
                        ?>
                            | <a href="javascript:deluser('<?php echo $row['memberID']; ?>','<?php echo $row['username']; ?>')">Xóa</a>
                        <?php } ?>

                    </td>
            <?php echo '</tr>';
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </table>
        <p><a href="add-user.php">Thêm tài khoản</a></p>
    </div>
    <script>
        function deluser(id, title) {
            if (confirm('Are you sure you want to delete "' + title + '"?')) {
                window.location.href = 'users.php?deluser=' + id;
            }
        }
    </script>
</body>

</html>