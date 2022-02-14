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
    if ($do == 'Manage') {
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $stm = $connect->prepare(" SELECT * FROM categories ORDER BY Ordering $sort");
        $stm->execute();
        $cats = $stm->fetchAll(); ?>

<div class="container categories">
    <h1 class="">All Cats</h1>
    <div class="ordering" style="float: right;">
        ordering:
        <a href="?sort=ASC" class="<?php if ($sort == 'ASC') {
                                                echo 'active';
                                            } ?>">ASC</a>
        <a href="?sort=DESC" class="<?php if ($sort == 'DESC') {
                                                echo 'active';
                                            } ?>">DESC</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Desc</th>
                <th scope="col">Ordering </th>
                <th scope="col">Visibility </th>
                <th scope="col">Allow_comments</th>
                <th scope="col">Allow_ads</th>
                <th scope="col">Actions</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($cats as $cat) : ?>
            <tr>
                <th scope="row"><?php echo $cat['ID'] ?></th>
                <td><?php echo $cat['Name'] ?></td>
                <td>
                    <?php
                                if ($cat['Description'] == '') {
                                    echo 'no desc';
                                } else {
                                    echo $cat['Description'];
                                }

                                ?>
                </td>
                <td><?php echo $cat['Ordering'] ?></td>
                <td>
                    <?php
                                if ($cat['Visibility'] == 1) {
                                    echo '<span class="btn btn-danger">Hidden</span> ';
                                }
                                ?>
                </td>
                <td>
                    <?php
                                if ($cat['Allow_Comment'] == 1) {
                                    echo '<span class="btn btn-danger">Comments disable</span> ';
                                } ?>
                </td>
                <td>
                    <?php
                                if ($cat['Allow_Ads'] == 1) {
                                    echo '<span class="btn btn-danger">Ads disable</span> ';
                                }
                                ?>
                </td>
                <td>
                    <a class="btn btn-success" href="categories2.php?do=Edit&catid=<?php echo $cat['ID']; ?>">Edit</a>
                    <a class="btn btn-danger confirm"
                        href="categories2.php?do=Delete&catid=<?php echo $cat['ID']; ?>">Delete
                    </a>

                </td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="categories.php?do=Add" type="button" class="btn btn-primary text-center">Add New Cat</a>

