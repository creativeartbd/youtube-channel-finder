<?php include_once "../includes/inc.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .login-wrapper {
            font-family: Arial, Helvetica, sans-serif;
            width: 500px;
            max-width: 100%;
            border: 1px solid #ddd;
            padding: 25px;
            margin : 0 auto;
        }

        .login-wrapper table {
            margin-top: 30px;
        }

        .login-wrapper table tr {
            margin-bottom: 30px;
        }

        input {
            width: -webkit-fill-available;
            border-radius: 8px;
            -webkit-border-radius: 8px;
            padding: 15px;
            outline: none;
            border: 1px solid #ddd;
            font-size: 14px;
            color: #000000;
        }

        .i_login_button {
            width: 100%;
            text-align: center;
            color: #ffffff;
            border-radius: 8px;
            -webkit-border-radius: 8px;
            background-color: #f65169;
            transition: all 0.25s ease;
            margin-top: 15px;
        }

        .i_login_button input[type="submit"] {
            outline: none;
            border: 0px solid #ddd;
            background-color: #ff4e4e;
            width: 100%;
            padding: 15px 10px;
            display: flex;
            display: -webkit-flex;
            font-weight: 500;
            font-size: 15px;
            font-family: "Noto Sans", sans-serif;
            color: #ffffff;
            text-align: center;
            justify-content: center;
            cursor: pointer;
        }
        .error {
            background : #ff4e4e;
            border : none; 
            color : #fff;
            padding : 15px;
            margin-bottom : 10px;
        }
    </style>
</head>
<body>


<div class="login-wrapper">
<?php
if(!isset($_SESSION['iuid'])) {

 if(isset($_POST['username']) && isset($_POST['password'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, sha1(md5($_POST['password'])));
    
    /*Check username and Password is true*/
    $query = mysqli_query($db,"SELECT * FROM `i_users` WHERE (i_username = '$username' OR i_user_email = '$username') AND i_password = '$password'") or die(mysqli_error($db));
    $uData = mysqli_fetch_array($query, MYSQLI_ASSOC);
    $userID = $uData['iuid'];
    $userType = $uData['userType'];
    $userUsername = $uData['i_username'];
    $userEmail = $uData['i_user_email'];
    $userPassword = $uData['i_password'];

    if(mysqli_num_rows($query) == '1' && $userType == 2 ){
      $time = time();
      mysqli_query($db, "UPDATE i_users SET last_login_time = '$time' WHERE iuid = '$userID'") or die(mysqli_error($db));
      $hash = sha1($userUsername).$time;
      setcookie($cookieName,$hash,time()+31556926 ,'/');
      $saveLogin = mysqli_query($db, "INSERT INTO `i_sessions`(session_uid, session_key, session_time) VALUES ('$userID','$hash', '$time')") or die(mysqli_error($db));
      $_SESSION['iuid'] = $userID;
      $_SESSION['userType'] = $userType;
      if($saveLogin){
        header("Location:search.php");
        exit();
      } 
    }else{
        echo '<div class="error">There is no user with these details! You do not have an account? Click <a href="'.$base_url.'">HERE</a> to create an account.</div>';
    }
 }else{
    echo '<div class="error">Please feel all fealds!</div>';
 }
?>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <table width=100%>
            <tr>
                <td>Username</td>
            </tr>
            <tr>
                <td><input type="text" name="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>"></td>
            </tr>
            <tr>
                <td>Password</td>
            </tr>
            <tr>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td class="i_login_button" colspan="2"><input type="submit" value="submit" name="Log In"></td>
            </tr>
        </table>
    </form>
    <?php } else { 
        header("Location:search.php");
        exit();
    } ?>
</div>

</body>
</html>