<?php
require_once 'session.php';
require_once APP_PATH . 'classes/Clients.php';
require_once APP_PATH . 'logic/viewLogsLogic.php';
?>

<!DOCTYPE html>
<html>

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - View Logs</title>
  <?php require_once 'components/css.php'; ?>
  <?php $utils->style("vendor/datatables/dataTables.bootstrap4.css"); ?>
  <?php $utils->style("vendor/responsive/css/responsive.dataTables.css"); ?>
  <?php $utils->style("vendor/responsive/css/responsive.bootstrap4.css"); ?>
</head>

<body id="page-top">
  <?php require_once 'components/header.php'; ?>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">System Logs</a>
          </li>
        </ol>
        <div class="card mb-3">
          <form method="POST" action="includes/deleteLogs.php">
            <input type="text" name="csrf" hidden="" value="<?php echo ($utils->sanitize($_SESSION['csrf'])); ?>">
            <div class="card-header">
              <i class="fas fa-clipboard-check"></i>
              System Logs</div>
            <div class="card-body">
              <div class="container text-center">
                <?php if (isset($_GET['msg'])) : ?>
                  <?php if ($_GET['msg'] == "yes") : ?>
                    <div class="container container-special">
                      <?php echo $utils->alert("Logs has been removed.", "success", "check-circle"); ?>
                    </div>
                  <?php elseif ($_GET['msg'] == "csrf") : ?>
                    <div class="container container-special">
                      <?php echo $utils->alert("CSRF token is invalid.", "danger", "times-circle"); ?>
                    </div>
                  <?php endif; ?>
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
                        <th>Time</th>
                        <th>Victim ID</th>
                        <th>Message</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($logs as $log) : ?>
                        <tr>
                          <td>
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input" id="log_<?php echo $log->id; ?>" name="log[]" value="<?php echo $log->id; ?>">
                              <label class="custom-control-label" for="log_<?php echo $log->id; ?>"></label>
                            </div>
                          </td>
                          <td><?php echo $log->time; ?></td>

                          <td><?php echo $log->vicid; ?></td>

                          <td><?php echo $log->message; ?></td>

                          <?php if ($log->type == "Succ") : ?>
                            <td>
                              <div><span class="fas fa-check text-success"></span></div>
                            </td>
                          <?php else : ?>
                            <td>
                              <div><span class="fas fa-times text-danger"></span></div>
                            </td>
                          <?php endif; ?>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Delete Logs</button>
              <button onClick="Export()" type="button" class="btn btn-primary">Export Logs</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
  <?php $utils->script("vendor/datatables/jquery.dataTables.js"); ?>
  <?php $utils->script("vendor/datatables/dataTables.bootstrap4.js"); ?>
  <?php $utils->script("js/demo/datatables-demo.js"); ?>
  <script type="text/javascript">
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

    function Export() {
      var conf = confirm("Export Logs to Excel?");
      if (conf == true) {
        window.location.href = 'includes/exportLogs.php';
      }
    }
  </script>

</body>

</html>