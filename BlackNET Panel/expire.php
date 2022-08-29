<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'logic/expireLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - Key Expired</title>
  <?php require_once 'components/css.php'; ?>
</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <div class="text-center mb-4">
          <h4>Key Expired</h4>
        </div>
        <p class="lead text-center">
          We are so sorry for the inconvenience but it appers your code does not exist or expired
          please reset your password again to generate a new token.
          </br></br>
          Thank You
        </p>
        <div class="text-center">
          <a class="d-block small mt-3" href="forgot-password.php">Forgot Password?</a>
          <a class="d-block small" href="login.php">Login Page</a>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'components/js.php'; ?>

</body>

</html>