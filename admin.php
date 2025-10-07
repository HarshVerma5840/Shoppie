<?php
session_start();
require 'db_connect.php'; 

if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'admin') {
    header("location: index.php");
    exit;
}

$users = [];
$sql = "SELECT id, name, email, mobile FROM user";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Shoppie - Admin Panel</h1>
        <nav>
            <ul>
                <li><a href="index.php">View Site</a></li>
                <li><a href="profile.php">My Profile</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <h2>User Management</h2>

        <?php if (empty($users)): ?>
            <p>No user data found.</p>
        <?php else: ?>
            <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['mobile']) ?></td>
                            <td>
                                <form action="delete_user.php" method="post" onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer>
        <p>Admin Panel</p>
    </footer>
</body>
</html>