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
        $stm = $connect->prepare("  SELECT 
                                        items.* ,
                                        categories.Name as cat_name,
                                        users.Username as username
                                    FROM 
                                        items 
                                    INNER JOIN 
                                        categories 
                                    ON 
                                        categories.ID = items.Cat_ID 
                                    INNER JOIN 
                                        users
                                    ON 
                                    users.UserID = items.Member_ID ;");
        $stm->execute();
        $items = $stm->fetchAll();


?>
<h1 class="text-center">All items</h1>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">desc</th>
                <th scope="col">price</th>
                <th scope="col">date</th>
                <th scope="col">country</th>
                <th scope="col">status</th>
                <th scope="col">rating</th>
                <th scope="col">cat name </th>
                <th scope="col">user name</th>
                <th scope="col">actions</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
            <tr>
                <th scope="row"><?php echo $item['ItemID'] ?></th>
                <td><?php echo $item['Name'] ?></td>
                <td><?php echo $item['Description'] ?></td>
                <td><?php echo $item['Price'] ?></td>
                <td><?php echo $item['Add_Date'] ?></td>
                <td><?php echo $item['Country_Made'] ?></td>
                <td><?php echo $item['Status'] ?></td>
                <td><?= $item['Rating'] ?></td>
                <td><?= $item['cat_name'] ?></td>
                <td><?= $item['username'] ?></td>
                <td>
                    <a class="btn btn-success" href="items.php?do=Edit&itemid=<?php echo $item['ItemID']; ?>"><i
                            class="far fa-edit"></i>Edit</a>
                    <a class="btn btn-danger confirm" href=" items.php?do=Delete&itemid=<?= $item['ItemID']; ?>"><i
                            class="fas fa-trash-alt"></i>Delete</a>
                    <?php
                                if ($item['Approve'] == 0) { ?>
                    <a class="btn btn-secondary" href="items.php?do=Approve&itemid=<?= $item["ItemID"] ?>">
                        <i class="fas fa-check"></i> Approve</a>
                    <?php  }
                                ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="items.php?do=Add" type="button" class="btn btn-primary text-center">Add New Item</a>

</div>
<?php  } elseif ($do == 'Add') { ?>
<h1 class="text-center">Add New Item</h1>
<div class="container">
    <form action="?do=Insert" method="POST">
        <div class="form-group ">
            <label for="exampleInputEmail1">Name </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name of cat" name="name" required>

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Desc </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name of cat" name="description">

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Price </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter price" name="price">

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Country </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="country of made" name="country">

        </div>
        <div class="form-group " style="margin-bottom: 20px;">
            <label for="exampleInputEmail1">Status </label>
            <select class="form-select" aria-label="Default select example" name="status">
                <option selected></option>
                <option value="1">new</option>
                <option value="2">used</option>
                <option value="3">lked</option>
            </select>

        </div>

        <div class="form-group ">
            <label for="exampleInputEmail1">Members </label>
            <select class="form-select" aria-label="Default select example" name="member">
                <option value="0"></option>
                <?php
                        $stm = $connect->prepare("SELECT * FROM users");
                        $stm->execute();
                        $users = $stm->fetchAll();
                        foreach ($users as $user) {
                            echo '<option value="' . $user["UserID"] . '" >' . $user["Username"] . '</option>';
                        }
                        ?>
            </select>

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Categories </label>
            <select class="form-select" aria-label="Default select example" name="category">
                <option value="0"></option>
                <?php
                        $stm = $connect->prepare("SELECT * FROM categories");
                        $stm->execute();
                        $categories = $stm->fetchAll();
                        foreach ($categories as $cat) {
                            echo '<option value="' . $cat["ID"] . '" >' . $cat["Name"] . '</option>';
                        }
                        ?>
            </select>

        </div>


        <button type="submit" style="margin-top: 20;" class="btn btn-primary " value="Add Item">Submit</button>
    </form>

</div>
<?php

    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];


            //validate
            $formError = [];
            if (empty($name)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                name can not be empty</div>';
            }

            if (empty($description)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                description can not be empty</div>';
            }
            if (empty($price)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                price can not be empty</div>';
            }
            if (empty($country)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                country can not be empty</div>';
            }
            if (empty($status)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
                status can not be empty</div>';
            }

            //loop into erroraray
            foreach ($formError as $error) {
                echo $error . '<br/>';
            }
            //check if errorform is empty
            if (empty($formError)) {

                $stm = $connect->prepare("INSERT INTO 
                                        items(Name,Description,Price,Country_Made,Status,Add_Date,Member_ID,Cat_ID)
                                        VALUES (:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zmember,:zcat )");
                $stm->execute(array(
                    'zname' => $name,
                    'zdesc' => $description,
                    'zprice' => $price,
                    'zcountry' => $country,
                    'zstatus' => $status,
                    'zmember' => $member,
                    'zcat' => $category,

                ));


                $msg = '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> Item Added</h3> 
                      </div>';
                redirectHome($msg);
            }
        }
    } elseif ($do == 'Edit') {

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $stm = $connect->prepare("SELECT *
                                 FROM items
                                 WHERE ItemID =? LIMIT 1  ");
        $count = $stm->execute(array($itemid));
        $row = $stm->fetch();
        //print_r($row);
        $count = $stm->rowCount();
        if ($count > 0) { ?>

<h1 class="text-center">Edit Item</h1>
<div class="container">
    <form action="?do=Update" method="POST">
        <input type="hidden" name="itemid" value="<?= $itemid ?>">
        <div class="form-group ">
            <label for="exampleInputEmail1">Name </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name of cat" name="name" value="<?= $row['Name'] ?>" required>

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Desc </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter name of cat" value="<?= $row['Description'] ?>" name="description">

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Price </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="Enter price" value="<?= $row['Price'] ?>" name="price">

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Country </label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                placeholder="country of made" value="<?= $row['Country_Made'] ?>" name="country">

        </div>
        <div class="form-group " style="margin-bottom: 20px;">
            <label for="exampleInputEmail1">Status </label>
            <select class="form-select" aria-label="Default select example" name="status">
                <option selected>...</option>
                <option value="1" <?php if ($row['Status'] == 1) {
                                                    echo 'selected';
                                                } ?>>new</option>
                <option value="2" <?php if ($row['Status'] == 1) {
                                                    echo 'selected';
                                                } ?>>used</option>
                <option value="3" <?php if ($row['Status'] == 2) {
                                                    echo 'selected';
                                                } ?>>lked</option>
            </select>

        </div>

        <div class="form-group ">
            <label for="exampleInputEmail1">Members </label>
            <select class="form-select" aria-label="Default select example" name="member">
                <option value="0"></option>
                <?php
                            $stm = $connect->prepare("SELECT * FROM users");
                            $stm->execute();
                            $users = $stm->fetchAll();
                            foreach ($users as $user) {
                                echo '<option value="' . $user["UserID"] . '"';
                                if ($row['Member_ID'] == $user["UserID"]) {
                                    echo 'selected';
                                }
                                echo '>' .
                                    $user["Username"] . '</option>';
                            }
                            ?>
            </select>

        </div>
        <div class="form-group ">
            <label for="exampleInputEmail1">Categories </label>
            <select class="form-select" aria-label="Default select example" name="category">
                <option value="0"></option>
                <?php
                            $stm = $connect->prepare("SELECT * FROM categories");
                            $stm->execute();
                            $categories = $stm->fetchAll();
                            foreach ($categories as $cat) {
                                echo '<option value="' . $cat["ID"] . '"';
                                if ($row['Cat_ID'] == $cat["ID"]) {
                                    echo 'selected';
                                }
                                echo '>' . $cat["Name"] . '</option>';
                            }
                            ?>
            </select>

        </div>


        <button type="submit" style="margin-top: 20;" class="btn btn-primary " value="Add Item">Submit</button>
    </form>

</div>
<?php       } else {
            echo 'no id exist';
        }
    } elseif ($do == 'Update') {
        echo '<h1 class="text-center">Update Members</h1>';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $itemid = $_POST['itemid'];
            $name =   $_POST['name'];
            $desc =   $_POST['description'];
            $price =  $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $category = $_POST['category'];

            //validate
            $formError = [];
            if (empty($name)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
    name can not be empty</div>';
            }
            /*
            if (empty($description)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
    description can not be empty</div>';
            }*/
            if (empty($price)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
    price can not be empty</div>';
            }
            if (empty($country)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
    country can not be empty</div>';
            }
            if (empty($status)) {
                $formError[] = '<div class="alert alert-danger" role="alert">
    status can not be empty</div>';
            }

            //loop into erroraray
            foreach ($formError as $error) {
                echo $error . '<br/>';
            }
            //check if errorform is empty
            if (empty($formError)) {

                $stm = $connect->prepare("UPDATE 
                                                items
                                         SET 
                                                Name = ? ,
                                                Description = ?,
                                                Price = ? ,
                                                Country_Made = ? ,
                                                Status = ? ,
                                                Member_ID = ? ,
                                                Cat_ID = ?
                                        WHERE   ItemID = ? ");
                $stm->execute(array($name, $desc, $price, $country, $status, $member, $category, $itemid));
                $msg = '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> item info updated</h3> 
                      </div>';
                redirectHome($msg);
            }
        } else {
            echo 'you can not access page direct';
        }
    } elseif ($do == 'Delete') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $stm = $connect->prepare("SELECT *
                                 FROM items
                                 WHERE ItemID =? LIMIT 1  ");
        $count = $stm->execute(array($itemid));
        $count = $stm->rowCount();
        if ($count > 0) {
            //$stm = $connect->prepare("DELETE FROM users WHERE UserID =? ");
            // $stm->execute(array($userid));
            $stm = $connect->prepare("DELETE FROM items WHERE ItemID =:zid");
            $stm->bindparam('zid', $itemid);
            $stm->execute();
            echo '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> item deleted!!</h3> 
                      </div>';
        } else {
            echo 'id not exists';
        }
    } elseif ($do == 'Approve') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stm = $connect->prepare("SELECT *
                                 FROM items
                                 WHERE ItemID =? LIMIT 1");
        $count = $stm->execute(array($itemid));
        $count = $stm->rowCount();
        if ($count > 0) {
            $stm = $connect->prepare("UPDATE items SET Approve =1 WHERE ItemID =? ");
            $stm->execute(array($itemid));
            $stm->execute();
            echo '<div class="alert alert-success" role="alert">
                      <h3 class="text-center"> item approve!!</h3> 
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