<?php
require_once 'config/config.php';
require_once APP_PATH . 'classes/Database.php';
require_once APP_PATH . 'classes/User.php';
require_once APP_PATH . 'classes/Mailer.php';
require_once APP_PATH . 'classes/Template.php';
require_once APP_PATH . 'classes/ResetPassword.php';
require_once APP_PATH . 'classes/Utils.php';
require_once APP_PATH . 'classes/Settings.php';
require_once APP_PATH . 'logic/resetQuestionLogic.php';
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
      <div class="card-header">Security Question</div>
      <div class="card-body">
        <?php if (isset($msg)) : ?>

          <?php echo $utils->alert($msg, "danger", "times-circle"); ?>

        <?php endif; ?>
        <div class="text-center mb-4">

          <h4>Security Question</h4>
          <p>Please enter the answer to your security question</p>
        </div>
        <form method="POST">
          <div class="form-group">
            <label><?php echo $questions[$userQuestion->question]; ?></label>
            <div class="form-label-group">
              <input type="text" name="answer" id="answer" class="form-control" placeholder="Security Question's Answer" required="required" autofocus="autofocus">
              <label for="answer">Security Question's Answer</label>
            </div>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Next Step</button>
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