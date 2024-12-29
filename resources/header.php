

<link rel="stylesheet" href="/resources/headerFooter.css">
<header id="head">
    <div class="d-flex flex-column flex-md-row align-items-left p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm" id="header">
        <a href="/index.php" class="navbar-brand d-flex align-items-left">
            <!-- <svg>

            </svg> -->
            <h1 class="my-0 mr-md-auto font-weight-normal" id="title"> Fresh 'n Clean</h1>
        </a>
        <nav class="my-2 my-md-0 mr-md-3 ms-auto" id="navBar">
            <a href="/ourStory/index.php" class="navItem">Our Story</a>
            <a href="/Schedule/schedule.php" class="navItem">Schedule</a>
            <?php if (isset($_SESSION['user_id']) || isset($_SESSION['provider_id'])): ?>
                <a class="btn btn-dark" id="loginButton" href="/login/profile.php">Profile</a>
            <?php else: ?>
                <a class="btn btn-dark" id="loginButton" href="/login/login.php">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>