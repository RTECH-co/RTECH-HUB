<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Insert product into database
    require_once 'db_connection.php';
    $conn = openDatabaseConnection();
    $sql = "INSERT INTO products (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssd', $name, $description, $price);
    $stmt->execute();
    $stmt->close();
    closeDatabaseConnection($conn);

    // Redirect to admin panel
    header('Location: admin_panel.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h2>Add Product</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" min="0.01" step="0.01" required><br><br>
        <input type="submit" value="Add Product">
    </form>
</body>
</html>
