<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin'])) {
    header("location:../");
    exit();
}

include("../api/connect.php");

// Fetch users' information from the database
$usersQuery = mysqli_query($connect, "SELECT id, name, mobile, address FROM user WHERE role = 1");
$usersData = mysqli_fetch_all($usersQuery, MYSQLI_ASSOC);

// Fetch groups' information from the database
$groupsQuery = mysqli_query($connect, "SELECT id, name, vote FROM user WHERE role = 2");
$groupsData = mysqli_fetch_all($groupsQuery, MYSQLI_ASSOC);

// Fetch voted users' information from the database
$votedUsersQuery = mysqli_query($connect, "SELECT id, name, mobile, address FROM user WHERE role = 1 AND status = 1");
$votedUsersData = mysqli_fetch_all($votedUsersQuery, MYSQLI_ASSOC);

// Fetch groups' information with vote counts from the database
$groupsVoteQuery = mysqli_query($connect, "SELECT id, name, vote FROM user WHERE role = 2");
$groupsVoteData = mysqli_fetch_all($groupsVoteQuery, MYSQLI_ASSOC);

// Handle user removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeUser'])) {
    // Remove user based on the provided phone number
    $phoneNumber = $_POST['removeUserPhoneNumber'];
    mysqli_query($connect, "DELETE FROM user WHERE mobile = '$phoneNumber'");
    // Reload the page to reflect the changes
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<html>

<head>
    <title>Admin Dashboard - Online Voting System</title>
    <link rel="stylesheet" href="../css/stylesheet.css">
</head>

<body>
    <style>
        #backbtn {
            padding: 5px;
            border-radius: 5px;
            background-color: #0984e3;
            color: white;
            font-size: 15px;
            float: left;
            margin: 10px;
        }

        #logoutbtn {
            padding: 5px;
            border-radius: 5px;
            background-color: #0984e3;
            color: white;
            font-size: 15px;
            float: right;
            margin: 10px;
        }

        #adminSection {
            display: flex;
        }

        #navigation {
            width: 20%;
            background-color: gray;
            padding: 20px;
            border-radius: 10px;
            margin-right: 20px;
        }

        #navigation a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            padding: 5px;
            border-radius: 5px;
            background-color: #ddd;
            cursor: pointer;
        }

        #adminContent {
            flex-grow: 1;
            background-color: gray;
            border-radius: 10px;
            padding: 20px;
        }

        .infoSection {
            display: none;
            margin-top: 20px;
            border: 2px solid;
            padding: 10px;
            border-radius: 10px;
        }

        .infoSection h2 {
            color: black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        #admincontent h2 {
            text-align: center;
        }

        #adminContent form {
            margin-top: 20px;
            display: none;
            /* Hide the form by default */
        }

        #adminContent form input {
            margin-right: 10px;
        }
    </style>

    <script>
        function showSection(sectionId) {
            var sections = document.querySelectorAll('.infoSection');
            sections.forEach(function (section) {
                section.style.display = 'none';
            });

            if (sectionId === 'contact') {
                // Load contact information into the iframe
                var contactIframe = document.getElementById('contactIframe');
                contactIframe.src = '../header/contact.html';
            } else {
                var selectedSection = document.getElementById(sectionId);
                selectedSection.style.display = 'block';
            }
        }

        function showRemoveUserForm() {
            // Show the form for removing a user
            var removeUserForm = document.getElementById('removeUserForm');
            removeUserForm.style.display = 'block';
        }
    </script>

    <div id="mainSection">
        <center>
            <div id="headerSection">
                <!--a href="../"><button id="backbtn">Back</button></a-->
                <a href="../"><button id="logoutbtn">Logout</button></a>
                <h1>Admin Dashboard</h1>
            </div>
        </center>
        <hr>
        <br>

        <div id="adminSection">
            <div id="navigation">
                <a onclick="showSection('user')">User</a>
                <a onclick="showSection('group')">Group</a>
                <a onclick="showSection('vote')">Vote</a> <!-- Added "Vote" option -->
               <!-- <a onclick="showSection('contact')">Contact Us</a>
                <a>About</a>
                <a onclick="showSection('update')">Update</a>-->
            </div>

            <div id="adminContent">
                <div class="infoSection" id="user">
                    <h2>User Information</h2>
                    <?php
                    if (!empty($usersData)) {
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Name</th>';
                        echo '<th>Mobile</th>';
                        echo '<th>Address</th>';
                        echo '</tr>';

                        foreach ($usersData as $user) {
                            echo '<tr>';
                            echo '<td>' . $user['id'] . '</td>';
                            echo '<td>' . $user['name'] . '</td>';
                            echo '<td>' . $user['mobile'] . '</td>';
                            echo '<td>' . $user['address'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No user data available';
                    }
                    ?>
                </div>

                <div class="infoSection" id="group">
                    <h2>Group Information</h2>
                    <?php
                    if (!empty($groupsData)) {
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Name</th>';
                        echo '<th>Votes</th>';
                        echo '</tr>';

                        foreach ($groupsData as $group) {
                            echo '<tr>';
                            echo '<td>' . $group['id'] . '</td>';
                            echo '<td>' . $group['name'] . '</td>';
                            echo '<td>' . $group['vote'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No group data available';
                    }
                    ?>
                </div>

                <!-- New section for "Vote" -->
                <div class="infoSection" id="vote">
                    <h2>Voting Information</h2>
                    <?php
                    // Display voted users' information
                    if (!empty($votedUsersData)) {
                        echo '<h3>Voted Users</h3>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Name</th>';
                        echo '<th>Mobile</th>';
                        echo '<th>Address</th>';
                        echo '</tr>';

                        foreach ($votedUsersData as $votedUser) {
                            echo '<tr>';
                            echo '<td>' . $votedUser['id'] . '</td>';
                            echo '<td>' . $votedUser['name'] . '</td>';
                            echo '<td>' . $votedUser['mobile'] . '</td>';
                            echo '<td>' . $votedUser['address'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No voted user data available';
                    }

                    // Display groups' vote counts
                    if (!empty($groupsVoteData)) {
                        echo '<h3>Group Vote Counts</h3>';
                        echo '<table>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Name</th>';
                        echo '<th>Votes</th>';
                        echo '</tr>';

                        foreach ($groupsVoteData as $groupVote) {
                            echo '<tr>';
                            echo '<td>' . $groupVote['id'] . '</td>';
                            echo '<td>' . $groupVote['name'] . '</td>';
                            echo '<td>' . $groupVote['vote'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    } else {
                        echo 'No group vote data available';
                    }
                    ?>
                </div>
                <div class="infoSection" id="contact">
                    <h2>Contact Information</h2>
                    <iframe id="contactIframe" style="width: 100%; height: 500px; border: none;"></iframe>
                </div>
                <div class="infoSection" id="update">
                    <h2>Update Admin Information</h2>
                    <button onclick="showRemoveUserForm()">Remove User</button>
                    <form id="removeUserForm" method="post" action="">
                        <label for="removeUserPhoneNumber">Phone Number:</label>
                        <input type="tel" id="removeUserPhoneNumber" name="removeUserPhoneNumber" required>
                        <input type="submit" name="removeUser" value="Remove User">
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
