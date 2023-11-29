<?php
session_start();
include_once "config.php";
?>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Whatsapp</title>
	<link rel="stylesheet" href="./assets/framework/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="./assets/framework/fontawesome/v5.0.10/css/all.css">	
	<link rel="stylesheet" href="./assets/css/chat.css">
	<link rel="stylesheet" href="./assets/css/chat-adapter-bootstrap-4.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" rel="stylesheet"/>
	<link rel="icon" type="image/ico" href="../assets/images/favicon-64x64.ico" />
</head>

<body class="chat-body">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
	<div class="container-fluid" id="main-container">
		<div class="chat-row h-100">
            <input type='hidden' name='userid' id='userid' value='<?php echo $_SESSION['id']; ?>'>
			<div class="col-xs-12 col-sm-5 col-md-4 d-flex flex-column h-100" id="chat-list-area" style="position:relative;">
				<!-- Navbar Left-->
				<div class="chat-row d-flex flex-row align-items-center p-2" id="navbar">
					<img src="./assets/images/images.jpg" alt="Profile Photo" class="img-fluid rounded-circle mx-2 mr-2" style="height:50px; cursor:pointer;" id="display-pic">
                    <?php 
                    $sql = "select * from users where chat_user_id = '$_SESSION[id]'";
                    $sqlexe = mysqli_query($conn,$sql);
                    $sqldtls = mysqli_fetch_assoc($sqlexe);
                    ?>
					<div class="text-black font-weight-bold" id="username" style="display:block"><?php echo $sqldtls['name']; ?></div>					
					<div class="d-flex flex-row align-items-center ml-auto">
						<a href="logout.php"><i class="fa fa-power-off mx-3 text-muted d-none d-md-block"></i></a>
					</div>
				</div>
				<div id="chat-search" class="chat-row p-2" style="border-bottom: 1px solid #dadbdb;">
					<div class="form-search form-inline" style="width:100%">
					    <input type="text" class="search-query border-0" placeholder="Search or start new chat" style="width:100%; height: 32px; font-size: 14px;  border-radius: 20px;"/>
					</div>
				</div>
				<!-- Chat List -->
				<div class="chat-row" id="chat-list" style="overflow:auto;" max-height='700px'>
                   
                </div>				
			</div>
			
			<!-- Message Area -->
			<div class="d-none d-sm-flex flex-column col-xs-12 col-sm-7 col-md-8 p-0 h-100" id="message-area">
				
				<!-- Messages -->
				<div class="d-flex flex-column messages-bg" id="messages"></div>

				<!-- Input -->
				<div class="justify-self-end align-items-center flex-row" id="input-area1" style='display:none'>
					<input type='hidden' name='receiverid' id='receiverid'>
					<!-- <i class="far fa-smile text-muted px-4" style="font-size:24px; cursor:pointer;"></i> -->
					<input type="text" name="message" id="input" placeholder="Type a message" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded-20 -shadow-sm; word-wrap: break-word;">
					<i class="fa fa-paper-plane text-muted px-4" style="cursor:pointer;" onclick="sendMessage()"></i>
				</div>
			</div>
			
		</div>
	</div>
    

	<script src="./assets/js/script.js"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
