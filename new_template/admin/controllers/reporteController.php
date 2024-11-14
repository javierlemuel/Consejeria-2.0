<?php
if(!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true)
{
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/ReporteModel.php');
require_once(__DIR__ . '/../config/database.php');

class ReporteController{
    public function index() {   
        global $conn;
        $reporteModel = new ReporteModel();

        $studentsAconsejados = $reporteModel->getStudentsAconsejados($conn);
        $studentsSinCCOM = $reporteModel->getStudentsSinCCOM($conn);
        $studentsNoConsejeria = $reporteModel->getStudentsNoConsejeria($conn);
        $studentsActivos = $reporteModel->getStudentsActivos($conn);
        $studentsInactivos = $reporteModel->getStudentsInactivos($conn);
        $studentsRegistrados = $reporteModel->getRegistrados($conn);
        $studentsEditados = $reporteModel->getEditados($conn);
        $studentsPerClass = $reporteModel->getStudentsPerClass($conn);
        $term = $reporteModel->getTerm($conn);
        $count = 0;

        if(isset($_GET['code']))
        {
            $type = $_GET['code'];

            if ($type == 'inactive') {
                /* el programa solo entra a este if si se esta buscando el reporte de estudiantes inactivos */

                $reporteModel->updateInactiveStudents($conn);

                $studentsInactivos = $reporteModel->getStudentsInactivos($conn); /* esta funcion 
                devuelve la cantidad de estudiantes inactivos para actualizar la cantidad en la pantalla */
            }

            if ($type == 'Cons') {
                $classesByStudent = $reporteModel->getClassesByStudent($conn);
            }

            $studentsInfo = $reporteModel->getStudentsInfo($conn, $type);
            // Data array for the second table
            $TableData = [];
            if (isset($studentsInfo)) {
                foreach ($studentsInfo as $s) {
                    if (isset($classesByStudent)) {
                        $TableData[] = [$s['student_num'], $s['full_name'], implode(',', $classesByStudent[$s['student_num']])];
                    } else {
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
                $combinedCsvFileName = "Report_Aconsejados_".$term.".csv";
            else if($type == 'consSinCCOM')
                $combinedCsvFileName = "Report_Aconsejados_Sin_CCOM_".$term.".csv";
            else if($type == 'noCons')
                $combinedCsvFileName = "Report_No_Han_Realizado_Consejeria_".$term.".csv";
            else if($type == 'Cons')
                $combinedCsvFileName = "Report_Realizaron_Consejeria_".$term.".csv";
            else if($type == 'active')
                $combinedCsvFileName = "Report_Estudiantes_Activos_".$term.".csv";
            else if($type == 'inactive')
                $combinedCsvFileName = "Report_Estudiantes_Inactivos_".$term.".csv";
            else
                $combinedCsvFileName = "Report_Estudiantes_Apuntados_".$type."-".$term.".csv";
            $_SESSION['filename'] = $combinedCsvFileName;

        }

        require_once(__DIR__ . '/../views/reporteView.php');

    }
}

$reporteController = new ReporteController();
$reporteController->index();

?>