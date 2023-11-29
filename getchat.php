<?php
session_start();
include_once "config.php";

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

if (isset($_REQUEST['q'])) {
    $id = $_REQUEST['q'];

    $sql = "select * from users where chat_user_id = $id";
    $result = mysqli_query($conn, $sql);
    $dtls = mysqli_fetch_assoc($result);

    echo "<input type='hidden' id='usernm' value='{$dtls['chat_user_id']}'>\n";
    echo "<h4> To: {$dtls['name']}</h4>\n";
    echo "<ul id='chatMessages'>\n";

    $messages = "select * from messages where (incoming_msg_id = $_SESSION[id] and outgoing_msg_id = $id) OR ( incoming_msg_id = $id and outgoing_msg_id = $_SESSION[id] )";
    $messagesexe = mysqli_query($conn, $messages);

    while ($row = mysqli_fetch_array($messagesexe)) {
        if ($row['outgoing_msg_id'] == $_SESSION['id']) {
            echo "<li class='right'>{$row['msg']}</li>\n";
        } else {
            echo "<li class='left'>{$row['msg']}</li>\n";
        }
    }

    echo "</ul>\n";
    echo "<input type='hidden' id='receiverid' name='receiverid' value='{$id}'>\n";
    echo "<input type='text' id='messageInput' placeholder='Type your message'>\n";
    echo "<button onclick='sendMessage()'>Send</button>\n";

    flush();
    sleep(1);
}
?>