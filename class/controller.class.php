<?php
include_once __DIR__ . '/../config/app.php';

class Controller
{
    const pageAutorized = ['dashboard', 'shortcuts', 'users', 'login', 'profil'];

    public static function load()
    {
        include_once __DIR__ . '/../config/db.php';
        include_once __DIR__ . '/../class/db.class.php';
        if (
            $db['username'] == '' ||
            $db['password'] == '' ||
            $db['dbname']   == '' ||
            $db['hostname'] == ''
        ) {
            header('location:install.php');
        }
        $db = new db($db['username'], $db['password'], $db['hostname'], $db['dbname']);

        $get = isset($_GET['p']) ? htmlentities($_GET['p']) : 'dashboard';
        $page = $get . '.class.php';

        if (file_exists(__DIR__ . '/../class/' . $page) && in_array($get, self::pageAutorized)) {
            session_start();
            if (!isset($_SESSION['id'])) {
                include_once __DIR__ . '/../class/login.class.php';
                $login = new Login();
                $login->index();
            } else {
                include_once __DIR__ . '/../class/' . $page;
                include __DIR__ . '/../config/app.php';
                include_once __DIR__ . '/../local/' . $_SESSION['language'] . '.php';
                $data['site_url'] = $app['site_url'];
                $ctrl = new Controller();
                $ctrl->view('app/main', $data);
                $app = new $get();
                $app->index();
            }
        } else {
            include_once __DIR__ . '/../class/redirect.class.php';

            if (!Redirect::check($get)) {
                include_once __DIR__ . '/../404.php';
            }
        }
    }

    public function view($path = null, $data = [])
    {
        include_once __DIR__ . '/../views/' . $path . '.php';
    }
}
