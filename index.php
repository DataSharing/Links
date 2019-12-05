<!--
 * @version 1.0
 -------------------------------------------------------------------------
 Links
 Copyright (C) 2019 By DataSharing.
 -------------------------------------------------------------------------
 LICENSE
 This file is part of Links.
 Links is free; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 3 of the License, or any later version.
 Links is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Links. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 * @author Walid H. <datasharing7@gmail.com>
 -->

<?php include __DIR__ . '/class/controller.class.php'; ?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="<?= $app['site_url']; ?>/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $app['site_url']; ?>/public/css/app.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script type="text/javascript" src="<?= $app['site_url']; ?>/public/js/jquery.js"></script>
    <script type="text/javascript" src="<?= $app['site_url']; ?>/public/js/popper.min.js"></script>
    <script type="text/javascript" src="<?= $app['site_url']; ?>/public/js/chart.js"></script>
    <script type="text/javascript" src="<?= $app['site_url']; ?>/public/js/app.js"></script>
    <title><?= $app['nom_du_site']; ?></title>
</head>

<body>
    <div class="container">
        <?= Controller::load(); ?>
    </div>
    <script type="text/javascript" src="<?= $app['site_url']; ?>/public/js/bootstrap.min.js"></script>
</body>

</html>
