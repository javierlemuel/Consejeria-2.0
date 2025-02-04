<?php
if (!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true) {
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/ClassesModel.php');
require_once(__DIR__ . '/../models/ReporteModel.php');
require_once(__DIR__ . '/../models/TermsModel.php');
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . "/../global_classes/utils.php");
//session_start();

class ClassesController
{
    public function index()
    {

        global $conn;
        $classesModel = new ClassesModel();
        $reporteModel = new ReporteModel();
        $termsModel = new TermsModel();

        $terms = $termsModel->getTerms($conn);

        // Search query (q) and pagination (p)
        $q = $_GET["q"] ?? "";
        $q = sanitizeSearch($q);
        $p = $_GET["p"] ?? 1;
        try {
            $p = (int)$p;
        } catch (Exception $e) {
            $p = 1;
        }

        if (isset($_GET['lista'])) {
            $matriculados = $classesModel->getStudentsMatriculadosModel($conn, $_GET['class']);
            $course = $_GET['class'];
            require_once(__DIR__ . '/../views/listaView.php');
        } elseif (isset($_GET['ccomelectives'])) {

            $term = $termsModel->getActiveTerm($conn);

            $courses = $classesModel->getCcomElectives($conn, $q, $p);
            $amountOfPages = $classesModel->getPageAmount();
            $category = 'electivas';
        } elseif (isset($_GET['generalclasses'])) {
            $term = $termsModel->getActiveTerm($conn);
            $courses = $classesModel->getGeneralCourses($conn, $q, $p);
            $amountOfPages = $classesModel->getPageAmount();
            $category = 'generales';
            $current_class = 'generalclasses';
        } elseif (isset($_GET['dummyclasses'])) {
            $term = $termsModel->getActiveTerm($conn);
            $courses = $classesModel->getDummyCourses($conn, $q, $p);
            $amountOfPages = $classesModel->getPageAmount();
            $category = 'dummy';
        } elseif (isset($_GET['offer'])) {
            // if (isset($_GET['otherterm'])) {
            //     $term = $_GET['otherterm'];
            //     $courses = $classesModel->getOfferCourses($conn, $term);
            //     $category = 'oferta';
            // } else {
            $term = $termsModel->getActiveTerm($conn);
            $courses = $classesModel->getOfferCourses($conn, $term);
            $category = 'oferta';
            // }
        } elseif (isset($_GET['addOffer']) && isset($_GET['code'])) {
            $courseID = $_GET['code'];
            $message = $classesModel->addToOffer($conn, $courseID);
            $term = $termsModel->getActiveTerm($conn);
            $courses = $classesModel->getCcomCourses($conn);
            $category = 'concentracion';
            header('Location: ?classes&message=' . $message);
            die;
        } elseif (isset($_GET['removeOffer']) && isset($_GET['code'])) {
            echo "HEY";
            $courseID = $_GET['code'];
            $message = $classesModel->removeFromOffer($conn, $courseID);
            $term = $termsModel->getActiveTerm($conn);
            $courses = $classesModel->getOfferCourses($conn, $term);
            $category = 'oferta';
            header('Location: ?offer&message=' . $message);
            die;
        } elseif (isset($_GET['newterm'])) {
            if (isset($_POST['term'])) {

                // error_reporting(E_ALL);
                // ini_set('display_errors', 'On');

                //Generate reports
                $new_term = $_POST['term'];
                $studentsAconsejados = $reporteModel->getStudentsAconsejados($conn);
                $studentsSinCCOM = $reporteModel->getStudentsSinCCOM($conn);
                $studentsRegistrados = $reporteModel->getRegistrados($conn);
                $studentsEditados = $reporteModel->getEditados($conn);
                $studentsPerClass = $reporteModel->getStudentsPerClass($conn);
                $term = $termsModel->getActiveTerm($conn);

                // Data array for the first table
                $firstTableData = [
                    ["Estudiantes Aconsejados", $studentsAconsejados],
                    ["Estudiantes Aconsejados sin Cursos de CCOM", $studentsSinCCOM],
                    ["Estudiantes que realizaron su Consejeria", $studentsRegistrados],
                    ["Expedientes revisados", $studentsEditados],
                ];

                // Data array for the second table
                $secondTableData = [];
                if (isset($studentsPerClass)) {
                    foreach ($studentsPerClass as $s) {
                        $secondTableData[] = [$s['crse_code'], $s['count']];
                    }
                }

                // Generate combined CSV content
                $combinedCsvContent = "Tipo de Reporte,Cantidad\n";
                foreach ($firstTableData as $row) {
                    $combinedCsvContent .= implode(',', $row) . "\n";
                }

                // Add a distinctive line or comment to separate tables
                $combinedCsvContent .= "\n";
                $combinedCsvContent .= "# Reportes de estudiantes por curso de CCOM\n";

                // Generate CSV content for the second table
                foreach ($secondTableData as $row) {
                    $combinedCsvContent .= implode(',', $row) . "\n";
                }

                // Generate session variables for page refresh
                $_SESSION['csv_content'] = $combinedCsvContent;
                $combinedCsvFileName = "Report" . $term . ".csv";
                $_SESSION['filename'] = $combinedCsvFileName;


                // Generate the new term
                $classesModel->setNewTerm($conn, $new_term);
            } else {
                // Get present term if not creating a new term
                $term = $termsModel->getActiveTerm($conn);
            }
            // Get courses in offer and return to offer page
            $courses = $classesModel->getOfferCourses($conn, $_POST['term']);
            $category = 'oferta';
            echo '<script>';
            echo 'window.location.href = "?offer";';
            echo '</script>';
            exit;
        } else //isset 'classes'
        {
            $courses = $classesModel->getCcomCourses($conn, $q, $p);
            $amountOfPages = $classesModel->getPageAmount();
            $category = 'concentracion';
            $term = $termsModel->getActiveTerm($conn);
        }

        if (!isset($_GET['lista']))
            require_once(__DIR__ . '/../views/classesView.php');
    }

    public function getMatriculados($course)
    {
        global $conn;
        $classesModel = new ClassesModel();
        return $classesModel->getMatriculadosModel($conn, $course);
    }
}

$classesController = new ClassesController();
$classesController->index();

// if (isset($_GET['callController'])) {
//     $classesController = new classesController();
//     $classesController->index();
// }
