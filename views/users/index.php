<div class="card">
    <div class="card-body">
        <form action="" method="get" class="row mb-4">
            <div class="col-12 col-lg-2">
                <button type="button" data-toggle="modal" data-target="#add" class="btn btn-success w-100">
                    <i class="fas fa-user"></i> <?= add;?>
                </button>
            </div>

            <div class="col-12 col-lg-7">
                <input type="text" name="recherche" placeholder="<?= your_search;?>" class="form-control" value="<?= isset($_GET['recherche']) ? htmlentities($_GET['recherche']) : ''; ?>">
            </div>
            <div class="col-12 col-lg-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="fas fa-search"></i> <?= search;?>
                </button>
            </div>
            <div class="col-12 col-lg-1">
                <a href='?reset' class="btn btn-danger w-100">
                    <i class="fas fa-times-circle"></i>
                </a>
            </div>
        </form>
        <form action="" method="post">
            <div class="modal fade bd-example-modal-lg" id="add" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title h4" id="myExtraLargeModalLabel"><?= add_user;?></h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-lg-2 mb-2">
                                    <select name="admin" class="custom-select">
                                        <option value="0">Usager</option>
                                        <option value="1">Admin</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-10 mb-2">
                                    <input type="text" name="login" class="form-control" placeholder="Login">
                                </div>
                                <div class="col-12 col-lg-6 mb-2">
                                    <input type="password" name="password" class="form-control" placeholder="<?= password;?>">
                                </div>
                                <div class="col-12 col-lg-6 mb-2">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="<?= password_confirmation;?>">
                                </div>
                                <div class="col-12 col-lg-2">
                                    <button type="submit" name="submit" value="add" class="btn btn-success w-100">
                                        <i class="fas fa-user"></i> <?= add;?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <table class="table table-striped">
            <tr>
                <th>#id</th>
                <th>Login</th>
                <th>Date modification</th>
                <th>Date création</th>
            </tr>
            <?php
            if (empty($data['users'])) {
                echo "<tr class='badge-warning'>";
                echo "<td colspan=5 >".no_results."</td>";
                echo "</tr>";
            } else {
                foreach ($data['users'] as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    $icone = "user";
                    if ($user['admin'] == 1) $icone = "user-shield";
                    echo "<td><a href='users?id=" . $user['id'] . "'><i class='fas fa-" . $icone . "'></i> " . $user['login'] . "</a></td>";
                    echo "<td>" . $user['date_modification'] . "</td>";
                    echo "<td>" . $user['date_creation'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</div>