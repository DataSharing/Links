<div class="card">
    <div class="card-body">
        <form action="" method="get" class="row mb-4">
            <div class="col-12 col-lg-2">
                <button type="button" data-toggle="modal" data-target="#add" class="btn btn-success w-100">
                    <i class="fas fa-link"></i> <?= add;?>
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
            <div class="modal fade bd-example-modal-xl" id="add" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title h4" id="myExtraLargeModalLabel"><?= add_shortcut;?></h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-lg-5">
                                    <input type="text" id="shortcut" onkeyup='check_duplicate();' autocomplete="off" name="shortcut" class="form-control" placeholder="<?= shortcut;?>">
                                </div>
                                <div class="col-12 col-lg-5">
                                    <input type="url" name="redirection" class="form-control" placeholder="Redirection">
                                </div>
                                <div class="col-12 col-lg-2">
                                    <button type="submit" name="submit" value="add" class="btn btn-success w-100">
                                        <i class="fas fa-link"></i> <?= add;?>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <div id="duplicate"></div>
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
                <th><?= shortcut;?></th>
                <th>Redirection</th>
                <?php if($_SESSION['admin']) echo '<th>'.created_by.'</th>';?>
                <th>Date modification</th>
                <th>Date création</th>
            </tr>
            <?php
            if (empty($data['shortcuts'])) {
                echo "<tr class='badge-warning'>";
                echo "<td colspan=6 >".no_results."</td>";
                echo "</tr>";
            } else {
                foreach ($data['shortcuts'] as $shortcut) {
                    echo "<tr>";
                    echo "<td>#" . $shortcut['id'] . "</td>";
                    echo "<td><a href='shortcuts?id=" . $shortcut['id'] . "'><i class='fas fa-link'></i> " . $shortcut['shortcut'] . "</a></td>";
                    echo "<td>" . $shortcut['redirect_to'] . "</td>";
                    if($_SESSION['admin']) echo '<td>'.$shortcut['created_by'].'</td>';
                    echo "<td>" . $shortcut['date_modification'] . "</td>";
                    echo "<td>" . $shortcut['date_creation'] . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
    </div>
</div>