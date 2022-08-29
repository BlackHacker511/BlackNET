<?php
require_once 'session.php';
require_once APP_PATH . 'classes/Settings.php';
require_once APP_PATH . 'classes/Mailer.php';
require_once APP_PATH . 'logic/settingsLogic.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - Network Settings</title>
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
            <i class="fas fa-cogs"></i>
            Update Settings</div>
          <div class="card-body">
            <form id="Form1" name="Form1" method="POST" action="includes/updateSettings.php">

              <div class="container container-special">
                <?php if (isset($_GET['msg']) && $_GET['msg'] == "yes") : ?>
                  <?php echo $utils->alert("Settings has been updated", "success", "check-circle"); ?>
                <?php endif; ?>

                <?php if (isset($_GET['msg']) && $_GET['msg'] == "csrf") : ?>
                  <?php echo $utils->alert("CSRF token is invalid.", "danger", "times-circle"); ?>
                <?php endif; ?>
              </div>
              <div class="container container-special">
                <div class="align-content-center justify-content-center">
                  <?php echo $utils->input("csrf", $utils->sanitize($_SESSION['csrf'])); ?>

                  <div class="form-group">
                    <div class="custom-control custom-switch custom-control-right">
                      <input type="checkbox" class="custom-control-input" id="panel_status" name="panel_status" <?php echo ($settings->getSettingValue('panel_status') == true) ? 'checked' : null; ?>>
                      <label class="custom-control-label" for="panel_status">Panel Status: </label>
                    </div>
                  </div>

                  <hr>

                  <div class="form-group">
                    <div class="custom-control custom-switch custom-control-right">
                      <input type="checkbox" class="custom-control-input" id="delete_backup" name="delete_backup" <?php echo ($settings->getSettingValue('delete_backup') == true) ? 'checked' : null; ?>>
                      <label class="custom-control-label" for="delete_backup">Delete Backup: </label>
                    </div>
                  </div>

                  <hr>

                  <div class="form-group">
                    <div class="custom-control custom-switch custom-control-right">
                      <input class="custom-control-input" id="recaptcha_status" name="recaptcha_status" type="checkbox" <?php echo ($settings->getSettingValue('recaptcha_status') == true) ? 'checked' : null; ?>>
                      <label class="custom-control-label" for="recaptcha_status">Enable reCAPTCHA: </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" id="recaptchapublic" type="text" name="recaptchapublic" placeholder="reCAPTCHA Public Key" value="<?php echo $settings->getSettingValue('recaptchapublic'); ?>">
                      <label for="recaptchapublic">reCAPTCHA Public Key</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" id="recaptchaprivate" type="text" name="recaptchaprivate" placeholder="reCAPTCHA Public Key" value="<?php echo $settings->getSettingValue('recaptchaprivate'); ?>">
                      <label for="recaptchaprivate">reCAPTCHA Private Key</label>
                    </div>
                    <small>
                      Note: This feature requires a free site and secret key from <a href="https://www.google.com/recaptcha">Google reCaptcha</a>
                    </small>
                  </div>

                  <hr>

                  <div class="form-group">
                    <div class="custom-control custom-switch custom-control-right">
                      <input class="custom-control-input" id="smtp_status" name="smtp_status" type="checkbox" <?php echo ($settings->getSettingValue('smtp_status') == true) ? 'checked' : null; ?>>
                      <label class="custom-control-label" for="smtp_status">Enable SMTP: </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="smtp_host" name="smtp_host" placeholder="SMTP Host" value="<?php echo $settings->getSettingValue('smtp_host'); ?>">
                      <label for="smtp_host">SMTP Host</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="smtp_username" name="smtp_username" placeholder="SMTP User" value="<?php echo $settings->getSettingValue('smtp_username'); ?>">
                      <label for="smtp_username">SMTP User</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="password" id="smtp_password" name="smtp_password" placeholder="SMTP Password" value="<?php echo base64_decode($settings->getSettingValue('smtp_password')); ?>">
                      <label for="smtp_password">SMTP Password</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <select label="Select a Security type" name="smtp_security" id="smtp_security" class="form-control custom-select">
                      <option>Select a Security type</option>
                      <?php foreach ($smtp_types as $smtp_type) : ?>
                        <option value="<?php echo strtolower($smtp_type); ?>" <?php echo ($settings->getSettingValue('smtp_security') == strtolower($smtp_type)) ? "selected" : null; ?>><?php echo $smtp_type ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="smtp_port" name="smtp_port" placeholder="SMTP Port" value="<?php echo $settings->getSettingValue('smtp_port'); ?>">
                      <label for="smtp_port">SMTP Port</label>
                    </div>
                  </div>
                  <button for="Form1" name="Form1" class="btn btn-primary btn-block">Update Settings</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
</body>

</html>