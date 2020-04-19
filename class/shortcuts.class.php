<?php

class Shortcuts extends Controller
{
    public $site_url;
    const shortcutsNoAutorized = ['dashboard', 'shortcuts', 'users', 'login', 'profil'];

    public function __construct()
    {
        include __DIR__ . '/../config/app.php';
        $this->site_url = $app['site_url'];
        $this->traitement();
    }

    public function traitement()
    {
        global $DB;

        if ($_POST) {
            $submit = isset($_POST['submit']) ? $_POST['submit'] : null;

            if ($submit == "add") {
                $shortcut = isset($_POST['shortcut']) ? htmlentities($_POST['shortcut']) : null;
                $redirection = isset($_POST['redirection']) ? htmlentities($_POST['redirection']) : null;
                $check_duplicate = $DB->prepare('select id from shortcuts where shortcut="' . $shortcut . '"');
                $check_duplicate->execute();
                if ($shortcut != null && $redirection != null && $check_duplicate->rowCount() == 0 && !in_array($shortcut, self::shortcutsNoAutorized)) {
                    $requete = $DB->prepare('INSERT INTO shortcuts(shortcut,redirect_to,id_user,date_creation,date_modification) values("' . $shortcut . '","' . $redirection . '","' . $_SESSION['id'] . '","' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")');
                    if ($requete->execute()) {
                        $this->view('app/notif', 'Le raccourcis a bien été ajouté');
                    }
                } else {
                    if ($check_duplicate->rowCount() >= 1) {
                        $this->view('app/error', 'Le raccourcis existe déjà!');
                    } elseif (in_array($shortcut, self::shortcutsNoAutorized)) {
                        $this->view('app/error', 'Raccourci non autorisé!');
                    } else {
                        $this->view('app/error', 'Tous les champs sont obligatoire!');
                    }
                }
            }

            if ($submit == "enregistrer" && isset($_GET['id'])) {
                $old_shortcut = isset($_POST['old_shortcut']) ? htmlentities($_POST['old_shortcut']) : null;
                $shortcut = isset($_POST['shortcut']) ? htmlentities($_POST['shortcut']) : null;
                $redirection = isset($_POST['redirection']) ? htmlentities($_POST['redirection']) : null;
                $check_duplicate = $DB->prepare('select id from shortcuts where shortcut="' . $shortcut . '"');
                $check_duplicate->execute();

                if ($shortcut != null && $redirection != null && ($check_duplicate->rowCount() == 0  || $old_shortcut == $shortcut) && !in_array($shortcut, self::shortcutsNoAutorized)) {
                    $requete = $DB->prepare('UPDATE shortcuts SET shortcut="' . $shortcut . '", redirect_to="' . $redirection . '",date_modification="' . date('Y-m-d H:i:s') . '" where id=' . intval($_GET['id']));
                    if ($requete->execute()) {
                        $this->view('app/notif', 'Le raccourcis a bien été modifié');
                    }
                } else {
                    if ($check_duplicate->rowCount() >= 1) {
                        $this->view('app/error', 'Le raccourcis existe déjà!');
                    } elseif (in_array($shortcut, self::shortcutsNoAutorized)) {
                        $this->view('app/error', 'Raccourci non autorisé!');
                    } else {
                        $this->view('app/error', 'Tous les champs sont obligatoires!');
                    }
                }
            }

            if ($submit == "supprimer" && isset($_GET['id'])) {
                $requete = $DB->prepare('delete from shortcuts where id=' . intval($_GET['id']));
                if ($requete->execute()) {
                    header('location:shortcuts');
                } else {
                    $this->view('app/error', 'Erreur de suppression du raccourci!');
                }
            }

            if (isset($_POST['ip_unique_oui'])) {
                $_SESSION['ip_unique'] = 'active';
            }

            if (isset($_POST['ip_unique_non'])) {
                $_SESSION['ip_unique'] = '';
            }
        }
    }

    public function index()
    {
        if (isset($_GET['id'])) {
            $this->shortcutForm(intval($_GET['id']));
        } else {
            $this->indexForm();
        }
    }

    public function indexForm()
    {
        global $DB;

        if (isset($_GET['recherche'])) {
            $admin = 's.id_user=' . $_SESSION['id'] . ' AND ';
            if ($_SESSION['admin'] == 1) $admin = '';
            $where = "where " . $admin . " s.shortcut like '%" . htmlentities($_GET['recherche']) . "%' 
            OR s.redirect_to like '%" . htmlentities($_GET['recherche']) . "%'";
        } else {
            $where = ' where id_user=' . $_SESSION['id'];
            if ($_SESSION['admin'] == 1) $where = '';
        }

        $requete = $DB->prepare('select s.id,s.shortcut,s.redirect_to,s.date_modification,s.date_creation,u.login as created_by from shortcuts s left join users u on u.id=s.id_user ' . $where . ' order by s.id desc');
        $requete->execute();
        $data['shortcuts'] = $requete->fetchall();
        $this->view('shortcuts/index', $data);
    }

    public function shortcutForm($id)
    {
        global $DB;
        if (!isset($_SESSION['ip_unique'])) $_SESSION['ip_unique'] = 'active';

        $data['id'] = $id;
        $data['site_url'] = $this->site_url;
        $data['vue'] = isset($_GET['vue']) ? htmlentities($_GET['vue']) : 'annee';

        $requete = $DB->prepare('select * from shortcuts where id=' . $id);
        $requete->execute();
        $data['shortcut'] = $requete->fetch();

        if ($data['shortcut']['id_user'] != $_SESSION['id'] && $_SESSION['admin'] == false) header('location:shortcuts');

        $requete_1 = $DB->prepare('select *  from stats where id_url=' . $id . ' order by id desc');
        $requete_1->execute();
        $data['nb'] = $requete_1->rowCount();
        $data['historique'] = $requete_1->fetchall();

        //Graphique
        $data['data_graph'] = "";

        if ($data['vue'] == "annee") {
            //// Vue année ////
            $x_label = 12;
            $distinct = '';
            if ($_SESSION['ip_unique'] == 'active') $distinct = 'distinct ';
            $group_by = 'GROUP BY MONTH(date_redirect)';
            $data['label'] = "'Nombre de redirection'";
            $data['labels'] = "'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'";
            $data_graph_query = 'select MONTH(date_redirect) as x,COUNT(' . $distinct . 'ip) as nb FROM stats 
            WHERE id_url = ' . intval($id) . ' and YEAR(date_redirect) = ' . date('Y') . ' ' . $group_by;
        }

        if ($data['vue'] == "mois") {
            //// Vue mois ////
            $x_label = date('t');
            $distinct = '';
            $group_by = 'GROUP BY DAY(date_redirect)';
            if ($_SESSION['ip_unique'] == 'active') $distinct = 'distinct ';
            $data['label'] = "'Nombre de redirection'";
            $data['labels'] = "";
            for ($i = 1; $i <= $x_label; $i++) {
                $data['labels'] = $data['labels'] . "'" . sprintf('%02d', $i) . "-" . date('m') . "',";
            }
            $data_graph_query = 'select  DAY(date_redirect) as x,COUNT(' . $distinct . 'ip) as nb FROM stats 
            WHERE id_url = ' . intval($id) . ' and MONTH(date_redirect) = ' . date('m') . ' and YEAR(date_redirect) = ' . date('Y') . ' ' . $group_by;
        }

        if ($data['vue'] == "jour") {
            //// Vue mois ////
            $x_label = 23;
            $distinct = '';
            $group_by = 'GROUP BY HOUR(date_redirect)';
            if ($_SESSION['ip_unique'] == 'active') $distinct = 'distinct ';
            $data['label'] = "'Nombre de redirection'";
            $data['labels'] = "";
            for ($i = 1; $i <= $x_label; $i++) {
                $data['labels'] = $data['labels'] . "'" . sprintf('%02d', $i) . "h00',";
            }
            $data_graph_query = 'select HOUR(date_redirect) as x,COUNT(' . $distinct . 'ip) as nb FROM stats 
            WHERE id_url = ' . intval($id) . ' and DAY(date_redirect) = ' . date('d') . ' and MONTH(date_redirect) = ' . date('m') . ' and YEAR(date_redirect) = ' . date('Y') . ' ' . $group_by;
        }

        $data_graph_query = $DB->prepare($data_graph_query);
        $data_graph_query->execute();

        foreach ($data_graph_query->fetchall() as $d) {
            $data_graph_mois[$d['x']] = $d['nb'];
        }

        for ($i = 1; $i <= $x_label; $i++) {
            if (isset($data_graph_mois[$i])) {
                $data['data_graph'] = $data['data_graph'] . $data_graph_mois[$i] . ",";
            } else {
                $data['data_graph'] = $data['data_graph'] . "0,";
            }
        }

        $this->view('shortcuts/shortcut', $data);
    }
}
