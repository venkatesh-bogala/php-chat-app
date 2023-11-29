<?php 
session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include_once "./config.php"; 

if (isset($_REQUEST['q'])) {
    
    $id = $_REQUEST['q'];
    if ($_SESSION['role'] == 'admin') {
        $users = "SELECT * FROM users WHERE chat_user_id != '$id' AND role = 'manager'";
    } elseif ($_SESSION['role'] == 'manager') {
        $users = "SELECT * FROM users WHERE (role = 'team_lead' AND mg_id = '$id') OR (role = 'admin')";
    } elseif ($_SESSION['role'] == 'team_lead') {
        $users = "SELECT * FROM users WHERE (role = 'agent' AND tl_id = '$id') OR (chat_user_id = (SELECT mg_id FROM users WHERE chat_user_id = '$id'))";
    } elseif ($_SESSION['role'] == 'agent') {
        $users = "SELECT * FROM users WHERE (chat_user_id = (SELECT tl_id FROM users WHERE chat_user_id = '$id'))";
    }

    $userqryexe = mysqli_query($conn, $users);
    $count = mysqli_num_rows($userqryexe);

    if ($count > 0) {
        while ($row = mysqli_fetch_assoc($userqryexe)) {
            if ($row['chat_status'] == 'Active now') {
                $status = 'online';
                $clr = 'green';
            } else {
                $status = 'offline';
                $clr = 'red';
            }
            $msgs = "select * from messages where (incoming_msg_id = $_SESSION[id] and outgoing_msg_id = '$row[chat_user_id]') OR ( incoming_msg_id = '$row[chat_user_id]' and outgoing_msg_id = $_SESSION[id] ) order by msg_id desc limit 1"; 
            $msgexe = mysqli_query($conn,$msgs);
            $no = 0;
            $lastmsg = '';
            while($msgdtls = mysqli_fetch_assoc($msgexe)){
                $lastmsg = $msgdtls['msg'];
                $lastmsgtime = date("h:i",$msgdtls['msg_time']);
                $status = $msgdtls['read_status'];
                $senderid = $msgdtls['outgoing_msg_id'];
                if($status == 0){
                    $no++;
                }
            }
            echo "data: <div id='chat-item' class='chat-list-item d-flex flex-row w-100 p-2 border-bottom-2 _border-bottom' onclick='chatfunc(" . $row['chat_user_id'] . ")'>\n";
            echo "data: <img src='./images/images.jpg' alt='Profile Photo' class='img-fluid rounded-circle mx-2 mr-2' style='height:50px;'>\n";
            echo "data: <div class='w-50'>\n";
            echo "data: <div class='name'>{$row["name"]}</div>\n";
            if($status == 0 && $senderid != $_SESSION['id']){
                echo "data: <div class='small last-message'><strong>{$lastmsg}</strong></div>\n";
            }else{
                echo "data: <div class='small last-message'>{$lastmsg}</div>\n";
            }
            
            echo "data: </div>\n";
            echo "data: <div class='flex-grow-1 text-right'>\n";
            echo "data: <div class='small time'>{$lastmsgtime}</div>\n";
            if($no > 0 && $senderid != $_SESSION['id']){
            echo "data: <div class='badge badge-success badge-pill small' id='unread-count'>{$no}</div>\n";
            }
            echo "data: </div>\n";
            echo "data: </div>\n";
        }

        // End the event stream
        echo "data: \n\n";
        flush();
    }
}
?>
