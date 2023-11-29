<?php
session_start();
include_once "./config.php";

if (isset($_REQUEST['senderid'])) {
    $sender = $_REQUEST['senderid'];
    $receiver = $_REQUEST['receiverid'];
    $msg = $_REQUEST['msg'];
    $time = time();  // Get the current Unix timestamp

    $sql = "INSERT INTO `messages`(`incoming_msg_id`, `outgoing_msg_id`, `msg`, `read_status`, `msg_time`) VALUES ('$receiver','$sender','$msg','0',$time)";
    $sqlexe = mysqli_query($conn, $sql);

    if ($sqlexe) {
        echo "sent";
    } else {
        echo "not sent";
    }
}
?>