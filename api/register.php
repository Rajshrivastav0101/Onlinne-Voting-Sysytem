<?php
include("connect.php");

$name = isset($_POST['name']) ? $_POST['name'] : '';
$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$cpassword = isset($_POST['cpassword']) ? $_POST['cpassword'] : '';
$address = isset($_POST['address']) ? $_POST['address'] : '';
$image = isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : '';
$tmp_name = isset($_FILES['photo']['tmp_name']) ? $_FILES['photo']['tmp_name'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : '';

// Validation: Check if any of the required fields are empty
if (empty($name) || empty($mobile) || empty($password) || empty($cpassword) || empty($address) || empty($image) || empty($role)) {
    echo '
        <script>
            alert("All fields are required");
            window.location="../routes/register.html";
        </script>
    ';
    exit(); // Stop further execution
}

// Check if the mobile number already exists
$checkMobileQuery = "SELECT * FROM user WHERE mobile = ?";
$checkMobileStmt = $connect->prepare($checkMobileQuery);
$checkMobileStmt->bind_param("s", $mobile);
$checkMobileStmt->execute();
$checkMobileResult = $checkMobileStmt->get_result();

if ($checkMobileResult->num_rows > 0) {
    echo '
        <script>
            alert("Mobile number is already registered");
            window.location="../routes/register.html";
        </script>
    ';
    exit(); // Stop further execution
}

// Add more validation if needed (e.g., check if mobile is numeric, etc.)

// Use prepared statements to prevent SQL injection
$stmt = $connect->prepare("INSERT INTO user (name, mobile, password, address, photo, role, status, vote) VALUES (?, ?, ?, ?, ?, ?, 0, 0)");

if (!$stmt) {
    echo '
        <script>
            alert("Prepare statement failed");
            window.location="../routes/register.html";
        </script>
    ';
    exit();
}

// Bind parameters
$stmt->bind_param("ssssss", $name, $mobile, $password, $address, $image, $role);

if ($stmt->execute()) {
    move_uploaded_file($tmp_name, "../upload/$image");
    echo '
        <script>
            alert("Registration successful");
            window.location="../";
        </script>
    ';
} else {
    echo '
        <script>
            alert("Some error occurred");
            window.location="../routes/register.html";
        </script>
    ';
}

// Close the statements
$stmt->close();
$checkMobileStmt->close();
$connect->close();
?>
