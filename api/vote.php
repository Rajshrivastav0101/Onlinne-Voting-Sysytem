<?php
session_start();
include('connect.php');

$vote = (int)$_POST['gvotes'];
$total_votes = $vote + 1;
$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['id'];

// Get user information for the notification
$userQuery = mysqli_query($connect, "SELECT * FROM user WHERE id = '$uid'");
$userData = mysqli_fetch_assoc($userQuery);

$update = mysqli_query($connect, "UPDATE user SET vote = '$total_votes' WHERE id = '$gid'");
$update_user_status = mysqli_query($connect, "UPDATE user SET status = 1 WHERE id = '$uid'");

if ($update && $update_user_status) {
    $groups = mysqli_query($connect, "SELECT * FROM user WHERE role = 2");
    $groupsdata = mysqli_fetch_all($groups, MYSQLI_ASSOC);
    $_SESSION['userdata']['status'] = 1;
    $_SESSION['groupsdata'] = $groupsdata;

    // Send a Telegram notification
    $telegramBotToken = 'AAEMSJcLvNhOlQsvXJr-vbiWbmfr0HFxkxE';
    $telegramChatId = 'Help_votersBot';

    $message = "🗳️ Voting Update\n\n";
    $message .= "User: {$userData['name']}\n";
    $message .= "Group Voted: {$groupsdata[$gid]['name']}\n";
    $message .= "Total Votes: $total_votes";

    sendTelegramMessage($telegramBotToken, $telegramChatId, $message);

    echo '
    <script>
        alert("Voting Successful");
        window.location="../routes/dashboard.php";
    </script>';
} else {
    echo '
        <script>
            alert("Some error occurred!!");
            window.location="../routes/dashboard.php";
        </script>';
}

// Function to send a message via Telegram
function sendTelegramMessage($token, $chatId, $message) {
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
