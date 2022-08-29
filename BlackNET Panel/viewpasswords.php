<?php
require_once 'session.php';
require_once APP_PATH . 'logic/viewPasswordsLogic.php';
?>

<!DOCTYPE html>
<html>

<head>
  <?php require_once 'components/meta.php'; ?>
  <title><?php echo APP_NAME; ?> - View Passwords</title>
  <?php require_once 'components/css.php'; ?>
  <?php $utils->style("vendor/datatables/dataTables.bootstrap4.css") ?>
  <?php $utils->style("vendor/responsive/css/responsive.dataTables.css") ?>
  <?php $utils->style("vendor/responsive/css/responsive.bootstrap4.css") ?>
</head>

<body id="page-top">
  <?php require_once 'components/header.php'; ?>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">View Passwords</a>
          </li>
        </ol>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-key"></i>
            View Passwords
          </div>
          <div class="card-body">
            <div class="container">
              <div class="table-responsive pt-4 pb-4">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Software</th>
                      <th>Website</th>
                      <th>Username</th>
                      <th>Password</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($lines as $line) : ?>
                      <?php $result = explode(",", $line); ?>
                      <?php if ($result[2] !== "" && $result[3] !== "") : ?>
                        <tr>
                          <td><?php echo $i; ?></td>

                          <td><?php echo $result[0]; ?></td>

                          <td><?php echo $utils->validateURL($result[1]); ?></td>

                          <td><?php echo $result[2]; ?></td>

                          <td><?php echo $result[3]; ?></td>
                        </tr>
                        <?php $i++; ?>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button onclick="Export()" type="button" class="btn btn-primary" download>
              Download Passwords
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php require_once 'components/footer.php'; ?>

  <?php require_once 'components/js.php'; ?>
  <?php $utils->script("vendor/datatables/jquery.dataTables.js"); ?>
  <?php $utils->script("vendor/datatables/dataTables.bootstrap4.js"); ?>
  <script>
    $(document).ready(function() {
      $("#dataTable").DataTable({
        ordering: true,
        "language": {
          "emptyTable": "Passwords File does not exist"
        },
        select: {
          style: "multi",
        },
      });
    });

    function Export() {
      var conf = confirm("Export Passwords to Excel?");
      if (conf == true) {
        window.location.href = 'includes/exportPasswords.php?vicid=<?php echo $_GET['vicid']; ?>';
      }
    }
  </script>
</body>

</html>