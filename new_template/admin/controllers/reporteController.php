<?php
if(!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true)
{
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/ReporteModel.php');
require_once(__DIR__ . '/../models/TermsModel.php');
require_once(__DIR__ . '/../models/StudentModel.php');
require_once(__DIR__ . '/../config/database.php');

class ReporteController{
    public function index() {   
        global $conn;
        $reporteModel = new ReporteModel();
        $termModel = new TermsModel();
        $studentModel = new StudentModel();

        $studentsAconsejados = $reporteModel->getStudentsAconsejados($conn);
        $studentsSinCCOM = $reporteModel->getStudentsSinCCOM($conn);
        $studentsNoConsejeria = $reporteModel->getStudentsNoConsejeria($conn);
        $studentsActivos = $reporteModel->getStudentsActivos($conn);
        $studentsInactivos = $reporteModel->getStudentsInactivos($conn);
        $studentsRegistrados = $reporteModel->getRegistrados($conn);
        $studentsRevisados = $reporteModel->getRevisados($conn);
        $studentsPerClass = $reporteModel->getStudentsPerClass($conn);
        $studentsIncompletos = $reporteModel->getStudentsIncompletos($conn);
        // $reporteModel->moveRepeatedCourses($conn);
        // $reporteModel->deleteRepeatedRecommendations($conn);
        $activeTerm = $termModel->getActiveTerm($conn);
        $counselingTerm = $termModel->getCounselingTerm($conn);
        $count = 0;

        if(isset($_GET['code']))
        {
            $type = $_GET['code'];

            if ($type == 'updateinactive') {
                /* el programa solo entra a este if si se esta buscando el reporte de estudiantes inactivos */

                $reporteModel->updateInactiveStudents($conn);

                $studentsInactivos = $reporteModel->getStudentsInactivos($conn); /* esta funcion 
                devuelve la cantidad de estudiantes inactivos para actualizar la cantidad en la pantalla */
            }

            if ($type == 'Cons') {
                $classesByStudent = $reporteModel->getClassesByStudent($conn);
            }

            if ($type != 'updateinactive') {
                $studentsInfo = $reporteModel->getStudentsInfo($conn, $type, $counselingTerm);
                // Data array for the second table
                $TableData = [];
                if (isset($studentsInfo)) {
                    foreach ($studentsInfo as $s) {
                        if (isset($classesByStudent)) {
                            $TableData[] = [$s['student_num'], $s['full_name'], implode(',', $classesByStudent[$s['student_num']])];
                        } 
                        else if ($type == 'incomplete') {
                            $TableData[] = [$s['student_num'], $s['full_name'], $s['crse_code'], $s['crse_grade']];
                        }
                        else {
                            $TableData[] = [$s['student_num'], $s['full_name']];
                        }
                    }
                }

                $combinedCsvContent = "Num de estudiante,Nombre\n";
                    foreach ($TableData as $row) {
                        $combinedCsvContent .= implode(',', $row) . "\n";
                    }

                $_SESSION['csv_content'] = $combinedCsvContent;
                if($type == 'consCCOM')
                    $combinedCsvFileName = "Report_Aconsejados_".$counselingTerm.".csv";
                else if($type == 'consSinCCOM')
                    $combinedCsvFileName = "Report_Aconsejados_Sin_CCOM_".$counselingTerm.".csv";
                else if($type == 'noCons')
                    $combinedCsvFileName = "Report_No_Han_Realizado_Consejeria_".$counselingTerm.".csv";
                else if($type == 'Cons')
                    $combinedCsvFileName = "Report_Realizaron_Consejeria_".$counselingTerm.".csv";
                else if($type == 'active')
                    $combinedCsvFileName = "Report_Estudiantes_Activos_".$counselingTerm.".csv";
                else if($type == 'openinactive')
                    $combinedCsvFileName = "Report_Estudiantes_Inactivos_".$counselingTerm.".csv";
                else if($type == 'incomplete')
                    $combinedCsvFileName = "Report_Notas_Incompletas.csv";
                else
                    $combinedCsvFileName = "Report_Estudiantes_Apuntados_".$type."-".$counselingTerm.".csv";
                $_SESSION['filename'] = $combinedCsvFileName;
            }

        }

        require_once(__DIR__ . '/../views/reporteView.php');

    }
}

$reporteController = new ReporteController();
$reporteController->index();

?>