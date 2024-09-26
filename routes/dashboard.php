<?php
session_start();

if (!isset($_SESSION['userdata'])) {
    header("location:../");
}

$userdata = $_SESSION['userdata'];
$groupsdata = $_SESSION['groupsdata'] ?? [];

if ($_SESSION['userdata']['status'] == 0) {
    $status = '<b style="color:red">Not Voted</b>';
} else {
    $status = '<b style="color:green">Voted</b>';
}
?>
<html>

<head>
    <title>Online Voting system - Dashboard</title>
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

        #Profile {
            background-color: whitesmoke;
            width: 30%;
            border-radius: 10px;
            padding: 20px;
            float: left;
        }

        #Group {
            background-color: whitesmoke;
            width: 60%;
            border-radius: 10px;
            padding: 20px;
            float: right;
        }

        #votebtn {
            padding: 5px;
            border-radius: 5px;
            background-color: #0984e3;
            color: white;
            font-size: 15px;
        }

        #voted {
            padding: 5px;
            border-radius: 5px;
            background-color: green;
            color: white;
            font-size: 15px;
        }

        #finalResultBtn {
            padding: 5px;
            border-radius: 5px;
            background-color: #0984e3;
            color: white;
            font-size: 15px;
        }
        
    </style>
    <div id="mainSection">
        <center>
            <div id="headerSection">
                <a href="../"><button id="backbtn">Back</button></a>
                <a href="../"><button id="logoutbtn">Logout</button></a>
                <h1>E-Voting System</h1>
            </div>
        </center>
        <hr>
        <br>

        <div id="Profile">
            <div id="photo">
            <center><img src="../upload/<?php echo $userdata['photo']; ?>" height="100px" width="100px"><br><br></center>
            </div>
            <b>Name : </b><?php echo $userdata['name'] ?><br><br>
            <b>Mobile : </b><?php echo $userdata['mobile'] ?><br><br>
            <b>Address : </b><?php echo $userdata['address'] ?><br><br>
            <b>Status : </b><?php echo $status ?><br><br>
        </div>
    </div>
    <div id="Group">
        <?php
        if ($_SESSION['groupsdata']) 
            for ($i = 0; $i < count($groupsdata); $i++) {
        ?>
                <div>
                    <img style="float: right;" src="../upload/<?php echo $groupsdata[$i]['photo'] ?>" height="100px" width="100px">
                    <b>Group Name : </b><?php echo $groupsdata[$i]['name'] ?><br><br>
                    <br>
                    <?php
                    // Check if the user is an admin (role = 1)
                    if ($_SESSION['userdata']['role'] == 1) {
                    ?>
                        <!--<b>Votes : </b><?php echo $groupsdata[$i]['vote'] ?><br><br>-->
                    <?php
                    }
                    ?>
                    <form action="../api/vote.php" method="POST">
                        <input type ="hidden" name="gvotes" value="<?php echo $groupsdata[$i]['vote'] ?>">
                        <input type ="hidden" name="gid" value="<?php echo $groupsdata[$i]['id'] ?>">
                        <?php
                        if ($_SESSION['userdata']['status'] == 0) {
                        ?>
                            <input type ="submit" name="votebtn" value="vote" id="votebtn">
                        <?php
                        } else {
                        ?>
                            <button disabled type="button" name="votebtn" value="vote" id="voted">Voted</button>
                        <?php
                        }
                        ?>
                        
                    </form>
                </div>
                <hr>
        <?php
            }
        ?>
        <!-- Add this button where you want it in your HTML -->
        <button id="finalResultBtn" onclick="showFinalResult()"> Final Result </button>
    </div>

    <!-- Add this script in the head section or at the end of your HTML body -->
    <script>
        function showFinalResult() {
            window.location = "../routes/final_result.php";
        }
   
    </script>
</body>
</html>