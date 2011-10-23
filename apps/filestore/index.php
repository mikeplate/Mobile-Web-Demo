<!DOCTYPE html>
<html>
    <head>
        <title>File Store Demo</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="https://jquery-json.googlecode.com/svn/trunk/src/jquery.json.min.js"></script>
        <script src="jquery.tmpl.min.js"></script>
    </head>
    <body>
        <h1>File Store Demo</h1>
        <div class="box">
            <label>Username</label>
            <input type="text" id="RegisterUsername" placeholder="Choose your own username" />
            <label>Password</label>
            <input type="password" id="RegisterPassword" placeholder="Specify a password" />
            <label>E-mail address</label>
            <input type="text" id="Email" placeholder="Specify your e-mail address" />
            <input type="button" id="Register" value="Register" />
        </div>
        <div class="box">
            <label>Username</label>
            <input type="text" id="Username" placeholder="Type your username" />
            <label>Password</label>
            <input type="password" id="Password" placeholder="Type your password" />
            <input type="button" id="Login" value="Login" />
        </div>
        <div class="box">
            <input type="button" id="Me" value="About Me" />
            <input type="button" id="Write" value="Write" />
            <input type="button" id="Read" value="Read" />
        </div>

        <script>
        function onError(xhr, status, err) {
            if (status=="error" && xhr.statusText && xhr.statusText!="OK")
                alert("Error: " + xhr.statusText);
            else
                alert("An error occurred!");
        }

        $("#Register").click(function() {
            $.ajax({
                url: 'api/register.php',
                data: {
                    username: 'mikeplate',
                    password: 'abcdef',
                    email: 'mikael@plate.se'
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    alert("Yay!");
                },
                error: onError
            });
        });
        $("#Login").click(function() {
            $.ajax({
                url: 'api/login.php',
                data: {
                    username: 'mikeplate',
                    password: 'abcdef'
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    alert("Yay!");
                },
                error: onError
            });
        });
        $("#Me").click(function() {
            $.ajax({
                url: 'api/me.php',
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    alert("Hello " + data.username);
                },
                error: onError
            });
        });
        $("#Write").click(function() {
            obj = [
                { pos: 1, name: 'A' },
                { pos: 2, name: 'B' },
                { pos: 3, name: 'C' }
            ];
            $.ajax({
                url: 'api/write.php',
                data: {
                    name: "generic",
                    value: $.toJSON(obj)
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    alert("Yay!");
                },
                error: onError
            });
        });
        $("#Read").click(function() {
            $.ajax({
                url: 'api/read.php',
                type: 'get',
                data: {
                    name: "generic"
                },
                dataType: 'json',
                success: function(data) {
                    alert("Yay! Got " + data.length + " items.");
                },
                error: onError
            });
        });
        </script>
    </body>
</html>

