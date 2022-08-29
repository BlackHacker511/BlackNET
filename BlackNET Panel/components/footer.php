<footer class="py-4 bg-<?php echo $theme_name; ?> my-sm-10 sticky">
  <div class="container-fluid my-auto">
    <div class="medium">
      <div class="text-muted text-center">
        <span>Powered By
          <?php echo APP_NAME; ?>
          <?php echo APP_VERSION; ?>
          | Developed By <a href="#"><?php echo APP_DEVELOPER; ?></a></span>
      </div>
    </div>
  </div>
</footer>

<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        Select "Logout" below if you are ready to end your current session.
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">
          Cancel
        </button>
        <a class="btn btn-primary" href="<?php echo SITE_URL; ?>/logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>