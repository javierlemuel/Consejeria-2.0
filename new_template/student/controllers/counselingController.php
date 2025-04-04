<?php

if (session_status() == PHP_SESSION_NONE) {
    // Start the session
    session_start();
}
if (!isset($_SESSION['student_authenticated']) || $_SESSION['student_authenticated'] !== true) {
    header("Location: ../index.php");
    exit;
} else {

    // controllers/expedientesController.php
    require_once(__DIR__ . '/../models/CounselingModel.php');
    require_once(__DIR__ . '/../config/database.php');

    class CounselingController
    {
        public function index()
        {
            $selectedCourses = "";
            global $conn;
            $counselingModel = new CounselingModel();


            //obtenemos el numero de estudiante        
            if (isset($_SESSION['student_num'])) {
                $student_num = $_SESSION['student_num'];
            }

            // Obtenemos la lista de las clases recomendadas
            $recommendedCourses = $counselingModel->getRecommendedCourses($conn, $student_num);

            // Obtenemos la lista de las clases de concentracion
            $concentrationCourses = $counselingModel->getConcentrationCourses($conn, $student_num);

            // Obtenemos la lista de las clases generales
            $generalCourses = $counselingModel->getGeneralCourses($conn, $student_num);

            $studentInfo = $counselingModel->getStudentInfo($conn, $student_num);

            //obtenemos la lista de los diferentes cohortes que existen en la base de datos
            $_SESSION['cohortes'] = $counselingModel->getCohortes($conn);
            //$cohortes = $counselingModel->getCohortes($conn);

            if (isset($_SESSION['student_num'])) {
                $_SESSION['full_student_name'] = $studentInfo['full_student_name'];
                $_SESSION['formatted_student_num'] = $studentInfo['formatted_student_num'];
                $_SESSION['email'] = $studentInfo['email'];
                $_SESSION['student_note'] = $studentInfo['student_note'];
                $_SESSION['conducted_counseling'] = $counselingModel->getCounselingLock($conn, $_SESSION['student_num']);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // You should check if the selectedCoursesList is not empty before proceeding.
                var_dump($_POST['selectedCoursesList']);
                
                if (!isset($_POST['selectedCoursesList']) || empty($_POST['selectedCoursesList'])) {
                    $counselingModel->confirmCounseling($conn, $student_num);
                    header("Location: ../index.php");
                    exit;
                } else if (isset($_POST['selectedCoursesList'])) {

                    $selectedCourses = $_POST['selectedCoursesList'];

                    // Save selected courses to the database using the Model
                    $counselingModel->setCourses($conn, $student_num, $selectedCourses);
                    header("Location: ../index.php");
                    exit;
                }
            }

            //get the student counseling status to create counseling button
            $lock = $counselingModel->getCounselingLock($conn, $student_num);
            // FIX HERE

            if ($lock == 1) {
                // $_SESSION['conducted_counseling'] = 1;
                $_SESSION['counseling_button'] = '<button type="submit" value="Submit" id="counseling_button" class="btn btn-warning self-end" disabled>Confirmar Consejeria</button>';
            } else {
                // $_SESSION['conducted_counseling'] = 0;
                $_SESSION['counseling_button']  = '<button type="submit" value="Submit" id="counseling_button" class="btn btn-warning self-end">Confirmar Consejeria</button>';
            }

            #$_SESSION['conducted_counseling'] = $conducted_counseling;

            //get the selected courses for the next term
            $selected_courses = $counselingModel->getStudentSelectedCourses($conn, $student_num);
            if ($selected_courses != null) {
                $_SESSION['selectedCourses'] = json_encode($selected_courses);
            } else {
                $_SESSION['selectedCourses'] = json_encode('');
            }


            require_once(__DIR__ . '/../views/counselingView.php');
            require_once(__DIR__ . '/../views/layouts/sidebar.php');
            require_once(__DIR__ . '/../views/layouts/header.php');
        }
    }

    $counselingController = new CounselingController();
    $counselingController->index();
}
