<?php
session_start();
//$_SESSION['started'] = time();

if (isset($_SESSION['started']))
{
$var = (time() - $_SESSION['started'] - 60); //60 seconds
//echo $var;
if($var > 0){
    //Logout, destroy session, etc.
    session_destroy();
    header("location:main.php");
}
else {
    $_SESSION['started'] = time();
    //echo '<h2>You <strong>ARE</strong> logged in.</h2>';
    //header("location:main.php");
}
}
//DB Connection information
$dbname="nova";
$dbuser="root";
$dbpwd="";
$host="localhost";
// Connect to the database
$cid = mysql_connect($host,$dbuser,$dbpwd);
if (!$cid) { print "ERROR: " . mysql_error() . "n";    }
mysql_select_db("$dbname") or die(mysql_error());

switch ( $_GET['action'] ) {
case "logout":
    session_destroy();
    header("location:main.php");
    break;
case "no":
    echo '<h2>You <strong>NOT</strong> loged in.</h2>';
    break;
case "yes":
    echo '<h2>You <strong>ARE</strong> loged in.</h2>';
    break;
case "check":
    $username=$_POST['username'];
    $password=$_POST['password'];
    $clean_username = strip_tags(stripslashes(mysql_real_escape_string($username)));
    $clean_password = sha1(strip_tags(stripslashes(mysql_real_escape_string($password))));
    $sql="SELECT * FROM users WHERE username='$clean_username' and password='$clean_password'";
    $rs = mysql_query($sql) or die ("Query failed");
    $numofrows = mysql_num_rows($rs);
    if($numofrows==1){
        $_SESSION['username'] = $username;
        $_SESSION['started'] = time();
        $_POST['action'] = 'yes';
        header("location:main.php");
        //header("location:main.php?action=yes");
    }
    else {
        header("location:main.php?action=no");
    }
default:
    if($_SESSION['started']) {
        echo '<h2>You <strong>ARE</strong> loged in.</h2>';
        break;
    }
if(isset($_SESSION['username'])){
    echo $username;
    //$_POST['action'] = 'yes';
    //header("location:main.php");
    //header("location:main.php?action=yes");
}
?>
<form name="form" method="post" action="main.php?action=check">
    <h4>Member Login:</h4>

    <p><label for="username">Username:</label><br />
        <input type="text" name="username" /></p>
    <p><label for="password">Password:</label><br />
        <input type="password" name="password" /></p>
    <p><input type="submit" value="Login" name="Submit" /></p>
</form>
<?
break;
}
?>
    <p><a href="main.php">Log-in</a> | <a href="main.php?action=logout">Log-out</a></p>