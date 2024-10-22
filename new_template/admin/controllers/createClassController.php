<?php
if(!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true)
{
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/CreateClassModel.php');
require_once(__DIR__ . '/../config/database.php');
class CreateClassController{
    public function index() {   
        global $conn;
        $createClassModel = new CreateClassModel();

       if(isset($_GET['createclass']) && !isset($_GET['code']))
       {
        $courseType = $_GET['createclass'];
        $minors = $createClassModel->getMinors($conn);
        require_once(__DIR__ . '/../views/createClassView.php');
       }
       elseif(isset($_GET['createclass']) && isset($_GET['code']))
       {
         $crse_code = $_POST['code'];
         $crse_name = $_POST['name'];
         $cred = $_POST['cred'];
         $type = $_POST['type'];

         if($_GET['code'] == 'CCOM')
         {
            $level = $_POST['level'];
            $minor = $_POST['minor'];
            $errorMessage = $createClassModel->createCcomCourse($conn, $crse_code, $crse_name, $cred,
                                            $type, $level, $minor);
            if($errorMessage)
            {
                header('Location: ?createclass=CCOM&error='.$errorMessage);
                return;
            }
            else{
                header('Location: ?classes');
                return;
            }
         }

         else
         {  

            $required = $_POST['required'];
            $errorMessage = $createClassModel->createGeneralCourse($conn, $crse_code, $crse_name, $cred,
                                            $type, $required);
            if($errorMessage)
            {
                header('Location: ?createclass=General&error='.$errorMessage);
                return;
            } else {
                header('Location: ?classes');
                return;
            }
         }
       }
    }
}

$createClassController = new CreateClassController();
$createClassController->index();

