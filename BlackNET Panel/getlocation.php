<?php
require_once 'session.php';
require_once APP_PATH . 'classes/Clients.php';
require_once APP_PATH . 'logic/getLocationLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - View Client Location</title>
  <?php require_once 'components/css.php'; ?>
</head>

<body id="page-top">
  <?php require_once 'components/header.php'; ?>

  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">View Client Location</a>
          </li>
        </ol>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-bolt"></i>
            Client Location
          </div>

          <div class="card-body">
            <div class="container container-special">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Key</th>
                    <th>Value</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($client_data as $key => $value) : ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $key; ?></td>
                      <td><?php echo $value; ?></td>
                    </tr>
                    <?php $i++ ?>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <div>
                <a href="https://whatismyipaddress.com/ip/<?php echo $ipaddress; ?>" class="btn btn-primary btn-block">View More Information</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
</body>

</html>