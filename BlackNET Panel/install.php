<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Update.php';
require_once APP_PATH . 'logic/installLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - Installation</title>
  <?php require_once 'components/css.php'; ?>
</head>

<body class="bg-dark">
  <div class="container pt-3">
    <div class="mx-auto mt-5 card card-login">
      <div class="card-header">Install</div>
      <div class="card-body">
        <form method="POST">
          <?php if (isset($msg)) : ?>
            <?php echo $utils->alert("Panel has been installed.", "success", "check-circle"); ?>
          <?php endif; ?>

          <?php if (isset($error)) : ?>
            <?php echo $utils->alert($error, "danger", "times-circle"); ?>
          <?php endif; ?>

          <?php if (!isset($_POST['install'])) : ?>

            <?php echo $php_alert; ?>

            <div class="text-center alert alert-primary border-primary">
              <b>
                <div>
                  PHP Version: <?php echo PHP_VERSION; ?>
                  <br />
                  <?php foreach ($is_installed as $library) : ?>
                    <?php echo $library['name'] . ": " . $library['status'] . "<br />"; ?>
                  <?php endforeach; ?>

                  <?php foreach ($is_writable as $folder) : ?>
                    <?php echo $folder['name'] . ": " . $folder['status'] . "<br />"; ?>
                  <?php endforeach; ?>
                </div>
              </b>
            </div>
          <?php endif; ?>

          <h3 class="text-center">Create an admin user</h3>

          <div class="form-group">
            <div class="form-label-group">
              <input class="form-control" type="text" maxlength="15" id="username" name="username" placeholder="Username" required>
              <label for="username">Username</label>
              <small id="user_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
              <input class="form-control" type="password" minlength="8" id="password" name="password" placeholder="Password" required>
              <label for="password">Password</label>
              <small id="password_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
              <input class="form-control" type="email" id="email" name="email" placeholder="Email Address" required>
              <label for="email">Email Address</label>
              <small id="email_error" class="text-danger"></small>
            </div>
          </div>

          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="EULA" name="EULA" required>
              <label class="custom-control-label" for="EULA">I agree to <a href="https://github.com/BlackHacker511/BlackNET/blob/main/EULA.md">EULA</a></label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block" <?php echo $disabled; ?>>
            Start Installation
          </button>
        </form>
      </div>
    </div>
  </div>
  <?php require_once 'components/js.php'; ?>
  <script type="text/javascript">
    $("#username").change(function() {
      var username = $("#username").val();
      if (username.length > 15) {
        $("#user_error").text("Username must be 15 characters or less");
      } else {
        $("#user_error").text(null);
      }
    });

    $("#password").change(function() {
      var password = $("#password").val();
      if (password.length < 8) {
        $("#password_error").text("Password must be 8 characters or more");
      } else {
        $("#password_error").text(null);
      }
    });

    $("#email").change(function() {
      var email = $("#email").val();
      var mailformat = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
      if (email != '') {
        if (mailformat.test(email)) {
          $("#email_error").text(null);
        } else {
          $("#email_error").text("Please enter a valid email address");
        }
      }
    });
  </script>
</body>

</html>