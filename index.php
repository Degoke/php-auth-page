<?php
session_start()
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <h2>Register</h2>
        <form action='index.php' method='POST'>
            <label>Email Address
                <input type='email' name='email' />
            </label>
            <label>FullName
                <input type='text' name='name' />
            </label>
            <label>Password
                <input type='password' name='password' />
            </label>
            <input type='submit' name='register' value='Register' />
        </form>
    </div>
    <div>
        <h1>Login</h1>
        <form action='index.php' method='POST'>
            <label>Email Address
                <input type='email' name='email' />
            </label>
            <label>Password
                <input type='password' name='password' />
            </label>
            <input type='submit' name='login' value='Login' />
        </form>
    </div>

</body>

</html>

<?php

if (isset($_POST['register']) == 'Register') {

    ##Form Validation
    $error_message = '';

    if (empty($_POST['email'])) {
        $error_message .= '<li> Please Enter your Email Address </li>';
    }

    if (empty($_POST['name'])) {
        $error_message .= '<li> Please Enter your Full Name </li>';
    }

    if (empty($_POST['password'])) {
        $error_message .= '<li> Please Enter your Password </li>';
    }

    if (!empty($error_message)) {
        echo ("<p>There was an Error with your form</p>\n");
        echo ("<ul>$error_message</ul>\n");
    } else {
        ##handle registration

        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = $_POST['password'];

        $user_data = [
            'email' => $email,
            'name' => $name,
            'password' => $password
        ];

        if (file_exists("users/$email.json")) {
            echo ('<h3>User already Exists</h3>');
        } else {
            file_put_contents("users/$email.json", json_encode($user_data));
            echo ('<h3>Registration successfull, Login to continue</h3>');
        }
    }
}


## Handle login
if (isset($_POST['login']) == 'Login') {

    ##Form Validation
    $error_message = '';

    if (empty($_POST['email'])) {
        $error_message .= '<li> Please Enter your Email Address </li>';
    }

    if (empty($_POST['password'])) {
        $error_message .= '<li> Please Enter your Password </li>';
    }

    if (!empty($error_message)) {
        echo ("<p>There was an Error with your form</p>\n");
        echo ("<ul>$error_message</ul>\n");
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        ##check if file exists and handle login
        if (file_exists("users/$email.json")) {
            $user_data = json_decode(file_get_contents("users/$email.json"), true);
            if ($user_data['password'] == $password) {
                $_SESSION['name'] = $user_data['name'];
                echo ("<h2>Welcome " . $_SESSION['name'] . "</h2><br/>");
                echo ("<form action='index.php' method='POST'><input type='submit' name='logout' value='Logout' /></form><br/>");
                echo ('<h3>Reset Password</h3>');
                echo ("<form action='index.php' method='POST'><label>Email Address <input type='email' name='email' /></label><label> New Password <input type='password' name='password' /></label><input type='submit' name='reset' value='Reset' /></form>");
            } else {
                echo ('<h3>Invalid Password</h3>');
            }
        } else {
            echo ('<h3>user does not Exist, Register to continue</h3>');
        }
    }
}

##Handle password reset
if (isset($_POST['reset']) == 'Reset') {
    if (empty($_POST['password'])) {
        echo ('Please enter a password');
    } else {
        $user_data = json_decode(file_get_contents("users/$_POST[email].json"), true);
        $user_data['password'] = $_POST['password'];
        file_put_contents("users/$user_data[email].json", json_encode($user_data));
        echo ('Password successfully Reset');
    }
}

## Handle logout
if (isset($_POST['logout']) == 'Logout') {
    session_destroy();
}

?>