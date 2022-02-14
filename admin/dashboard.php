<?php
session_start();


if (isset($_SESSION['Username'])) {
    //echo 'welcome' . $_SESSION['Username'];
    $pageTitle = 'dashboard';
    include 'init.php'; ?>

<div class="container" style="margin-top:50px">
    <div class="row">
        <div class="col-lg-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">total members</h5>
                    <p class="card-text">
                        <?php echo countItem('UserID', 'users') ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-white bg-secondary  mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title"><a href="members.php?do=Manage&page=pending">pending members</a> </h5>
                    <p class="card-text">
                        <?php echo checkItem('RegisterStatus', 'users', '0') ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card text-white bg-success ">
                <div class="card-body text-center">
                    <h5 class="card-title">total items</h5>
                    <p class="card-text"><a href="items.php?do=Manage">
                            <?= countItem('ItemID', 'items') ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card">

    </div>


    <div class="row">
        <div class="col-sm-6">
            <?php
                $latest = getLatest('*', 'users', 'UserID', 5);
                ?>
            <ul class="list-group">
                <li class="list-group-item active"><i class="fas fa-users" style="margin:5px;"></i>latest users
                    <span style="float:right" class="toggle-info">
                        <i class="fas fa-plus"></i>
                    </span>
                </li>

                <?php foreach ($latest as $user) { ?>
                <li class=" list-group-item "><?= $user['Username'] ?><span style=" float: right;">
                        <a class="btn btn-success"
                            href="members.php?do=Edit&userid=<?php echo $user['UserID']; ?>">Edit</a>

                    </span></li>
                <?php } ?>
            </ul>

        </div>

        <div class="col-sm-6 latest">
            <?php
                $latestitems = getLatest('*', 'items', 'ItemID', 5);
                ?>
            <ul class="list-group ">
                <li class="list-group-item active"><i class="fas fa-sitemap" style="margin:5px;"></i>latest Items
                    <span style="float:right" class="toggle-info">
                        <i class="fas fa-plus"></i>
                    </span>
                </li>

                <?php foreach ($latestitems as $item) { ?>
                <li class="list-group-item item"><?= $item['Name'] ?>
                    <span style="float: right;margin-left:5px">
                        <a class="btn btn-success"
                            href="items.php?do=Edit&itemid=<?php echo $item['ItemID']; ?>">Edit</a>
                    </span>
                    <?php if ($item['Approve'] == 0) { ?>
                    <a class="btn btn-secondary" href="items.php?do=Approve&itemid=<?= $item["ItemID"] ?>"
                        style="float: right;">
                        <i class="fas fa-check"></i> Approve</a>
                    <?php  }
                            ?>

                </li>
                <?php } ?>
            </ul>
        </div>

    </div>


</div>
<?php include $temp . '/footer.php';
} else {
    header('location: dashboard.php');
    exit;
}