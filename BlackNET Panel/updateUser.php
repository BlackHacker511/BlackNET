<?php
require_once 'session.php';
require_once APP_PATH . 'classes/Mailer.php';
require_once APP_PATH . 'classes/Settings.php';
require_once APP_PATH . 'logic/updateUserLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - User Settings</title>
  <?php require_once 'components/css.php'; ?>
</head>

<body id="page-top">

  <?php require_once 'components/header.php'; ?>

  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">User Settings</a>
          </li>
        </ol>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-user-circle"></i>
            User Settings</div>
          <form method="POST" action="includes/updateUser.php">
            <div class="card-body">
              <div class="container container-special">
                <?php if (isset($_GET['msg'])) : ?>

                  <?php if ($_GET['msg'] == "yes") : ?>

                    <?php echo $utils->alert(
                      "User settings has been updated",
                      "success",
                      "check-circle"
                    ); ?>

                  <?php elseif ($_GET['msg'] == "csrf") : ?>

                    <?php echo $utils->alert(
                      "CSRF token is invalid.",
                      "danger",
                      "times-circle"
                    ); ?>

                  <?php elseif ($_GET['msg'] == "error") : ?>

                    <?php echo $utils->alert(
                      "An unexpected error has occurred",
                      "danger",
                      "times-circle"
                    ); ?>

                  <?php elseif ($_GET['msg'] == "attack") : ?>

                    <?php echo $utils->alert(
                      "You are trying to access another account",
                      "danger",
                      "times-circle"
                    ); ?>

                  <?php endif; ?>

                <?php endif; ?>
              </div>
              <div class="container container-special">
                <div class="align-content-center justify-content-center">

                  <?php echo $utils->input("id", $data->id); ?>

                  <?php echo $utils->input("csrf", $utils->sanitize($_SESSION['csrf'])); ?>

                  <div class="form-group">

                    <div class="form-label-group">
                      <input class="form-control" type="text" id="Username" name="Username" placeholder="Username" value="<?php echo $data->username; ?>">
                      <label for="Username">Username</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid Email" type="email" id="Email" name="Email" placeholder="Email Address" value="<?php echo $data->email; ?>" />
                      <label for="Email">Email Address</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="password" title="Must contain at least one number, one uppercase letter, lowercase letter, one special character, and at least 8 or more characters" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" id="Password" name="Password" placeholder="New Password">
                      <label for="Password">New Password</label>
                    </div>
                    <small>Keep it empty if you do not want change the password.</small>
                  </div>
                  <hr />
                  <div class="form-group">
                    <div class="custom-control custom-switch custom-control-right">
                      <input class="custom-control-input" id="darkmode" name="darkmode" type="checkbox" <?php echo (isset($_COOKIE['darkmode'])) ? 'checked' : null; ?>>
                      <label class="custom-control-label" for="darkmode">Dark Mode: </label>
                    </div>
                  </div>
                  <hr />
                  <div class="form-group">
                    <div class="form-group">
                      <label for="switch-state">Enable 2FA: </label>
                      <a href="authsettings.php" class="btn btn-primary text-white">Open 2FA Settings</a>
                    </div>
                  </div>
                  <hr />
                  <div class="form-group">
                    <div class="form-group">
                      <div class="custom-control custom-switch custom-control-right">
                        <input class="custom-control-input" id="sqenable" name="sqenable" type="checkbox" <?php echo ($user_question->sqenable == true) ? 'checked' : null; ?>>
                        <label class="custom-control-label" for="sqenable">Enable Security Question: </label>
                      </div>
                    </div>

                    <div class="from-group">
                      <select name="questions" id="questions" class="form-control custom-select">
                        <?php foreach ($questions as $question_key => $question_value) : ?>
                          <option value="<?php echo $question_key ?>" <?php echo ($user_question != null && $user_question->question == $question_key) ? "selected" : null; ?>><?php echo $question_value; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="answer" name="answer" placeholder="Answer the question" value="<?php echo (!$user_question == null) ? ($user_question->answer) : null; ?>" />
                      <label for="answer">Answer the question</label>
                    </div>
                  </div>
                  <button class="btn btn-primary btn-block">Update your information</button>
                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
</body>

</html>