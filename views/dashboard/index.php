<?php if ($_SESSION['admin']) { ?>
    <div class="row mb-4 text-center">
        <div class="col-4">
            <div class="card">
                <div class="card-body pt-2 pb-0">
                    <p><strong><i class="fas fa-link"></i> <?= shortcuts; ?></strong></p>
                    <p><?= $data['nb_shortcuts']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body pt-2 pb-0">
                    <p><strong><i class="fas fa-directions"></i> Redirection</strong></p>
                    <p><?= $data['nb_redirection']; ?></p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body pt-2 pb-0">
                    <p><strong><i class="fas fa-users"></i> <?= users; ?></strong></p>
                    <p><?= $data['nb_users']; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<div class="card">
    <div class="card-body">
        <canvas id="myChart"></canvas>
    </div>
</div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            datasets: [{
                label: 'Redirection par mois',
                data: [
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        if (isset($data['graph'][$i])) {
                            echo $data['graph'][$i] . ',';
                        } else {
                            echo '0,';
                        }
                    }
                    ?>
                ],
                backgroundColor: 'transparent',
                borderColor: '#333',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>