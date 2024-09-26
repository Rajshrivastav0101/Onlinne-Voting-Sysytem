<?php
session_start();

if (!isset($_SESSION['userdata'])) {
    header("location:../");
}

$groupsdata = $_SESSION['groupsdata'] ?? [];

// Sort the groups data based on votes in descending order
usort($groupsdata, function ($a, $b) {
    return $b['vote'] - $a['vote'];
});

$winner = $groupsdata[0]; // The group with the highest votes is the winner
?>

<html>

<head>
    <title>Online Voting System - Final Result</title>
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

        #mainSection {
            text-align: center;
        }

        #headerSection {
            background-color: gray;
            padding: 20px;
            border-radius: 10px;
        }

        #winnerSection {
            background-color: grey;
            width: 60%;
            padding: 20px;
            margin: auto;
            text-align: center;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>

    <div id="mainSection">
        <div id="headerSection">
            <a href="../"><button id="backbtn">Back</button></a>
            <h1 style="color: white;">E-Voting System - Final Result</h1>
        </div>

        <div id="winnerSection">
            <h2>Final Result</h2>
            <?php if ($winner) : ?>
                <div style="margin-bottom: 20px;">
                    <strong>The winner is:</strong>
                    <p><?php echo $winner['name']; ?></p>
                </div>

                <div style="display: flex; justify-content: space-around;">
                    <div style="flex: 1;">
                        <strong>Group Name:</strong>
                        <p><?php echo $winner['name']; ?></p>
                    </div>

                    <div style="flex: 1;">
                        <strong>Votes:</strong>
                        <p><?php echo $winner['vote']; ?></p>
                    </div>
                </div>
            <?php else : ?>
                <p>No winner yet. Keep voting!</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