</div>
<?php } elseif ($do == 'Add') { ?>
<h1 class="text-center">Add New Category</h1>
<div class="container">
    <form action="?do=Insert" method="POST">
        <div class="form-group">
            <label for="exampleInputEmail1">Name </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name of cat" name="name" required>

        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Desc </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name" name="description" required>

        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Ordering </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name" name="ordering" required>

        </div>
        <div class="form-group mb-2">

            <label for="vis-yes">Visible </label>
            <input id="vis-yes" type="radio" name="visibility" value="0" checked>
            <label for="vis-no">Not Visible </label>
            <input id="vis-no" type="radio" name="visibility" value="1">

        </div>

        <div class="form-group mb-2">
            <label for="comment-yes">Allow Comment </label>
            <input id="comment-yes" type="radio" name="comments" value="0" checked>
            <label for="comment-no">Not comment </label>
            <input id="comment-no" type="radio" name="comments" value="1">

        </div>
        <div class="form-group mb-2">
            <label for="ads-yes">Allow Ads </label>
            <input id="ads-yes" type="radio" name="ads" value="0" checked>
            <label for="ads-no">Not ads</label>
            <input id="ads-no" type="radio" name="ads" value="1">

        </div>

        <button type="submit" class="btn btn-primary" value="Add Member">Submit</button>
    </form>

</div>
<?php

    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $ordering = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];

            //validate

            //check if name is empty
            $check = checkItem('Name', 'categories', $name);
            if ($check == 1) {

                $msg = '<div class="alert alert-success" role="alert">
                          <h3 class="text-center"> cateory is exist</h3> 
                          </div>';
                redirectHome($msg);
            } else {

                $stm = $connect->prepare("INSERT INTO 
                                             categories(Name,Description,Ordering,Visibility,Allow_Comment,Allow_Ads)
                                             VALUES (:zname,:zdesc,:zordering,:zvisibility,:zcomments,:zads)");
                $stm->execute(array(
                    'zname' => $name,
                    'zdesc' => $desc,
                    'zordering' => $ordering,
                    'zvisibility' => $ordering,
                    'zcomments' => $comments,
                    'zads' => $ads,

                ));


                $msg = '<div class="alert alert-success" role="alert">
                          <h3 class="text-center"> Category Added</h3> 
                          </div>';
                redirectHome($msg);
            }
        }
    } elseif ($do == 'Edit') {

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $stm = $connect->prepare("SELECT *
                                     FROM categories
                                     WHERE ID =?");
        $count = $stm->execute(array($catid));
        $row = $stm->fetch();
        $count = $stm->rowCount();
        if ($count > 0) { ?>

<h1 class="text-center">Edit Categories</h1>
<div class="container">
    <form action="?do=Update" method="POST">
        <input type="hidden" name="catid" value="<?php echo $catid ?>">

        <div class="form-group">
            <label for="exampleInputEmail1">Name </label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter name of cat" name="name"
                value="<?php echo $row['Name'] ?>" required>

        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Desc </label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter name" name="description"
                value="<?php echo $row['Description'] ?>" required>

        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Ordering </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name" name="ordering" value="<?php echo $row['Ordering'] ?>" required>

        </div>
        <div class="form-group mb-2">

            <label for="vis-yes">Visible </label>
            <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($row['Visibility'] == 0) {
                                                                                            echo 'checked';
                                                                                        } ?>>
            <label for=" vis-no">Not Visible </label>
            <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($row['Visibility'] == 1) {
                                                                                        echo 'checked';
                                                                                    } ?>>

        </div>

        <div class=" form-group mb-2">
            <label for="comment-yes">Allow Comment </label>
            <input id="comment-yes" type="radio" name="comments" value="0" <?php if ($row['Allow_Comment'] == 0) {
                                                                                            echo 'checked';
                                                                                        } ?>>
            <label for="comment-no">Not comment </label>
            <input id="comment-no" type="radio" name="comments" value="1" <?php if ($row['Allow_Comment'] == 1) {
                                                                                            echo 'checked';
                                                                                        } ?>>

        </div>
        <div class="form-group mb-2">
            <label for="ads-yes">Allow Ads </label>
            <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($row['Allow_Ads'] == 0) {
                                                                                    echo 'checked';
                                                                                } ?>>
            <label for="ads-no">Not ads</label>
            <input id="ads-no" type="radio" name="ads" value="1" <?php if ($row['Allow_Ads'] == 1) {
                                                                                    echo 'checked';
                                                                                } ?>>

        </div>

        <button type="submit" class="btn btn-primary" value="Add Member">Submit</button>
    </form>
</div>
<?php
        } else {
            echo 'no id exist';
        }
    } elseif ($do == 'Update') {
        echo '<h1 class="text-center">Update Members</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $catid = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $comment = $_POST['comments'];
            $ads = $_POST['ads'];


            $stm = $connect->prepare("UPDATE 
                                           categories
                                      SET 
                                            Name = ? ,
                                            Description =  ? ,
                                            Ordering =  ?,
                                            Visibility =  ?,
                                            Allow_Comment =  ?,
                                            Allow_Ads =  ?
                                      WHERE
                                            ID = ? ");

            $stm->execute(array($name, $desc, $order, $visibility, $comment, $ads, $catid));
            $msg = '<div class="alert alert-success" role="alert">
                    <h3 class="text-center"> user info updated</h3> 
                    </div>';
            redirectHome($msg);
        } else {
            echo 'you can not access page direct';
        }
    } elseif ($do == 'Delete') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        /* $stm = $connect->prepare("SELECT *
                                 FROM categories
                                 WHERE ID =? LIMIT 1  ");
        $count = $stm->execute(array($catid));
        $count = $stm->rowCount();*/
        $count = checkItem('ID', 'categories', $catid);
        if ($count > 0) {
            //$stm = $connect->prepare("DELETE FROM users WHERE UserID =? ");
            // $stm->execute(array($userid));
            $stm = $connect->prepare("DELETE FROM categories WHERE ID =:zid");
            $stm->bindparam('zid', $catid);
            $stm->execute();
            echo '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> user deleted!!</h3> 
                      </div>';
        } else {
            echo 'id not exists';
        }
    } elseif ($do == 'Activate') {
    }
    include $temp . '/footer.php';
} else {
    header('location: index.php');
    exit;
}