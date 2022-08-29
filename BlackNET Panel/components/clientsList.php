<div class="card mb-3">
  <div class="card-header">
    <i class="fas fa-bug"></i>
    Bots List
  </div>
  <div class="card-body">
    <div class="pl-2 pb-2 pt-2 mb-3 border rounded">
      Toggle Column:
      <?php echo implode(" | ", $toggle); ?>
    </div>
    <div class="table-responsive border pl-2 pb-2 pt-2 pr-2 pb-2 rounded">
      <table class="table nowrap table-bordered" width="100%" id="dataTable">
        <thead>
          <tr>
            <th>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="select-all" name="select-all" <?php echo $disabled; ?>>
                <label class="custom-control-label" for="select-all"></label>
              </div>
            </th>
            <?php foreach ($columns as $column) : ?>
              <th><?php echo $column; ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($allClients as $clientData) : ?>
            <tr class="<?php echo ($clientData->is_usb == "yes") ? "text-primary" : "" ?>">
              <td>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="<?php echo $clientData->vicid; ?>" name="client[]" value="<?php echo $clientData->vicid; ?>" />
                  <label class="custom-control-label" for="<?php echo $clientData->vicid; ?>"></label>
                </div>
              </td>
              <td>
                <a href="viewClient.php?vicid=<?php echo $clientData->vicid ?>"><?php echo $clientData->vicid; ?></a>
              </td>
              <td><?php echo $clientData->ipaddress; ?></td>
              <td><?php echo $clientData->computername; ?></td>
              <td><?php echo $clientData->is_admin; ?></td>
              <td class="text-center">
                <img src="<?php echo $client->getClientFlag($clientData->country); ?>" />
                <p hidden>
                  <?php echo $countries[strtoupper($clientData->country)]; ?>
                </p>
              </td>
              <td><?php echo $clientData->os; ?></td>
              <td><?php echo $clientData->insdate; ?></td>
              <td>
                <span class="badge badge-primary"><?php echo $clientData->version; ?></span>
              </td>
              <td class="align-content-center text-center">
                <img src="images/<?php echo strtolower($clientData->status) ?>.png" />
                <p hidden><?php $clientData->status; ?></p>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>