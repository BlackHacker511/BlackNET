<?php if (defined("MODULES_PATH")) : ?>
    <?php if ($data->role == 1) : ?>
        <?php if (is_dir(MODULES_PATH)) : ?>
            <?php if (!(empty($modules))) : ?>
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="fa fa-puzzle-piece"></span> Modules
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
                        <?php foreach ($modules as $module) : ?>
                            <a class="dropdown-item" href="<?php echo SITE_URL; ?>/modules/<?php echo $module; ?>/index.php">
                                <span class="fas fa-cog fa-fw"></span>
                                <?php echo ucwords($module); ?>
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>