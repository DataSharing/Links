<form action="" method="post">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-12 col-lg-2 mb-2">
                    <select name="admin" class="custom-select">
                        <option value="0" <?php if ($data['user']['admin'] == 0) echo 'selected'; ?>>Usager</option>
                        <option value="1" <?php if ($data['user']['admin'] == 1) echo 'selected'; ?>>Admin</option>
                    </select>
                    <small class="form-text text-muted"><?= permission_to_access;?></small>
                </div>
                <div class="col-12 col-lg-2 mb-2">
                    <select name="language" class="custom-select">
                        <option value="fr" <?php if ($data['user']['language'] == 'fr') echo 'selected'; ?>>fr</option>
                        <option value="en" <?php if ($data['user']['language'] == 'en') echo 'selected'; ?>>en</option>
                    </select>
                    <small class="form-text text-muted"><?= language;?></small>
                </div>
                <div class="col-12 col-lg-8 mb-2">
                    <input type="hidden" name="old_login" value="<?= $data['user']['login']; ?>">
                    <input type="text" name="login" class="form-control" placeholder="Login" value="<?= $data['user']['login']; ?>">
                    <small class="form-text text-muted">Login</small>
                </div>
                <div class="col-12">
                    <hr>
                    <div class="col-12 mb-4">
                        <small class='form-text text-muted"'><?= create;?> <?= $data['user']['date_creation']; ?> <?= modified;?> <?= $data['user']['date_modification']; ?></small>
                    </div>
                    <a href='users' class='btn btn-sm btn-secondary'>
                        <i class='fas fa-arrow-left'></i> <?= close;?>
                    </a>
                    <button type="submit" name="submit" value="enregistrer" class="btn btn-sm btn-success">
                        <i class="fas fa-hdd"></i> <?= save;?>
                    </button>
                    <button type="button" data-toggle="modal" data-target="#reset" class="btn btn-sm btn-warning">
                        <i class='fas fa-key'></i> <?= reset_password;?>
                    </button>
                    <button type="button" data-toggle="modal" data-target="#supprimer" class="btn btn-sm btn-danger float-right">
                        <i class='fas fa-trash'></i> <?= delete;?>
                    </button>
                </div>
            </div>

        </div>
    </div>
    </div>

    <div class="modal" id="supprimer" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn  btn-sm btn-secondary" data-dismiss="modal"><?= close;?></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5><?= confirmation_delete_user;?></h5>
                        </div>
                        <div class="col-12 text-center  mt-4">
                            <button type="submit" name="submit" value="supprimer" class="btn btn-sm btn-danger">
                                <i class='fas fa-trash'></i> <?= delete_definitively;?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="" method="post">
    <div class="modal" id="reset" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><?= reset_password;?></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <input type="password" name="password" class="form-control" placeholder="<?= password;?>">
                        </div>
                        <div class="col-12 mt-2">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="<?= password_confirmation;?>">
                        </div>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn  btn-sm btn-secondary" data-dismiss="modal"><?= close;?></button>
                            <button type="submit" name="submit" value="reset_password" class="btn btn-sm btn-success">
                                <i class="fas fa-key"></i> <?= reset;?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</form>