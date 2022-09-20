<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="This is a time tracker">
    <meta name="keywords" content="PHP, HTML, CSS, JavaScript, jQuery">
    <meta name="author" content="Masoud Ilderemi">


    <title>Time Tracker</title>

    <script src="assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="page">
        <!-- Login Form -->
        <form class="center">
            <div>
                <input type="email" name="email" placeholder="Email">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password">
            </div>
            <div>
                <button>Login</button>
            </div>
        </form>
        <!-- /Login Form -->

        <script>
        /**
         * Login Script
         * This should be removed after logging in
         */
        $("form").on("submit",function(event) {
            event.preventDefault();
            let params = $(this).serialize();
            var req = $.ajax({
                type: 'POST',
                dataType: 'json',
                data: params,
                url: '/api.php/api/v1/login',   // this route is used to provide a token by sending the email and the password
                timeout: 5000,
            });

            req.done(function(response){
                if (response.status==1){
                    window.localStorage.setItem('access_token', response.data.token);   // save the token on local storage
                    show_dashboard();
                }
            });

            req.fail(function(jqXHR, textStatus){
                if (jqXHR.responseJSON.status==0){
                    alert(jqXHR.responseJSON.message);
                }
            });
        });

        $(document).ready(function(){
            let token = window.localStorage.getItem('access_token');    // loads the local storage to check the token if it exists
            if (token!=='null') {
                /**
                 * if the token was existed, it runs this function to show the dashboard.
                 * this function sends an ajax request, so, it sends and verifies the token before loading the dashboard.
                 */
                show_dashboard();
            }
        });
        </script>
    </div>

    <!-- Loading the scripts -->
    <script src="assets/js/functions.js"></script>
    <script src="assets/js/timer.js"></script>
</body>
</html>