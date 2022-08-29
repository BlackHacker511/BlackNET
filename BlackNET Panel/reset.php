<?php
session_start();
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/User.php';
require_once APP_PATH . 'classes/Mailer.php';
require_once APP_PATH . 'classes/Template.php';
require_once APP_PATH . 'classes/ResetPassword.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'logic/resetPasswordLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - Reset Password</title>
  <?php require_once 'components/css.php'; ?>

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <?php if (isset($msg)) : ?>

          <?php echo $utils->alert($msg, "primary", "info-circle"); ?>

        <?php endif; ?>

        <?php if (isset($err)) : ?>

          <?php echo $utils->alert($err, "danger", "times-circle"); ?>

        <?php endif; ?>
        <div class="text-center mb-4">
          <h4>Reset Password</h4>
          <p>Please enter a strong password that contains 8 characters and at least one special character</p>
        </div>
        <form method="POST" action="">

          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="password" id="password" class="form-control" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least one number, one uppercase letter, lowercase letter, one character, and at least 8 or more characters" placeholder="New Password" required="required" autofocus="autofocus">
              <label for="password">New Password</label>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="confirmPassword" id="confirmPassword" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least one number, one uppercase letter, lowercase letter, one special character, and at least 8 or more characters" class="form-control" placeholder="Confirm Password" required="required" autofocus="autofocus">
              <label for="confirmPassword">Confirm Password</label>
            </div>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Reset Password</button>

        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="login.php">Login Page</a>
        </div>
      </div>
    </div>
  </div>
  <?php require_once 'components/js.php'; ?>
</body>

</html>