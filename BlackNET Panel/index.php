<?php
require_once 'session.php';
require_once APP_PATH . 'classes/Clients.php';
require_once APP_PATH . 'logic/homeLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - Main Interface</title>
  <?php require_once 'components/css.php'; ?>
  <?php $utils->style("vendor/datatables/dataTables.bootstrap4.css"); ?>
  <?php $utils->style("vendor/jvector/css/jvector.css"); ?>
</head>

<body id="page-top">
  <?php require_once 'components/header.php'; ?>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <?php echo $utils->input("theme_mode", $theme_name); ?>

        <?php if ($user->isTwoFAEnabled($_SESSION['username']) == false) : ?>

          <?php echo $utils->dismissibleAlert(
            "<b> Warning!</b> Your account is not protected by two-factor authentication. Enable two-factor authentication now from <a href=\"authsettings.php\" class=\"alert-link\">here</a>.",
            "warning",
            "exclamation-triangle"
          ); ?>

        <?php endif; ?>

        <?php if (isset($forbidden_message)) : ?>

          <?php echo $forbidden_message; ?>

        <?php endif; ?>

        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Bots Menu</a>
          </li>
        </ol>

        <?php require_once 'components/stats.php'; ?>

        <form method="POST" action="sendcommand.php" id="Form1" name="Form1">
          <?php echo $utils->input("csrf", $utils->sanitize($_SESSION['csrf'])); ?>

          <?php require_once 'components/clientsList.php'; ?>

          <div class="row">
            <?php require_once 'components/commands.php'; ?>
            <div class="col-12 col-sm-12 col-md-8 col-lg-6">
              <div class="mb-3 card">
                <div class="card-header">
                  <i class="fas fa-map-marker-alt"></i>
                  Map Visualization
                </div>
                <div class="card-body">
                  <div class="map-container">
                    <div id="clientmap" name="clientmap" class="jvmap-smart"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
  <?php $utils->script("vendor/chartjs/Chart.min.js"); ?>
  <?php $utils->script("vendor/chartjs/chartjs-plugin-colorschemes.min.js"); ?>
  <?php $utils->script("vendor/datatables/jquery.dataTables.js"); ?>
  <?php $utils->script("vendor/datatables/dataTables.bootstrap4.js"); ?>
  <?php $utils->script("js/demo/datatables-demo.js"); ?>
  <?php $utils->script("js/demo/chart-bar-demo.js"); ?>
  <?php $utils->script("js/demo/chart-pie-demo.js"); ?>
  <?php $utils->script("vendor/jvector/js/core.js"); ?>
  <?php $utils->script("vendor/jvector/js/world.js"); ?>
  <?php $utils->script("js/main.js"); ?>
</body>

</html>