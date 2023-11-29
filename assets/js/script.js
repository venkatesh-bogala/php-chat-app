
    document.addEventListener('DOMContentLoaded', function() {
        var userEventSource = new EventSource('get_user.php?q=' + document.getElementById("userid").value);

        userEventSource.onmessage = function(event) {
            const chatContainer = document.getElementById("chat-list");
            chatContainer.innerHTML = "";
            
            document.getElementById("chat-list").innerHTML += event.data + "<br>";
        };

    });
	var currentEventSource = null;
    var currentData = "";
	function chatfunc(id){
		if (currentEventSource) {
            currentEventSource.close();
        }
debugger;
        document.getElementById("receiverid").value = id;

        currentEventSource = new EventSource('chat.php?q=' + id);

        currentEventSource.onmessage = function(event) {
            const newData = event.data; 

            if (newData !== currentData) {
               
                currentData = newData;

                const chatContainer = document.getElementById("messages");
                chatContainer.innerHTML = currentData;

               chat = document.getElementById("messages");
            chat.scrollTop = chat.scrollHeight;
            }
        };

        document.getElementById("input-area1").style.display = 'flex';
	}
	function sendMessage(){
		    var msg = document.getElementById('input').value;
		    var senderid = document.getElementById("userid").value;
            var receiverid = document.getElementById("receiverid").value; // Make sure you have an element with id 'receiverid' in your HTML
            

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("input").value = '';
                    chat = document.getElementById("messages");
            chat.scrollTop = chat.scrollHeight;
                }
            };
            xmlhttp.open("GET", "insertchat.php?senderid=" + senderid + "&receiverid=" + receiverid + "&msg=" + encodeURIComponent(msg), true);
            xmlhttp.send();
	}