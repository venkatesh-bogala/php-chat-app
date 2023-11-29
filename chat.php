<?php
session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include_once "config.php"; 

ob_start();

if (isset($_REQUEST['q'])) {
    $id = $_REQUEST['q'];

    $chatQuery = "SELECT * FROM messages WHERE (outgoing_msg_id = {$_SESSION['id']} AND incoming_msg_id = {$id}) OR (outgoing_msg_id = {$id} AND incoming_msg_id = {$_SESSION['id']}) ORDER BY msg_time ASC";
    $chatResult = mysqli_query($conn, $chatQuery);
    $chatCount = mysqli_num_rows($chatResult);

    $userQuery = "SELECT * FROM users WHERE chat_user_id = {$id}";
    $userResult = mysqli_query($conn, $userQuery);
    $userDetails = mysqli_fetch_assoc($userResult);

    $status = ($userDetails['chat_status'] == 'Offline now') ? 'Inactive' : (($userDetails['chat_status'] == 'Active now') ? 'Active' : 'Loading');
    $clr = ($userDetails['chat_status'] == 'Offline now') ? 'red' : (($userDetails['chat_status'] == 'Active now') ? 'green' : 'grey');

    echo "data: <div class='chat-row d-flex flex-row align-items-center p-2 m-0 w-100' id='navbar' style='border-bottom: 1px solid #d7d0ca; position: fixed; top: 0; width: 100%; z-index: 1;'>\n";
echo "data:     <a href='#'>\n";
echo "data:         <img src='./images/images.jpg' alt='Profile Photo' class='img-fluid rounded-circle mx-2 mr-2' style='height:50px;' id='pic'>\n";
echo "data:     </a>\n";
echo "data:     <div class='d-flex flex-column'>\n";
echo "data:         <div class='text-black font-weight-bold-apagar' id='name'>{$userDetails['name']}</div>\n";
echo "data:         <div class='text-black small' id='details' style='color: rgba(0, 0, 0, 0.6);'>\n";
echo "data:           {$userDetails['chat_status']}\n";
echo "data:         </div>\n";
echo "data:     </div>\n";
echo "data: </div>\n";




    echo "data: <div class='chat-container' id='chatMessages' style='margin-top: 60px'>\n";
    echo "data:     <ul class='chat-box chatContainerScroll' id='chatdiv' style='overflow-y: scroll;'>\n";

    $currentDate = null;

    if ($chatCount > 0) {
        echo "data: <div class='d-flex flex-column messages-bg' id='messages'>\n";
        while ($chatDetails = mysqli_fetch_assoc($chatResult)) {
            $messageDate = date('Y-m-d',$chatDetails['msg_time']);
            
            // Display date only once
            if ($messageDate != $currentDate) {
                echo "data: <div class='dt-message mx-auto my-2 bg-primary text-white small py-1 px-2 rounded'>\n";
                echo "data:     <span class='dt-message-span'>$messageDate</span>\n";
                echo "data: </div>\n";
                $currentDate = $messageDate;
            }
            if ($chatDetails['outgoing_msg_id'] != $_SESSION['id']) {
            $updtmsg = "UPDATE `messages` SET `read_status`='1' WHERE `msg_id` = '$chatDetails[msg_id]'";
                $Updtmsgexe = mysqli_query($conn,$updtmsg);
            }
            if ($chatDetails['outgoing_msg_id'] == $_SESSION['id']) {
                echo "data: <div class='align-self-end self p-1-apagar my-1 mx-5 rounded-apagar bg-white shadow-sm-apagar rounded-7-5 m-p-r m-shadow message-item tail'>\n";
                echo "data: <span class='tail-container'></span>\n";
                echo "data: <div class='d-flex flex-row'>\n";
                echo "data: <div class='body mr-2'>{$chatDetails['msg']}</div>\n";
                echo "data: <div class='time ml-auto small text-right flex-shrink-0 align-self-end text-muted' style='position: relative; top: 7px; font-size: 11px;'>\n";
                echo "data: " . date('h:i', $chatDetails['msg_time']) . " <i class='fas fa-check-circle' style='color:{$clr}'></i>\n";
                echo "data: </div>\n";
                echo "data: </div>\n";
                echo "data: </div>\n";
            } else {
                
                echo "data: <div class='align-self-start p-1-apagar my-1 mx-5 rounded-apagar bg-white shadow-sm-apagar rounded-7-5 m-p-r m-shadow message-item tail' style='margin-left: 8px!important;'>\n";
                echo "data:     <span class='tail-container'></span>\n";
                echo "data:     <div class='d-flex flex-row'>\n";
                echo "data:         <div class='body mr-2'>{$chatDetails['msg']}</div>\n";
                echo "data:         <div class='time ml-auto small text-right flex-shrink-0 align-self-end text-muted' style=' position: relative; top: 7px; font-size: 11px;'>\n";
                echo "data:             " . date('h:i', $chatDetails['msg_time']) . " \n";
                echo "data:         </div>\n";
                echo "data:     </div>\n";
                echo "data: </div>\n";
            }

            echo "data:         </li>\n";
        }
    } 

    echo "data:     </ul>\n";
    echo "data: </div>\n";
    echo "data: \n\n";
    ob_flush();
    flush();
}
?>
