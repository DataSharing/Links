<?php

class Shortcuts extends Controller
{
    const shortcutsNoAutorized = ['dashboard', 'shortcuts', 'users', 'login', 'profil'];

    public function __construct()
    {
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

        $requete = $DB->prepare('select * from shortcuts where id=' . $id);
        $requete->execute();
        $data['shortcut'] = $requete->fetch();

        if ($data['shortcut']['id_user'] != $_SESSION['id'] && $_SESSION['admin'] == false) header('location:shortcuts');

        $requete_1 = $DB->prepare('select *  from stats where id_url=' . $id . ' order by id desc');
        $requete_1->execute();
        $data['nb'] = $requete_1->rowCount();
        $data['historique'] = $requete_1->fetchall();

        $this->view('shortcuts/shortcut', $data);
    }

}
