<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom dark">
    <h5 class="my-0 mr-md-auto font-weight-normal dark"><i class="fas fa-link"></i> <strong>Links</strong></h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 color-grey" href="<?= $data['site_url'];?>/dashboard"><i class="fas fa-tachometer-alt"></i> <?= dashboard;?></a>
        <a class="p-2 color-grey" href="<?= $data['site_url'];?>/shortcuts"><i class="fas fa-link"></i> <?= shortcuts;?></a>
        <?php if($_SESSION['admin'] == 1){?>
        <a class="p-2 color-grey" href="<?= $data['site_url'];?>/users"><i class="fas fa-users"></i> <?= users;?></a>
        <?php } ?>
        <a class="p-2 color-grey" href="<?= $data['site_url'];?>/profil"><i class="fas fa-cogs"></i> Profil</a>
        <a class="p-2 color-grey" href="<?= $data['site_url'];?>/logout.php"><i class="fas fa-sign-out-alt"></i> <?= logout;?></a>
    </nav>
</div>