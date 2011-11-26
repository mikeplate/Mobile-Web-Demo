<!DOCTYPE html>
<html>
    <head>
        <title>MobileAppLab Data Service</title>
        <meta name="viewport" content="user-scalable=yes, width=device-width" />
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
        <script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
        <style>
            .ui-content h2 {
                margin-top: 0px;
                font-size: 20px;
            }
            .ui-content ul {
                padding-top: 20px;
            }
            .ui-content p {
                margin-top: 5px;
            }
        </style>
    </head>
    <body>
        <div data-role="page" id="Start">
            <div data-role="header"><h1>MobileAppLab Data Service</h1></div>
            <div data-role="content">
                <p class="message">Welcome to the MobileAppLab Data Service. This serviced can be used by other web applications to store data securely in the cloud.</p>
                <ul data-role="listview">
                    <li><a href="#Login">Log in with existing account</a></li>
                    <li><a href="#Register">Register new account</a></li>
                    <li><a href="#Logout">Log out</a></li>
                </ul>
            </div>
        </div>
        <div data-role="page" id="Register">
            <div data-role="header">
                <h1>Register</h1>
                <a data-rel="back" data-icon="arrow-l" data-iconpos="left">Back</a>
            </div>
            <div data-role="content">
                <p id="RegisterMessage" class="message">Register a new user. If have registered previously, please go back and log in instead.</p>
                <form>
                    <label>Username</label>
                    <input type="text" id="RegisterUsername" placeholder="Choose your own username" />
                    <label>Password</label>
                    <input type="password" id="RegisterPassword" placeholder="Specify a password" />
                    <label>E-mail address</label>
                    <input type="text" id="RegisterEmail" placeholder="Specify your e-mail address" />
                    <input type="button" id="RegisterButton" value="Register" />
                </form>
            </div>
        </div>
        <div data-role="page" id="Login">
            <div data-role="header">
                <h1>Log in</h1>
                <a data-rel="back" data-icon="arrow-l" data-iconpos="left">Back</a>
            </div>
            <div data-role="content">
                <p id="LoginMessage" class="message">Log in with an existing account</p>
                <form>
                    <label>Username</label>
                    <input type="text" id="Username" placeholder="Type your username" />
                    <label>Password</label>
                    <input type="password" id="Password" placeholder="Type your password" />
                    <input type="button" id="LoginButton" value="Log in" />
                </form>
            </div>
        </div>
        <div data-role="page" id="Logout">
            <div data-role="header">
                <h1>Log out</h1>
                <a data-rel="back" data-icon="arrow-l" data-iconpos="left">Back</a>
            </div>
            <div data-role="content">
                <p id="LogoutMessage" class="message">Log out from the data service, making the data unavailable until you log in again.</p>
                <form>
                    <input type="button" id="LogoutButton" value="Log out" />
                </form>
            </div>
        </div>

        <script>
        var callbackUrl = "<?php if (isset($_SERVER['HTTP_REFERER'])) echo $_SERVER['HTTP_REFERER'];  ?>";
        var isLoggedIn = <?php echo isset($_SESSION['username']) ? 'true':'false' ?>;

        function onError(xhr, err, statusText) {
            if (err=="error" && statusText)
                err = statusText;
            err = "An error occurred! " + err;
            if (lastMessageId!=null)
                document.getElementById(lastMessageId).innerHTML = err;
            else
                alert(err);
        }

        function goToCallback() {
            if (callbackUrl)
                window.location.href = callbackUrl;
            else
                $.mobile.changePage("#Start");
        }

        // Use custom function to show a message in a specified element and remember the id
        // and display any upcoming ajax error messages in the same element.
        var lastMessageId = null;
        function showMessage(messageId, messageText) {
            var msg = document.getElementById(messageId);
            if (!msg.defaultMessage)
                msg.defaultMessage = msg.innerHTML;
            msg.innerHTML = messageText;
            lastMessageId = messageId;

            msg.style.backgroundColor = "#E0E000";
            window.setTimeout(function() {
                msg.style.MozTransition = "background-color 3s";
                msg.style.WebkitTransition = "background-color 3s";
                msg.style.backgroundColor = "";
                window.setTimeout(function() {
                    msg.style.MozTransition = "";
                    msg.style.WebkitTransition = "";
                    msg.innerHTML = msg.defaultMessage;
                }, 3000);
            }, 100);
        }

        // Initialization when page is loaded
        $(function() {
            // Set default ajax settings for jQuery
            $.ajaxSetup({
                dataType: "json",
                error: onError
            });
        });

        function register(username, password, email, success) {
            $.ajax({
                url: 'api/register.php',
                data: {
                    username: username,
                    password: password,
                    email: email
                },
                type: 'post',
                success: success
            });
        }

        function login(username, password, success) {
            $.ajax({
                url: 'api/login.php',
                data: {
                    username: username,
                    password: password
                },
                type: 'post',
                success: success
            });
        }

        function logout(success) {
            $.ajax({
                url: "api/logout.php",
                success: success
            });
        }

        // When Register button is pressed
        $("#RegisterButton").click(function() {
            var username = $("#RegisterUsername").val();
            var password = $("#RegisterPassword").val();
            if (username.length==0 || password.length==0) {
                showMessage("RegisterMessage", "You must specify a requested username and password to register!");
                return;
            }
            showMessage("RegisterMessage", "Sending register request to server...");
            register(username, password, $("#RegisterEmail").val(), function() {
                login(username, password, goToCallback);
            });
        });

        // When Login button is pressed
        $("#LoginButton").click(function() {
            var username = $("#Username").val();
            var password = $("#Password").val();
            if (username.length==0 || password.length==0) {
                showMessage("LoginMessage", "You must specify a username and password to login!");
                return;
            }
            showMessage("LoginMessage", "Sending login request to server...");
            login(username, password, goToCallback);
        });

        // When Logout button is pressed
        $("#LogoutButton").click(function() {
            showMessage("LogoutMessage", "Sending logout request to server...");
            logout(goToCallback);
        });
        </script>
    </body>
</html>

