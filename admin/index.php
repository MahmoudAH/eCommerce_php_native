<?php
session_start();
$nonavbar = '';
$pageTitle = 'login';
//print_r($_SESSION['username']);
include 'init.php';

//checking if user coming fromm http post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['pass'];
    $hashedpass = sha1($pass);
    // echo 'your user name is ' . ' ' . $user . ' ' . $hashedpass;

    // checking if user exist in database
    $stm = $connect->prepare("SELECT 
                                  UserID,Username,Password
                            FROM 
                                users
                             WHERE
                                Username =?
                            AND 
                                  Password =? 
                            AND GroupID=1
                            LIMIT 1");
    $stm->execute(array($username, $pass));
    $row = $stm->fetch();
    $count = $stm->rowCount();

    //check if count >0 this meaning that database containig record
    if ($count > 0) {
        // echo 'welcome ' . $username;
        $_SESSION['Username'] = $username; //register session username
        $_SESSION['id'] = $row['UserID']; //register session userid
        header('location: dashboard.php');
        exit;
    }
}

?>


<div class=" position-absolute top-50 start-50 translate-middle ">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h3>Admin Login</h3>
        <div class="mb-3>
            <label for=" exampleInputEmail1" class="form-label">User Name</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>
        <div class="mb-3 ">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="pass">
        </div>
        <button type="submit" class="btn btn-primary form-control">Submit</button>
    </form>
</div>

<?php
include $temp . '/footer.php';
?>