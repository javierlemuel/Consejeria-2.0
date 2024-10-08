<?php
if (!isset($_SESSION['student_authenticated']) && $_SESSION['student_authenticated'] !== true) {
    header("Location: ./index.php");
    exit;
}
// controllers/expedientesController.php
require_once(__DIR__ . '/../models/ExpedienteModel.php');
require_once(__DIR__ . '/../config/database.php');
if (session_status() == PHP_SESSION_NONE) {
    // Start the session
    session_start();
}

class ExpedienteController
{
    public function index()
    {
        global $conn;
        $studentModel = new StudentModel();
        if (session_status() == PHP_SESSION_NONE) {
            // Start the session
            session_start();
        }

        //obtenemos el numero de estudiante        
        if (isset($_SESSION['student_num'])) {
            $student_num = $_SESSION['student_num'];
        }


        //get student info
        $studentInfo = $studentModel->getStudentInfo($conn, $student_num);

        //get student courses
        $ccomStudentCourses = $studentModel->getStudentCCOMCourses($conn, $student_num, $studentInfo['cohort_year']);
        $generalesStudentCourses = $studentModel->getStudentGeneralCourses($conn, $student_num,  $studentInfo['cohort_year']);
        $ccomElectives = $studentModel->getCCOMElectives($conn, $student_num, $studentInfo['minor']);
        $freeElectives = $studentModel->getFREElectives($conn, $student_num, $studentInfo['minor']);
        $otherCourses = $studentModel->getOtherCourses($conn, $student_num);
        $minor = $studentModel->getMinor($conn, $student_num, $studentInfo['minor']);



        require_once(__DIR__ . '/../views/expedienteView.php');
    }
}

$expedienteController = new ExpedienteController();
$expedienteController->index();
