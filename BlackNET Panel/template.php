<?php require_once 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - Example Template</title>
  <?php require_once 'components/css.php'; ?>
</head>

<body id="page-top">
  <?php require_once 'components/header.php'; ?>

  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Example Template</a>
          </li>
        </ol>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-cube"></i>
            Layout Template
          </div>

          <div class="card-body">
            <div class="container container-special"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
</body>

</html>