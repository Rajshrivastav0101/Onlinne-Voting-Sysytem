<?php
include("connect.php");
session_start();

$name = isset($_POST['mobile']) ? $_POST['mobile'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : '';

// Validation: Check if any of the required fields are empty
if (empty($name) || empty($password) || empty($role)) {
    echo '
        <script>
            alert("All fields are required");
            window.location="../";
        </script>
    ';
    exit(); // Stop further execution
}

// Add more validation if needed (e.g., check if mobile is numeric, etc.)

// Hardcoded admin credentials
$adminUsername = "9874561230";
$adminPassword = "admin123";

if ($name === $adminUsername && $password === $adminPassword && $role == 3) {
    // Admin login successful
    $_SESSION['admin'] = true;
    echo '
        <script>
            window.location="../routes/admin_dashboard.php";
        </script>
    ';
} else {
    // Check normal user credentials in the database
    $check = mysqli_query($connect, "SELECT * FROM user WHERE mobile='$name' AND password='$password' AND role='$role'");
    
    if (mysqli_num_rows($check) > 0) {
        $userdata = mysqli_fetch_array($check);
        $groups = mysqli_query($connect, "SELECT * FROM user WHERE role=2");
        $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);

        $_SESSION['userdata'] = $userdata;
        $_SESSION['groupsdata'] = $groupsdata;

        echo '
            <script>
                window.location="../routes/dashboard.php";
            </script>
        ';
    } else {
        echo '
            <script>
                alert("Invalid credentials or user not found");
                window.location="../";
            </script>
        ';
    }
}
?>
