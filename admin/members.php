<?php

/**
 ===========================================================
 ==
 ==manage members page
 =========================================================== 
 */
session_start();
$pageTitle = 'members';

if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    //$query =isset($_GET['page']) && $_GET['page'] == 'pending')? 'AND RegisterStatus = 0' : '' ;
    $query = '';
    if (isset($_GET['page']) && $_GET['page'] == 'pending') {
        $query = 'AND RegisterStatus =0 ';
    }
    if ($do == 'Manage') {
        $stm = $connect->prepare(" SELECT * FROM users WHERE GroupID != 1 $query");
        $stm->execute();
        $users = $stm->fetchAll();


?>
<h1 class="text-center">All Members</h1>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Registered Date</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
            <tr>
                <th scope="row"><?php echo $user['UserID'] ?></th>
                <td><?php echo $user['Username'] ?></td>
                <td><?php echo $user['Email'] ?></td>
                <td><?php echo $user['Date'] ?></td>
                <td>
                    <a class="btn btn-success" href="members.php?do=Edit&userid=<?php echo $user['UserID']; ?>">Edit</a>
                    <a class="btn btn-danger confirm"
                        href="members.php?do=Delete&userid=<?php echo $user['UserID']; ?>">Delete</a>
                    <?php
                                if ($user['RegisterStatus'] == 0) { ?>
                    <a class="btn btn-secondary"
                        href="members.php?do=Activate&userid=<?= $user['UserID'] ?>">Activate</a>
                    <?php  }
                                ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="members.php?do=Add" type="button" class="btn btn-primary text-center">Add New Member</a>

</div>
<?php } elseif ($do == 'Add') { ?>
<h1 class="text-center">Add Members</h1>
<div class="container">
    <form action="?do=Insert" method="POST">
        <div class="form-group">
            <label for="exampleInputEmail1">Name </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name" name="username" required>

        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email </label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter email" name="useremail" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control password" id="exampleInputPassword1" placeholder="Password"
                name="password" autocomplete="new-password" required>
            <span class="show-pass fa fa-eye fa-2x"></span>
        </div>

        <button type="submit" class="btn btn-primary" value="Add Member">Submit</button>
    </form>

</div>
<?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['username'];
            $email = $_POST['useremail'];
            $password = $_POST['password'];
            $hashedpass = sha1($_POST['password']);
            //validate
            $formError = [];
            if (strlen($name) < 3) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                name can not less than 3 char </div>';
            }
            if (empty($name)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                name can not be empty</div>';
            }

            if (empty($email)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                email can not be empty</div>';
            }

            //loop into erroraray
            foreach ($formError as $error) {
                echo $error . '<br/>';
            }
            //check if errorform is empty
            if (empty($formError)) {

                $stm = $connect->prepare("INSERT  INTO users(Username,Email,Password,Date,RegisterStatus)
                                         VALUES (:zname,:zemail,:zhashedpass,now(),1 )");
                $stm->execute(array(
                    'zname' => $name,
                    'zemail' => $email,
                    'zhashedpass' => $hashedpass,
                ));

                $msg = '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> User Added</h3> 
                      </div>';
                redirectHome($msg);
            }
        }
    } elseif ($do == 'Edit') {

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stm = $connect->prepare("SELECT *
                                 FROM users
                                 WHERE UserID =? LIMIT 1  ");
        $count = $stm->execute(array($userid));
        $row = $stm->fetch();
        $count = $stm->rowCount();
        if ($count > 0) { ?>

<h1 class="text-center">Edit Members</h1>
<div class="container">
    <form action="?do=Update" method="POST">
        <input type="hidden" name="userid" value="<?php echo $userid ?>">
        <div class="form-group">
            <label for="exampleInputEmail1">Name </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name" name="username" value="<?php echo $row['Username'] ?>" required>

        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email </label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter email" name="useremail" value="<?php echo $row['Email'] ?>" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"
                name="newpassword" autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>
<?php
        } else {
            echo 'no id exist';
        }
    } elseif ($do == 'Update') {
        echo '<h1 class="text-center">Update Members</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['userid'];
            $name = $_POST['username'];
            $email = $_POST['useremail'];
            //password 
            $pass = empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);

            //validate
            $formError = [];
            if (strlen($name) < 3) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                name can not less than 3 char </div>';
            }
            if (empty($name)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                name can not be empty</div>';
            }

            if (empty($email)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                email can not be empty</div>';
            }

            //loop into erroraray
            foreach ($formError as $error) {
                echo $error . '<br/>';
            }
            //check if errorform is empty
            if (empty($formError)) {

                $stm = $connect->prepare("UPDATE users SET 
                                     Username = ? , Email = ?, Password = ?  WHERE UserID = ? ");
                $stm->execute(array($name, $email, $pass, $id));
                $msg = '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> user info updated</h3> 
                      </div>';
                redirectHome($msg);
            }
        } else {
            echo 'you can not access page direct';
        }
    } elseif ($do == 'Delete') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stm = $connect->prepare("SELECT *
                                 FROM users
                                 WHERE UserID =? LIMIT 1  ");
        $count = $stm->execute(array($userid));
        $count = $stm->rowCount();
        if ($count > 0) {
            //$stm = $connect->prepare("DELETE FROM users WHERE UserID =? ");
            // $stm->execute(array($userid));
            $stm = $connect->prepare("DELETE FROM users WHERE UserID =:zid");
            $stm->bindparam('zid', $userid);
            $stm->execute();
            echo '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> user deleted!!</h3> 
                      </div>';
        } else {
            echo 'id not exists';
        }
    } elseif ($do == 'Activate') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stm = $connect->prepare("SELECT *
                                 FROM users
                                 WHERE UserID =? LIMIT 1");
        $count = $stm->execute(array($userid));
        $count = $stm->rowCount();
        if ($count > 0) {
            $stm = $connect->prepare("UPDATE users SET RegisterStatus =1 WHERE UserID =? ");
            $stm->execute(array($userid));

            $stm->execute();
            echo '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> user activated!!</h3> 
                      </div>';
        } else {
            echo 'user not found';
        }
    }
    include $temp . '/footer.php';
} else {
    header('location: index.php');
    exit;
}