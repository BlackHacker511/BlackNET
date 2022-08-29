<?php
require_once 'session.php';
require_once APP_PATH . '/logic/authSettingsLogic.php';
?>
<!DOCTYPE html>
<html>

<head>
	<?php require_once 'components/meta.php'; ?>
	<title><?php echo APP_NAME; ?> - 2 Factor Authentication Settings</title>
	<?php require_once 'components/css.php'; ?>
</head>

<body id="page-top">
	<?php require_once 'components/header.php'; ?>
	<div id="wrapper">
		<div id="content-wrapper">
			<div class="container-fluid">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="#">2 Factor Authentication Settings</a>
					</li>
				</ol>
				<div class="card mb-3">
					<div class="card-header">
						<i class="fas fa-user-circle"></i> 2 Factor Authentication Settings
					</div>
					<form method="POST" action="includes/updateAuth.php">
						<?php echo $utils->input("csrf", $utils->sanitize($_SESSION['csrf'])); ?>
						<div class="card-body">
							<div class="container container-special">
								<?php require_once 'components/s2famsg.php'; ?>
							</div>
							<div class="container container-special">
								<input type="text" name="username" value="<?php echo ($data->username); ?>" hidden="">
								<?php if ($data->s2fa == false) : ?>
									<div>
										<p>
											2FA is an enhanced level of security for your account. Each time you login, an extra step where you will need to enter a unique code will be required to gain access to your account. To enable 2FA please click the button below and download the <b>Google Authenticator</b> app from Apple Store or Google Play Store

											<h4>Important</h4>

											You need to scan the code below with the app. You need to backup the QR code below by saving it and save the key somewhere safe in case you lose your phone. You will not be able to login if you can't provide the code. if you disable 2FA and re-enable it, you will need to scan a new code.
										</p>
									</div>
									<div class="text-center">
										<img class="img-fluid justify-content-center rounded" src="<?php echo $qrcode; ?>" />
									</div>
									<div class="form-group pt-2">
										<div class="form-label-group">
											<input class="form-control" type="text" id="secret" name="secret" placeholder="Authentication Secret" value="<?php echo $_SESSION['secret']; ?>" readonly="" />
											<label for="secret">Authentication Secret</label>
										</div>
									</div>
									<div class="form-group">
										<div class="form-label-group">
											<input class="form-control" maxlength="6" size="6" type="text" id="code" name="code" placeholder="Authentication Code" />
											<label for="code">Authentication Code</label>
											<small for="code" class="small">Get the code from the app</small>
										</div>
									</div>
									<button type="submit" name="enable" class="btn btn-block btn-primary">Enable 2 Factor Authentication</button>
								<?php else : ?>
									<button type="submit" name="disable" class="btn btn-block btn-danger">Disable 2 Factor Authentication</button>
								<?php endif; ?>
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