<?php

include __DIR__ . '/config/db.php';

if (
    $db['hostname'] == '' ||
    $db['dbname'] == ''   ||
    $db['username'] == '' ||
    $db['password'] == ''
) {
    echo "Il manque des informations dans le fichier config/db.php";
    exit;
}

try {
    $conn = new PDO("mysql:host=" . $db['hostname'] . ";dbname=" . $db['dbname'] . ";charset=utf8", "" . $db['username'] . "", "" . $db['password'] . "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
} catch (PDOException $e) {
    echo 'ERROR: ' . utf8_encode($e->getMessage());
}

if ($_POST) {
    if (isset($_POST['nom_du_site']) && isset($_POST['url_du_site']) && $_POST['nom_du_site'] != '' && $_POST['url_du_site'] != '') {
        //Fichier app.php
        $filename = __DIR__ . '/config/app.php';
        if (!is_writable($filename)) {
            echo '<span style="color:red">Le fichier "app.php" n\'est pas accessible en écriture !</span><br>';
            exit;
        }
        $salt = genererChaineAleatoire();
        $fichier = fopen(__DIR__ . '/config/app.php', 'w+');
        fwrite($fichier, '<?php' . PHP_EOL);
        fwrite($fichier, '$app["salt"]= "' . $salt . '";' . PHP_EOL);
        fwrite($fichier, '$app["nom_du_site"]= "' . $_POST['nom_du_site'] . '";' . PHP_EOL);
        fwrite($fichier, '$app["site_url"]= "' . $_POST['url_du_site'] . '";');
        fclose($fichier);
        echo "<span style='color:green;'>Création du fichier app.php' : OK!</span><br>";
        //Création des tables
        $shortcuts_table = "CREATE TABLE `shortcuts` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `shortcut` LONGTEXT NULL,
            `redirect_to` LONGTEXT NULL,
            `id_user` INT(11) NULL DEFAULT NULL,
            `date_modification` DATETIME NULL DEFAULT NULL,
            `date_creation` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB;";
        $stats_table = "CREATE TABLE `stats` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `id_url` INT(11) NULL DEFAULT NULL,
            `ip` VARCHAR(50) NULL DEFAULT NULL,
            `country` VARCHAR(255) NULL DEFAULT NULL,
            `regionName` VARCHAR(255) NULL DEFAULT NULL,
            `city` VARCHAR(255) NULL DEFAULT NULL,
            `zip` VARCHAR(255) NULL DEFAULT NULL,
            `date_redirect` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB;";
        $users_table = "CREATE TABLE `users` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `login` VARCHAR(150) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `admin` INT(11) NULL DEFAULT '0',
            `language` VARCHAR(10) NOT NULL DEFAULT 'fr',
            `date_modification` DATETIME NOT NULL,
            `date_creation` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        )
        COLLATE='utf8_general_ci'
        ENGINE=InnoDB;";
        if ($conn->query($shortcuts_table)) {
            echo "<span style='color:green;'>Création de la table 'shortcuts' : OK!</span><br>";
        } else {
            echo "<span style='color:red;'>Création de la table 'shortcuts' : ERREUR!</span><br>";
        }
        if ($conn->query($stats_table)) {
            echo "<span style='color:green;'>Création de la table 'stats' : OK!</span><br>";
        } else {
            echo "<span style='color:red;'>Création de la table 'stats' : ERREUR!</span><br>";
        }
        if ($conn->query($users_table)) {
            echo "<span style='color:green;'>Création de la table 'users' : OK!</span><br>";
        } else {
            echo "<span style='color:red;'>Création de la table 'users' : ERREUR!</span><br>";
        }
        //Création admin
        $pwd = sha1(sha1("admin") . $salt);
        $requete = 'INSERT INTO users(login,password,admin,date_modification,date_creation) values("admin","' . $pwd . '",1,"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")';
        if ($conn->query($requete)) {
            echo "<span style='color:green;'>Création du compte admin : OK!</span><br><br>";
            echo "<span><strong>Login : <i>admin</i> | Password : <i>admin</i></strong></span><br><br>";
            echo "<a href='" . $_POST['url_du_site'] . "'>Se connecter au site</a><br><br>";
            echo "<span style='color:red'>N'oubliez pas de supprimer le fichier 'install.php' à la racine du site!</span>";
        } else {
            echo "<span style='color:red'>ERREUR : Le compte admin n'a pas été créé!</span><br>";
        }
    } else {
        echo "<span style='color:red;'>Tous les champs sont obligatoire!</span><br>";
    }
} else {
    echo "<form action='' method='post'>";
    echo "<table>";
    echo "<tr>";
    echo "<td>Nom du site : </td>";
    echo "<td><input type='text' name='nom_du_site' ></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Url du site (sans le slash de fin) : </td>";
    echo "<td><input type='url' name='url_du_site' ></td>";
    echo "</tr>";
    echo "<tr><td colspan=2><input type='submit' value='Enregistrer'></td></tr>";
    echo "</form>";
}
function genererChaineAleatoire($longueur = 50)
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $longueurMax = strlen($caracteres);
    $chaineAleatoire = '';
    for ($i = 0; $i < $longueur; $i++) {
        $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
    }
    return $chaineAleatoire;
}
