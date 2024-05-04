<?php
if(!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true)
{
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/MinorModel.php');
require_once(__DIR__ . '/../config/database.php');

class MinorController{

    
    public function index($message) {   
        
        global $conn;
        $minorModel = new MinorModel();
        $minors = $minorModel->getMinors($conn);

        require_once(__DIR__ . '/../views/minorView.php');

    }

    public function editMinor()
    {
        global $conn;
        $minorModel = new MinorModel();

        $mID = $_POST['mID'];
        $name = $_POST['name'];

        $message = $minorModel->editMinor($conn, $mID, $name);

        $this->index($message);
    }

    public function addMinor()
    {
        try {
            global $conn;
            $minorModel = new MinorModel();

            $name = $_POST['name'];

            $message = $minorModel->addMinor($conn, $name);
           

           // $this->index($message);
            // You can add logging here for successful cases.
        } catch (Exception $e) {
            // Log or display the error message.
            echo "Error: " . $e->getMessage();
        }
        
    }

    public function deleteMinor()
    {
        global $conn;
        $minorModel = new MinorModel();

        $minorID = $_POST['minorID'];

        $message = $minorModel->deleteMinorModel($conn, $minorID);

        $this->index($message);
    }


    public function getCourses($id)
    {
        global $conn;
        $minorModel = new MinorModel();

        return $minorModel->getMinorCourses($conn, $id);
    }
}

$minorController = new MinorController();

if(isset($_GET['minor']))
{
    if(isset($_GET['mID']))
        $minorController->editMinor();
    
    if(isset($_GET['add']))
        $minorController->addMinor();

    if(isset($_GET['delete']))
        $minorController->deleteMinor();

    $minorController->index('');
}
    

?>