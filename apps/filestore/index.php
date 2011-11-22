<!DOCTYPE html>
<html>
    <head>
        <title>File Store Demo</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    </head>
    <body>
        <h1>File Store Demo</h1>
        <div class="box">
            <label>Username</label>
            <input type="text" id="RegisterUsername" placeholder="Choose your own username" />
            <label>Password</label>
            <input type="password" id="RegisterPassword" placeholder="Specify a password" />
            <label>E-mail address</label>
            <input type="text" id="RegisterEmail" placeholder="Specify your e-mail address" />
            <label id="RegisterMessage" class="message"></label>
            <input type="button" id="Register" value="Register" />
        </div>
        <div class="box">
            <label>Username</label>
            <input type="text" id="Username" placeholder="Type your username" />
            <label>Password</label>
            <input type="password" id="Password" placeholder="Type your password" />
            <label id="LoginMessage" class="message"></label>
            <input type="button" id="Login" value="Log in" />
        </div>
        <div class="box">
            <label id="MeMessage" class="message"></label>
            <input type="button" id="Me" value="About Me" />
        </div>
        <div class="box">
            <label id="WriteMessage" class="message"></label>
            <input type="button" id="Write" value="Write" />
        </div>
        <div class="box">
            <label id="ReadMessage" class="message"></label>
            <input type="button" id="Read" value="Read" />
        </div>
        <div class="box">
            <label id="ListMessage" class="message"></label>
            <input type="button" id="List" value="List" />
        </div>
        <div class="box">
            <label id="LogoutMessage" class="message"></label>
            <input type="button" id="Logout" value="Log out" />
        </div>

        <script>
        function onError(xhr, err, statusText) {
            if (err=="error" && statusText)
                err = statusText;
            err = "An error occurred! " + err;
            if (lastMessageId!=null)
                document.getElementById(lastMessageId).innerHTML = err;
            else
                alert(err);
        }

        // Use custom function to show a message in a specified element and remember the id
        // and display any upcoming ajax error messages in the same element.
        var lastMessageId = null;
        function showMessage(messageId, messageText) {
            document.getElementById(messageId).innerHTML = messageText;
            lastMessageId = messageId;
        }

        // Initialization when page is loaded
        $(function() {
            // Set default ajax settings for jQuery
            $.ajaxSetup({
                dataType: "json",
                error: onError
            });
        });

        // When Register button is pressed
        $("#Register").click(function() {
            var username = $("#RegisterUsername").val();
            var password = $("#RegisterPassword").val();
            if (username.length==0 || password.length==0) {
                alert("You must specify a requested username and password to register!");
                return;
            }
            showMessage("RegisterMessage", "Sending register request to server...");
            $.ajax({
                url: 'api/register.php',
                data: {
                    username: username,
                    password: password,
                    email: $("#RegisterEmail").val()
                },
                type: 'post',
                success: function(data) {
                    showMessage("RegisterMessage", "Registration was successful! Now try and login with the same credentials.");
                }
            });
        });

        // When Login button is pressed
        $("#Login").click(function() {
            var username = $("#Username").val();
            var password = $("#Password").val();
            if (username.length==0 || password.length==0) {
                alert("You must specify a username and password to login!");
                return;
            }
            showMessage("LoginMessage", "Sending login request to server...");
            $.ajax({
                url: 'api/login.php',
                data: {
                    username: username,
                    password: password
                },
                type: 'post',
                success: function(data) {
                    showMessage("LoginMessage", "Log in was successful. Now try and get information about yourself.");
                }
            });
        });

        // When Me button is pressed
        $("#Me").click(function() {
            showMessage("MeMessage", "Sending me request to server...");
            $.ajax({
                url: 'api/me.php',
                type: 'get',
                success: function(data) {
                    showMessage("MeMessage", "Me was successful. Your username is " + data.username
                        + " and your registered e-mail is " + data.email + ". Now try and write some data");
                }
            });
        });

        $("#Write").click(function() {
            var sample = [
                { id: 1, name: 'A' },
                { id: 2, name: 'X' },
                { id: 7, name: 'C' }
            ];
            showMessage("WriteMessage", "Sending write request to server...");
            $.ajax({
                url: 'api/write.php',
                data: {
                    name: "sample",
                    value: JSON.stringify(sample)
                },
                type: 'post',
                success: function(data) {
                    showMessage("WriteMessage", "Write was successful. Now try and read the data.");
                }
            });
        });

        $("#Read").click(function() {
            showMessage("ReadMessage", "Sending read request to server...");
            $.ajax({
                url: 'api/read.php',
                type: 'get',
                data: {
                    name: "sample"
                },
                success: function(data) {
                    showMessage("ReadMessage", "Read was successful. The data had " + data.length + " items in it.");
                }
            });
        });

        $("#List").click(function() {
            showMessage("ListMessage", "Sending list request to server...");
            $.ajax({
                url: 'api/list.php',
                type: 'get',
                success: function(data) {
                    showMessage("ListMessage", "List was successful. You have the following items stored: " + data.join(", "));
                }
            });
        });

        $("#Logout").click(function() {
            showMessage("LogoutMessage", "Sending logout request to server...");
            $.ajax({
                url: "api/logout.php",
                success: function(data) {
                    showMessage("LogoutMessage", "Log out was successful. Now you have to login again in order to use Me, Write and Read.");
                }
            });
        });
        </script>
    </body>
</html>

