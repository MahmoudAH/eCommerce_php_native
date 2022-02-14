<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"><?php echo lang('home_admin') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories2.php?do=Manage"><?php echo lang('categories') ?></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="items.php?do=Manage"><?php echo lang('items') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="members.php?do=Manage">
                        Members</a>
                </li>
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Mahmoud
                    </a>
                    <ul class="dropdown-menu " aria-labelledby="navbarScrollingDropdown">
                        <li><a class="dropdown-item"
                                href="members.php?do=Edit&userid=<?php echo $_SESSION['id']; ?>">Edit Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings </a></li>
                        <li><a class="dropdown-item" href="dashboard.php">Dashboard </a></li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>
</nav>