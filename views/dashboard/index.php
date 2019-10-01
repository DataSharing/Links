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
        type: 'bar',
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
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderWidth: 1
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