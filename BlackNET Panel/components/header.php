  <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <a class="navbar-brand mr-1" href="<?php echo SITE_URL; ?>/index.php">
      <img src="<?php echo SITE_URL; ?>/favico.png" width="30" height="30" alt=""><?php echo APP_NAME; ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="fas fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php echo $utils->linkActive($page, "home"); ?>">
          <a class="nav-link" href="<?php echo SITE_URL; ?>/index.php">
            <span class="fa fa-home"></span> Home
          </a>
        </li>
        <li class="nav-item <?php echo $utils->linkActive($page, "view_logs"); ?>">
          <a class="nav-link" href="<?php echo SITE_URL; ?>/viewlogs.php">
            <span class="fa fa-clipboard-check"></span> View Logs
          </a>
        </li>
        <?php if ($data->role == 1) : ?>
          <li class="nav-item <?php echo $utils->linkActive($page, "network_settings"); ?>">
            <a class="nav-link" href="<?php echo SITE_URL; ?>/settings.php">
              <span class="fa fa-wrench"></span> Network Settings
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item <?php echo $utils->linkActive($page, "update_user"); ?>">
          <a class="nav-link" href="<?php echo SITE_URL; ?>/updateUser.php">
            <span class="fa fa-user"></span> User Settings
          </a>
        </li>
        <!-- Start Modules Handler -->
        <?php require_once 'moduleLoader.php'; ?>
        <!-- End Modules Handler -->
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#logoutModal"><span class="fa fa-sign-out-alt"></span> Logout</a>
        </li>
      </ul>
    </div>
  </nav>