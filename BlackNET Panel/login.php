<?php
session_start();
require_once 'config/config.php';
require_once APP_PATH . "classes/Database.php";
require_once APP_PATH . 'classes/Settings.php';
require_once APP_PATH . 'classes/User.php';
require_once APP_PATH . 'classes/Auth.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'logic/loginLogic.php';
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'components/meta.php'; ?>
<title><?php echo APP_NAME; ?> - Login</title>
<?php require_once 'components/css.php'; ?>
</head>

<body class="bg-dark">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form method="POST" id="login_form" name="login_form">
          <?php if (isset($error)) : ?>

            <?php echo $utils->alert($error, "danger", "times-circle"); ?>

          <?php endif; ?>
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="username" class="form-control" name="username" placeholder="Username" required="required" autofocus="autofocus">
              <label for="username">Username</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="required">
              <label for="password">Password</label>
            </div>
          </div>
          <div class="align-content-center text-center">
            <?php if ($settings->getSettingValue('recaptcha_status') == true) : ?>
              <div class="form-group">
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
              </div>
            <?php endif; ?>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="forgot-password.php">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>

  <p class="text-center mt-3 text-white">
    Powered By <a href="#"><?php echo APP_NAME; ?></a>
  </p>

  <?php require_once 'components/js.php'; ?>

  <?php if ($settings->getSettingValue('recaptcha_status') == true) : ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $settings->getSettingValue('recaptchapublic'); ?>"></script>
    <script>
      grecaptcha.ready(function() {
        grecaptcha.execute('<?php echo $settings->getSettingValue('recaptchapublic'); ?>', {
          action: 'login_form'
        }).then(function(token) {
          var recaptchaResponse = document.getElementById('recaptchaResponse');
          recaptchaResponse.value = token;
        });
      });
    </script>
  <?php endif; ?>
</body>

</html>