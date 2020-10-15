<?php
require_once('../includes/config.php');
if (!$user->is_logged_in()) {
    header('location: login.php');
}

if (isset($_GET['delFB'])) {
    $stmt = $db->prepare('DELETE FROM feedback WHERE id = :id');
    $stmt->execute(array(':id' => $_GET['delFB']));

    header('location:feedback.php?action=deleted');
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
        function delFB(id, title) {
            if (confirm("Are you sure you want to delete '" + title + "'")) {
                window.location.href = 'feedback.php?delFB=' + id;
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
            echo '<h3>Feedback ' . $_GET['action'] . '</h3>';
        }
        ?>
        <table>
            <tr>
                <th>Tên</th>
                <th>Email</th>
                <th>Nội dung</th>
                <th>Hành động</th>
            </tr>
            <?php
            try {
                $stmt = $db->query('SELECT id,fullName,email,content FROM feedback ORDER BY id DESC ');
                while ($row = $stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . htmlentities($row['fullName']) . '</td>';
                    echo '<td>' . htmlentities($row['email']) . '</td>';
                    echo '<td><textarea cols="30">' . htmlentities($row['content']) . '</textarea></td>';
            ?>
                    <td>
                        <a href="javascript:delFB('<?php echo $row['id']; ?>','<?php echo $row['fullName']; ?>')">Delete</a>
                    </td>

            <?php echo '</tr>';
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>
        </table>
    </div>
</body>

</html>