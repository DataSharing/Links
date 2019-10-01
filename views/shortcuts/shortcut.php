<div class="card">
    <div class="card-body">
        <form action="" method="post" class="row">
            <div class="col-12 mb-2" id="duplicate"></div>
            <div class='col-12 col-lg-6'>
                <input type="hidden" name="old_shortcut" value="<?= $data['shortcut']['shortcut']; ?>">
                <input type="text" class='form-control' onkeyup='check_duplicate();' autocomplete="off" name="shortcut" id="shortcut" placeholder="<?= shortcut; ?>" value="<?= $data['shortcut']['shortcut']; ?>">
                <small class="form-text text-muted"><?= shortcut; ?></small>
            </div>
            <div class='col-12 col-lg-6'>
                <input type="url" class='form-control' name="redirection" id="redirection" placeholder="Redirection" value="<?= $data['shortcut']['redirect_to']; ?>">
                <small class="form-text text-muted">Redirection</small>
            </div>
            <div class="col-12">
                <hr>
                <div class="col-12 mb-4">
                    <small class='form-text text-muted"'><?= create; ?> <?= $data['shortcut']['date_creation']; ?> <?= modified; ?> <?= $data['shortcut']['date_modification']; ?></small>
                </div>
                <a href='shortcuts' class='btn btn-sm btn-secondary'>
                    <i class='fas fa-arrow-left'></i> <?= close; ?>
                </a>
                <button type="button" data-toggle="modal" data-target="#history" class="btn btn-sm btn-dark">
                    <i class='fas fa-history'></i> <?= history; ?>
                    <span class="badge badge-light"><?= $data['nb'];?></span>
                </button>
                <button type="submit" name="submit" value="enregistrer" class="btn btn-sm btn-success">
                    <i class='fas fa-hdd'></i> <?= save; ?>
                </button>
                <button type="button" data-toggle="modal" data-target="#supprimer" class="btn btn-sm btn-danger float-right">
                    <i class='fas fa-trash'></i> <?= delete; ?>
                </button>
            </div>
            <div class="modal" id="supprimer" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn  btn-sm btn-secondary" data-dismiss="modal"><?= close; ?></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h5><?= confirmation_delete_shortcut; ?></h5>
                                </div>
                                <div class="col-12 text-center  mt-4">
                                    <button type="submit" name="submit" value="supprimer" class="btn btn-sm btn-danger">
                                        <i class='fas fa-trash'></i> <?= delete_definitively; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal bd-example-modal-xl" id="history" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <table class="table table-striped table-sm">
                    <tr>
                        <th><?= country; ?></th>
                        <th>Region</th>
                        <th><?= zip;?></th>
                        <th>ip</th>
                        <th>Date</th>
                    </tr>
                    <?php
                    foreach ($data['historique'] as $h) {
                        echo "<tr>";
                        echo "<td>" . $h['country'] . "</td>";
                        echo "<td>" . $h['regionName'] . "</td>";
                        echo "<td>" . $h['zip'] . "</td>";
                        echo "<td>" . $h['ip'] . "</td>";
                        echo "<td>" . $h['date_redirect'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
                <button type="button" class="btn  btn-sm btn-secondary mt-4" data-dismiss="modal"><?= close; ?></button>
            </div>
        </div>
    </div>
</div>