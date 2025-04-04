<?php
if (!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true) {
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/adminModel.php');
require_once(__DIR__ . '/../config/database.php');

class AdminController
{
    public function index($message)
    {
        global $conn;
        $adminModel = new AdminModel();

        $admins = $adminModel->getAdmins($conn);

        require_once(__DIR__ . '/../views/adminsView.php');
    }

    public function register()
    {

        global $conn;
        $adminModel = new AdminModel();

        $email = $_POST['email'];
        $name = $_POST['name'];
        $lname = $_POST['lname'];
        $pass = $_POST['password'];
        $privileges = $_POST['privileges'];

        $result = $adminModel->registerAdmin($conn, $email, $name, $lname, $pass, $privileges);

        $this->index($result);
    }

    public function editadmin($email)
    {
        global $conn;
        $adminModel = new AdminModel();

        if ($email == '' || $email == NULL)
            require_once(__DIR__ . '/../views/adminsView.php');
        else {
            $admin = $adminModel->getAdmin($conn, $email);
            if ($admin)
                require_once(__DIR__ . '/../views/editAdminView.php');
            else
                require_once(__DIR__ . '/../views/adminsView.php');
        }
    }


    public function changeadmininfo()
    {
        global $conn;
        $adminModel = new AdminModel();

        $old_email = $_POST['old_email'];
        // $old_pass = $_POST['old_p'];

        $email = $_POST['email'];

        // if ($old_pass != $_POST['pass']) {
        $pass = $_POST['pass'];
        // } else
        //     $pass = $old_pass;

        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $priv = $_POST['privileges'];

        $result = $adminModel->changeAdminInfoModel($conn, $old_email, $email, $fname, $lname, $priv, $pass);

        $message = "successEdit";

        if ($_SESSION['privileges'] == 0)
            header("Location: index.php");

        else if ($result == 'success') {
            header('Location: ?admin&edit=' . $email . '&message=' . $message);
            die;
        } else {
            $message = 'failureEdit';
            header('Location: ?admin&edit=' . $old_email . '&message=' . $message);
            die;
        }
    }

    public function deleteadmin($email)
    {
        global $conn;
        $adminModel = new AdminModel();

        $result = $adminModel->deleteAdminModel($conn, $email);



        $message = "success";
        if ($result == 'failure')
            $message = "failure";

        $this->index($message);
    }
}


$adminController = new AdminController();

if (isset($_GET['register']))
    $adminController->register();
else if (isset($_GET['edit']))
    $adminController->editadmin($_GET['edit']);
else if (isset($_GET['changes']))
    $adminController->changeadmininfo();
else if (isset($_GET['delete']))
    $adminController->deleteadmin($_GET['delete']);
else
    $adminController->index('');

