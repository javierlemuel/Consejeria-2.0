<?php
if (!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true) {
    header("Location: ../index.php");
    exit;
}
// controllers/expedientesController.php
require_once(__DIR__ . '/../models/StudentModel.php');
//JAVIER
require_once(__DIR__ . '/../models/MinorModel.php');
//
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/CohorteModel.php');
require_once(__DIR__ . '/../global_classes/utils.php');
class ExpedientesController
{
    public function index()
    {
        global $conn;
        $studentModel = new StudentModel();
        //JAVIER
        $minorModel = new MinorModel();
        #$error_log = "";
        $_SESSION['registermodeltxt'] = "";
        //

        // Search query (q) and pagination (p)
        $q = $_GET["q"] ?? null;
        $q = sanitizeSearch($q);
        $p = $_GET["p"] ?? 1;

        // Students filter
        $statusFilter = $_GET['status'] ?? NULL;
        $didCounseling = $_GET['did_counseling'] ?? NULL;

        if (isset($_GET['autorecommend'])) {
            // Entrar función de auto-recomendación de cursos en oferta a todos los estudiantes
            $date = date("Y-m-d");
            $studentModel->generateAutoReports($conn, $date);
        }

        if (isset($_GET['deleteAllRecommend'])) {
            // Entrar función de borrar todas las recomendaciones
            $studentModel->deleteAllRecommendations($conn);
        }

        if (isset($_POST['deleteAllRecommendationsforOneStudent']) && $_POST['student_num']) {
            // Entrar función de borrar todas las recomendaciones
            $studentModel->deleteAllRecommendationsOnOneStudent($conn, $_POST['student_num']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = date("Y-m-d");
            $action = isset($_POST['action']) ? $_POST['action'] : '';
            if ($action === 'addStudent') {
                // Obtén y procesa los datos para agregar un estudiante
                $nombre = $_POST['nombre'];
                $nombre2 = $_POST['nombre2'];
                $apellidoP = $_POST['apellidoP'];
                $apellidoM = $_POST['apellidoM'];
                $email = $_POST['email'];
                $minor = $_POST['minor'];
                $numero = $_POST['numero_estu'];
                $cohorte = $_POST['cohorte'];
                $estatus = $_POST['estatus'];
                $birthday = $_POST['birthday'];

                // Llama al modelo para insertar el estudiante en la base de datos
                $success = $studentModel->insertStudent($conn, $nombre, $nombre2, $apellidoP, $apellidoM, $email, $minor, $numero, $cohorte, $estatus, $birthday);
                if ($success == TRUE) {
                    $mensaje = "studentAdded";
                } else {
                    $mensaje = "studentNotAdded";
                }
            } elseif ($action === 'selecteStudent') {
                require_once(__DIR__ . '/../models/ClassesModel.php');
                $classesModel = new ClassesModel();

                $student_num = $_POST['student_num'];
                $studentData = $studentModel->selectStudent($student_num, $conn);
                $minors = $minorModel->getMinors($conn);
                $cohorts = $classesModel->getCohorts($conn);
                require_once(__DIR__ . '/../views/editStudentView.php');
                return;
            } elseif ($action === 'editStudent') {
                require_once(__DIR__ . '/../models/ClassesModel.php');
                $classesModel = new ClassesModel();

                $nombre = $_POST['nombre'];
                $nombre2 = $_POST['nombre2'];
                $apellidoP = $_POST['apellidoP'];
                $apellidoM = $_POST['apellidoM'];
                $email = $_POST['email'];
                $old_email = $_POST['old_email'];
                $numeroEst = $_POST['numeroEstu'];
                $fechaNac = $_POST['fechaNac'];
                $cohorte = $_POST['cohorte'];
                $minor = $_POST['minor'];
                $graduacion = $_POST['graduacion'];
                $notaAdmin = $_POST['notaAdmin'];
                $notaEstudiante = $_POST['notaEstudiante'];
                $status = $_POST['estatus'];
                $tipo = $_POST['tipo'];
                //JAVIER
                $date = date("Y-m-d");
                $result = $studentModel->editStudent($nombre, $nombre2, $apellidoP, $apellidoM, $old_email, $email, $numeroEst, $fechaNac, $cohorte, $minor, $graduacion, $notaAdmin, $notaEstudiante, $status, $tipo, $date, $conn);
                $minors = $minorModel->getMinors($conn);
                //
                $studentData = $studentModel->selectStudent($numeroEst, $conn);
                $cohorts = $classesModel->getCohorts($conn);
                require_once(__DIR__ . '/../views/editStudentView.php');
                return;
            } elseif ($action === 'studentCounseling') {
                require_once(__DIR__ . '/../models/ClassesModel.php');
                $classesModel = new ClassesModel();
                require_once(__DIR__ . '/../models/ClassModel.php');
                $classModel = new ClassModel();
                $archivoRegistro = __DIR__ . '/archivo_de_registro.txt';

                // info del estudiatne
                $student_num = $_POST['student_num'];
                $studentData = $studentModel->selectStudent($student_num, $conn);
                $studentCohort = $studentData['cohort_year'];
                $studentRecommendedTerms = $studentModel->studentRecommendedTerms($student_num, $conn);

                if (isset($_POST['selectedTerm']) && !empty($_POST['selectedTerm'])) {
                    $selectedTerm = $_POST['selectedTerm']; // term seleccionado en el select de counseling view
                    $studentRecommendedClasses = $studentModel->studentRecommendedClasses($student_num, $selectedTerm, $conn); // clases recomendadas en ese term
                    $studentWillTakeClasses = $studentModel->getClassesStudentWillTake($student_num, $selectedTerm, $conn); // clases que el estudiante escogio en la consejeria
                } else {
                    $studentRecommendedClasses = NULL;
                }
                if (isset($_POST['deleteRecomendation']) && !empty($_POST['deleteRecomendation'])) {
                    $selectedTerm = $_POST['selectedTerm']; // term seleccionado en el select de counseling view
                    $crse_code = $_POST['crse_code'];
                    $deleteResult = $studentModel->deleteRecomendation($student_num, $crse_code, $selectedTerm, $conn); // clases recomendadas en ese term
                    $studentRecommendedClasses = $studentModel->studentRecommendedClasses($student_num, $selectedTerm, $conn);
                }
                if (isset($_POST['makecounseling']) && !empty($_POST['makecounseling'])) {
                    if (isset($_POST['updateGrade']))
                        unset($_POST['updateGrade']);

                    $currentDateTime = date("Y-m-d H:i:s");
                    $logMessage = "\n" . $currentDateTime . "\n";
                    #error_log($logMessage, 3, $archivoRegistro);
                    $_SESSION['registermodeltxt'] .= $logMessage;

                    $term = $classesModel->getTerm($conn);

                    if (isset($_POST['seleccion']) && is_array($_POST['seleccion'])) {
                        // Obtiene los valores de los checkboxes seleccionados
                        $selectedClasses = $_POST['seleccion'];

                        foreach ($selectedClasses as $class) {
                            $result = $studentModel->alreadyRecomended($student_num, $class, $term, $conn);

                            if ($result == TRUE) {
                                #error_log("La clase $class ya estaba recomendada para este semestre. \n", 3, $archivoRegistro);
                                $_SESSION['registermodeltxt'] .= "La clase $class ya estaba recomendada para este semestre. \n";
                            } else {
                                $results = $studentModel->insertRecomendation($student_num, $class, $term, $conn);
                                if ($results == TRUE) {
                                    #error_log("La clase $class se anadio a recommended courses. \n", 3, $archivoRegistro);
                                    $_SESSION['registermodeltxt'] .= "La clase $class se añadió a 'Recommended Courses'. \n";
                                    if (sizeof($selectedClasses) > 1)
                                        $_SESSION['consejeria_msg'] = "Clases fueron añadidas a recomendación!!";
                                } else {
                                    #error_log("Hubo un error insertando la clase. \n", 3, $archivoRegistro);
                                    $_SESSION['registermodeltxt'] .= "Hubo un error insertando la clase. \n";
                                }
                            }
                        }
                    } else {
                        // No se seleccionaron clases
                        #error_log("No se seleccionaron clases \n", 3, $archivoRegistro);
                        $_SESSION['registermodeltxt'] .= "No se seleccionaron clases \n";
                    }
                }
                if (isset($_POST['updateGrade']) && !empty($_POST['updateGrade'])) {
                    $currentDateTime = date("Y-m-d H:i:s");
                    $logMessage = "\n" . $currentDateTime . "\n";
                    #error_log($logMessage, 3, $archivoRegistro);
                    $_SESSION['registermodeltxt'] .= $logMessage;

                    $course_code = $_POST['crse_code'];
                    $credits = $_POST['credits'];
                    $department = substr($course_code, 0, 4);
                    $category = $_POST['category'];
                    if ($category == "") {
                        $category = $_POST['old_category'];
                    }
                    if (isset($_POST['level']))
                        $level = $_POST['level'];
                    else
                        $level = 'NULL';
                    $grade = $_POST['grade'];
                    $equi = $_POST['equivalencia'];
                    $conva = $_POST['convalidacion'];
                    $term = $_POST['term'];
                    $old_term = $_POST['old_term'];

                    if ($department == "CCOM") {
                        if (in_array($grade, ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                            $status = "NP";
                        } else {
                            $status = "P";
                        }
                    } else {
                        if (in_array($grade, ['F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                            $status = "NP";
                        } else {
                            $status = "P";
                        }
                    }


                    $result = $studentModel->studentAlreadyHasGrade($student_num, $course_code, $conn);

                    if ($grade == "") {
                        $studentModel->deleteStudentGrade($student_num, $course_code, $term, $conn);
                    }

                    if ($result == TRUE) {
                        $studentModel->UpdateStudentGradeManual($student_num, $course_code, $grade, $equi, $conva, $credits, $term, $category, $level, $old_term, $status, $conn);
                    } else {
                        $studentModel->InsertStudentGrade($student_num, $course_code, $grade, $equi, $conva, $credits, $term, $category, $status, $conn);
                    }
                }
                if (isset($_POST['insertGrade']) && !empty($_POST['insertGrade'])) {
                    $crse_code = $_POST['crse_code'];
                    $crse_code = strtoupper($crse_code);
                    $term = $_POST['term'];
                    if ($term == '') {
                        $term = $classesModel->getTerm($conn);
                    }
                    $studentAlreadyHasGradeInTerm = $studentModel->alreadyHasGradeInTerm($student_num, $crse_code, $term, $conn); # revisa si ya el estudiante a tiene nota en esta clase y semestre
                    if ($studentAlreadyHasGradeInTerm == TRUE) # el estudiante ya tiene una nota en esa clase y semestre.
                    {
                        #error_log("El estudiante $student_num ya tiene una calificacion en el curso $crse_code en el term $term. No se actualizo nada.\n", 3, $archivoRegistro);
                        $_SESSION['registermodeltxt'] .= "El estudiante $student_num ya tiene una calificacion en el curso $crse_code en el term $term. No se actualizo nada.\n";
                    } else # el estudiante no tiene una nota en esa clase y semestre.
                    {
                        $credits = $_POST['credits'];
                        $category = $_POST['category'];
                        if ($category == '') {
                            $category = "free";
                        }
                        $grade = $_POST['grade'];
                        $status = $_POST['status'];
                        $department = substr($crse_code, 0, 4);
                        $equivalencia = $_POST['equivalencia'];
                        $convalidacion = $_POST['convalidacion'];

                        if ($status == '') {
                            if ($department == "CCOM") {
                                if (in_array($grade, ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                    $status = "NP";
                                } else {
                                    $status = "P";
                                }
                            } else {
                                if (in_array($grade, ['F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                    $status = "NP";
                                } else {
                                    $status = "P";
                                }
                            }
                        }

                        $course_info = $classModel->selectCourseWNull($conn, $crse_code);
                        if ($course_info == NULL) {
                            if ($credits == '' or $category == '') {
                                #error_log("La clase " . $crse_code . "no está en la base de datos, tienes que proveer los creditos y el tipo de clase. \n", 3, $archivoRegistro);
                                $_SESSION['registermodeltxt'] .= "La clase " . $crse_code . "no está en la base de datos, tienes que proveer los creditos y el tipo de clase. \n";
                            } else {
                                $studentModel->InsertStudentGrade($student_num, $crse_code, $grade, $equivalencia, $convalidacion, $credits, $term, $category, $status, $conn);
                            }
                        } else {
                            $credits = $course_info['credits'];
                            $studentModel->InsertStudentGrade($student_num, $crse_code, $grade, $equivalencia, $convalidacion, $credits, $term, $category, $status, $conn);
                        }
                    }
                }

                $studentHaveMinor = $studentModel->studentHaveMinor($student_num, $conn);

                // variables para las notas
                $currentlyTaking = $classesModel->getCurrentlyTakingClasses($conn, $student_num);
                $ccomByCohort = $classesModel->getCohortCoursesWgradesCCOM($conn, $studentCohort, $student_num);
                $ccomFreeByNotCohort = $classesModel->getCohortCoursesWgradesCCOMfree($conn, $studentCohort, $student_num);
                $notccomByCohort = $classesModel->getCohortCoursesWgradesNotCCOM($conn, $studentCohort, $student_num);
                $notccomByNotCohort = $classesModel->getCohortCoursesWgradesNotCCOMfree($conn, $studentCohort, $student_num);
                $otherClasses = $classesModel->getAllOtherCoursesWgrades($conn, $student_num);

                // variables para las recomendaciones
                $mandatoryClasses = $classesModel->getCcomCourses($conn);
                $electiveClasses = $classesModel->getCcomElectives($conn);
                $dummyClasses = $classesModel->getDummyCourses($conn);
                $generalClasses = $classesModel->getGeneralCourses($conn);

                require_once(__DIR__ . '/../views/counselingView.php');
                return;
            } elseif ($action === 'openCounseling') {
                require_once(__DIR__ . '/../models/ClassesModel.php');
                $classesModel = new ClassesModel();
                require_once(__DIR__ . '/../models/ClassModel.php');
                $classModel = new ClassModel();
                $archivoRegistro = __DIR__ . '/archivo_de_registro.txt';

                // info del estudiatne
                $student_num = $_POST['student_num'];
                $studentData = $studentModel->selectStudent($student_num, $conn);
                $studentCohort = $studentData['cohort_year'];
                $studentRecommendedTerms = $studentModel->studentRecommendedTerms($student_num, $conn);

                if (isset($_POST['selectedTerm']) && !empty($_POST['selectedTerm'])) {
                    $selectedTerm = $_POST['selectedTerm']; // term seleccionado en el select de counseling view
                    $studentRecommendedClasses = $studentModel->studentRecommendedClasses($student_num, $selectedTerm, $conn); // clases recomendadas en ese term
                } else {
                    $studentRecommendedClasses = NULL;
                }
                $_SESSION['consejeria_msg'] = 'Consejería re-abierta para el estudiante!';
                $studentModel->openCounseling($student_num, $conn);
                $studentHaveMinor = $studentModel->studentHaveMinor($student_num, $conn);

                // variables para las notas
                $currentlyTaking = $classesModel->getCurrentlyTakingClasses($conn, $student_num);
                $ccomByCohort = $classesModel->getCohortCoursesWgradesCCOM($conn, $studentCohort, $student_num);
                $ccomFreeByNotCohort = $classesModel->getCohortCoursesWgradesCCOMfree($conn, $studentCohort, $student_num);
                $notccomByCohort = $classesModel->getCohortCoursesWgradesNotCCOM($conn, $studentCohort, $student_num);
                $notccomByNotCohort = $classesModel->getCohortCoursesWgradesNotCCOMfree($conn, $studentCohort, $student_num);
                $otherClasses = $classesModel->getAllOtherCoursesWgrades($conn, $student_num);

                // variables para las recomendaciones
                $mandatoryClasses = $classesModel->getCcomCourses($conn);
                $electiveClasses = $classesModel->getCcomElectives($conn);
                $dummyClasses = $classesModel->getDummyCourses($conn);
                $generalClasses = $classesModel->getGeneralCourses($conn);

                require_once(__DIR__ . '/../views/counselingView.php');
                return;
            } elseif ($action === 'blockCounseling') {
                require_once(__DIR__ . '/../models/ClassesModel.php');
                $classesModel = new ClassesModel();
                require_once(__DIR__ . '/../models/ClassModel.php');
                $classModel = new ClassModel();
                $archivoRegistro = __DIR__ . '/archivo_de_registro.txt';

                // info del estudiatne
                $student_num = $_POST['student_num'];
                $studentData = $studentModel->selectStudent($student_num, $conn);
                $studentCohort = $studentData['cohort_year'];
                $studentRecommendedTerms = $studentModel->studentRecommendedTerms($student_num, $conn);

                if (isset($_POST['selectedTerm']) && !empty($_POST['selectedTerm'])) {
                    $selectedTerm = $_POST['selectedTerm']; // term seleccionado en el select de counseling view
                    $studentRecommendedClasses = $studentModel->studentRecommendedClasses($student_num, $selectedTerm, $conn); // clases recomendadas en ese term
                } else {
                    $studentRecommendedClasses = NULL;
                }
                $lockStatus = '';

                if (isset($_POST['block'])) {
                    $_SESSION['consejeria_msg'] = 'Consejería fue bloqueada para el estudiante!';
                    $lockStatus = 1;
                }

                if (isset($_POST['unblock'])) {
                    $_SESSION['consejeria_msg'] = 'Consejería fue desbloqueada para el estudiante!';
                    $lockStatus = 0;
                }

                $studentModel->changeCounselingLock($student_num, $conn, $lockStatus);
                $studentHaveMinor = $studentModel->studentHaveMinor($student_num, $conn);

                $studentData = $studentModel->selectStudent($student_num, $conn);

                // variables para las notas
                $currentlyTaking = $classesModel->getCurrentlyTakingClasses($conn, $student_num);
                $ccomByCohort = $classesModel->getCohortCoursesWgradesCCOM($conn, $studentCohort, $student_num);
                $ccomFreeByNotCohort = $classesModel->getCohortCoursesWgradesCCOMfree($conn, $studentCohort, $student_num);
                $notccomByCohort = $classesModel->getCohortCoursesWgradesNotCCOM($conn, $studentCohort, $student_num);
                $notccomByNotCohort = $classesModel->getCohortCoursesWgradesNotCCOMfree($conn, $studentCohort, $student_num);
                $otherClasses = $classesModel->getAllOtherCoursesWgrades($conn, $student_num);

                // variables para las recomendaciones
                $mandatoryClasses = $classesModel->getCcomCourses($conn);
                $electiveClasses = $classesModel->getCcomElectives($conn);
                $dummyClasses = $classesModel->getDummyCourses($conn);
                $generalClasses = $classesModel->getGeneralCourses($conn);

                require_once(__DIR__ . '/../views/counselingView.php');
                return;
            } elseif ($action === 'uploadCSV') {
                $archivoRegistro = __DIR__ . '/archivo_de_registro.txt';

                $currentDateTime = date("Y-m-d H:i:s");
                $logMessage = "\n" . $currentDateTime . "\n";
                #error_log($logMessage, 3, $archivoRegistro);
                $_SESSION['registermodeltxt'] .= $logMessage;

                // Verificamos si se han subido archivos
                if (!empty($_FILES['files']['name']) && !empty($_FILES['files2']['name'])) {
                    $file_tmp = $_FILES['files']['tmp_name'];
                    $file_tmp2 = $_FILES['files2']['tmp_name'];

                    // Validamos que el primer archivo sea de tipo texto
                    if ($_FILES['files']['type'] == "text/plain") {
                        // Leemos el contenido del primer archivo CSV
                        $file_content = file_get_contents($file_tmp);

                        // Dividimos el contenido por líneas
                        $lines = explode("\n", $file_content);

                        // Leemos el contenido del segundo archivo CSV
                        $file_content2 = file_get_contents($file_tmp2);
                        $lines2 = preg_split('/\r\n|\r|\n/', $file_content2);
                        $birthdays = [];

                        foreach ($lines2 as $line2) {

                            if (trim($line2) == '') {
                                continue; // Saltar a la siguiente iteración si la línea está en blanco
                            }

                            $data2 = explode(",", $line2);

                            if ($data2 == 'ID') {
                                continue;
                            }

                            // Trim each data element to remove leading/trailing spaces
                            $data2 = array_map('trim', $data2);

                            $student_num2 = trim($data2[0]);
                            $birthday = trim($data2[count($data2) - 2]); // Asumiendo que la fecha de nacimiento está en el penúltimo índice

                            // Almacenar la fecha de nacimiento asociada al número de estudiante
                            $birthdays[$student_num2] = $birthday;
                            //echo "{{Birthday for $student_num2: $birthdays[$student_num2]}}\n";
                        }

                        foreach ($lines as $line) {

                            if (trim($line) == '') {
                                continue; // Saltar a la siguiente iteración si la línea está en blanco
                            }

                            // Dividimos cada línea por el delimitador ";"
                            $data = explode(";", $line);

                            // Aplicamos trim a cada parte para eliminar espacios en blanco
                            $student_num = trim($data[0]);

                            // Obtenemos el apellido paterno y materno
                            $apellidos_nombres = explode(",", trim($data[1]));
                            $apellidos = $apellidos_nombres[0];

                            // Verificamos si hay un segundo apellido (materno)
                            $apellido_paterno = $apellido_materno = "";

                            if (strpos($apellidos, ' ') !== false) {
                                list($apellido_paterno, $apellido_materno) = explode(' ', $apellidos, 2);
                            } else {
                                $apellido_paterno = $apellidos;
                            }

                            // Obtenemos el nombre y segundo nombre
                            $nombres = explode(" ", trim($apellidos_nombres[1]));
                            $nombre = isset($nombres[0]) ? trim($nombres[0]) : "";
                            $segundo_nombre = isset($nombres[1]) ? trim($nombres[1]) : "";

                            $email = trim($data[12]);

                            // Obtener la fecha de nacimiento del array $birthdays si está disponible
                            // echo "{{Student $student_num has $birthdays[$student_num]}}\n";
                            $birthdate = isset($birthdays[$student_num]) ? $birthdays[$student_num] : '';
                            //echo $birthdate;

                            //hacer que los nombre comienzen con letra mayuscula y el resto sea minusculas.
                            $nombre = ucwords(strtolower($nombre));
                            $segundo_nombre = ucwords(strtolower($segundo_nombre));
                            $apellido_paterno = ucwords(strtolower($apellido_paterno));
                            $apellido_materno = ucwords(strtolower($apellido_materno));
                            $email = strtolower($email) . "@upr.edu";

                            if ($birthdate != '') {
                                $student = $studentModel->selectStudent($student_num, $conn);
                                if ($student != NULL) {
                                    #error_log("El estudiante: " . $student_num . " ya existia en la base de datos. \n", 3, $archivoRegistro);
                                    $_SESSION['registermodeltxt'] .= "El estudiante: " . $student_num . " ya existia en la base de datos. \n";
                                } else {
                                    // Llamamos a la función del modelo para insertar el estudiante
                                    $studentModel->insertStudentCSV($conn, $student_num, $nombre, $segundo_nombre, $apellido_materno, $apellido_paterno, $email, $birthdate);
                                    $archivoRegistroModel = __DIR__ . '/../models/archivo_de_registro.txt';
                                    $date = date("Y-m-d");
                                    $studentModel->updateEditDate($conn, $student_num, $date);
                                }
                            } else {
                                #error_log("El estudiante: " . $student_num . " no tiene fecha de nacimiento \n", 3, $archivoRegistro);
                                $_SESSION['registermodeltxt'] .= "El estudiante: " . $student_num . " no tiene fecha de nacimiento \n";
                            }
                        }
                        //exito
                        $result = "Archivos CSV procesados correctamente.";
                        #error_log("Archivos procesados correctamente \n", 3, $archivoRegistro);
                        $_SESSION['registermodeltxt'] .= "Archivos procesados correctamente \n";
                    } else {
                        // el archivo no es .txt
                        $result = "Error: El archivo debe ser de tipo texto (.txt).";
                        #error_log("El archivo debe ser tipo texto \n", 3, $archivoRegistro);
                        $_SESSION['registermodeltxt'] .= "El archivo debe ser tipo texto \n";
                    }
                } else {
                    // no se mando ningun arhivo o solo 1
                    $result = "Error: No se ha seleccionado ningún archivo.";
                    #error_log("No se a seleccionado ningun archivo \n", 3, $archivoRegistro);
                    $_SESSION['registermodeltxt'] .= "No se a seleccionado ningun archivo \n";
                }
            } elseif ($action === 'updateGradeCSV') {
                require_once(__DIR__ . '/../models/ClassesModel.php');
                $classesModel = new ClassesModel();
                require_once(__DIR__ . '/../models/ClassModel.php');
                $classModel = new ClassModel();
                $archivoRegistro = __DIR__ . '/archivo_de_registro.txt';

                $currentDateTime = date("Y-m-d H:i:s");
                $logMessage = "\n" . $currentDateTime . "\n";
                #error_log($logMessage, 3, $archivoRegistro);
                $_SESSION['registermodeltxt'] .= $logMessage;

                // Verificamos si se han subido archivos
                if (!empty($_FILES['files']['name'])) {
                    // Obtén el archivo temporal subido
                    $tmpName = $_FILES['files']['tmp_name'];

                    // Abre el archivo para leer
                    $file = fopen($tmpName, 'r');

                    // Itera sobre cada línea del archivo
                    while (($line = fgetcsv($file)) !== FALSE) {
                        // Asigna cada dato a una variable
                        $term = $line[0];
                        if (strlen($term) > 3)
                            $term = trim($term); # no se por que esta pone un espacio en blanco en el comienzo del archivo. ya con trim lo quitamos
                        $term = trim($term, "\xEF\xBB\xBF");
                        $studentNumber = $line[1];
                        //le quita los guiones al numero de estudiantes.
                        $studentNumber = str_replace("-", "", $studentNumber);
                        $class = $line[2];
                        //Se toman solo los primeros 8 caracteres de la clase ya que el archivo incluye las secciones y no nos interesa esa informacion
                        $class = substr($class, 0, 8);
                        $department = substr($class, 0, 4);
                        $creditAmount = $line[3];
                        $grade = $line[5];
                        $grade = trim($grade); # para eliminar los espacios en blanco si no hay nota. deja el string en ""

                        $studentData = $studentModel->selectStudent($studentNumber, $conn);
                        // El estudiante no existe en la base de datos.
                        if ($studentData == NULL) {
                            #error_log("El estudiante: " . $studentNumber . " no existe en la base de datos.\n", 3, $archivoRegistro);
                            $_SESSION['registermodeltxt'] .= "El estudiante: " . $studentNumber . " no existe en la base de datos.\n";
                        } else {
                            if ($creditAmount != 0) # clases de 0 creditos no se ponen en las notas
                            {
                                $equi = "";
                                $conva = "";
                                $course_info = $classModel->selectCourseWNull($conn, $class);
                                if ($course_info == NULL) {
                                    $type = 'free';
                                } else #AQUI
                                {
                                    if (isset($course_info['required'])) {
                                        if ($course_info['required'] == 1)
                                            $type = 'general';
                                        else
                                            $type = 'free';
                                    } elseif ($course_info['type'] != 'mandatory' && $course_info['type'] != 'elective') {
                                        if ($course_info['type'] == 'FREE') {
                                            $type = 'free';
                                        } else
                                            $type = 'general';
                                    } else {
                                        $type = $course_info['type'];
                                    }
                                }

                                #echo "{$course_info['crse_code']} has type: {$type}\n";

                                if ($grade == "") {
                                    $status = "M";
                                } elseif ($department == "CCOM") {
                                    if (in_array($grade, ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                        $status = "NP";
                                    } else {
                                        $status = "P";
                                    }
                                } else {
                                    if (in_array($grade, ['F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                        $status = "NP";
                                    } else {
                                        $status = "P";
                                    }
                                }

                                if (!in_array($class, ['CCOM3135', 'CCOM3985', 'INTD4995']))
                                    $result = $studentModel->studentAlreadyHasGrade($studentNumber, $class, $conn);
                                else
                                    $result = $studentModel->studentAlreadyHasGradeWithSemester($studentNumber, $class, $term, $conn); //el estudiante ya tiene una nota en esa clase y en ese semestre

                                if ($result == TRUE) {
                                    $result = $studentModel->UpdateStudentGradeCSV($studentNumber, $class, $grade, $equi, $conva, $creditAmount, $term, $type, $term, $status, $conn);
                                    $date = date("Y-m-d");
                                    $studentModel->updateEditDate($conn, $studentNumber, $date);
                                } else // el estudiante no tiene una nota en esa clase.
                                {
                                    $result = $studentModel->InsertStudentGrade($studentNumber, $class, $grade, $equi, $conva, $creditAmount, $term, $type, $status, $conn);
                                    $date = date("Y-m-d");
                                    $studentModel->updateEditDate($conn, $studentNumber, $date);
                                }
                            }
                        }
                    }

                    // Cierra el archivo
                    fclose($file);
                }
            }
        }




        // Obtenemos la lista de estudiantes según el filtro y la búsqueda
        $students = $studentModel->getStudents($conn, $p, $statusFilter, $q, $didCounseling);
        $amountOfPages = $studentModel->getPageAmount();

        //JAVIER (Add minors)
        $minors = $minorModel->getMinors($conn);
        //


        // if(isset($archivoRegistro))
        // {
        //     $fileContent = file_get_contents($archivoRegistro);
        //     $_SESSION['registertxt'] = $fileContent;
        // }

        // if($error_log != '')
        // {
        //     $_SESSION['registertxt'] = $error_log;
        // }


        // if(isset($archivoRegistroModel))
        // {
        //     $fileContentModel = file_get_contents($archivoRegistroModel);

        //     if($fileContentModel != "")
        //         $_SESSION['registermodeltxt'] = $fileContentModel;
        // }

        $cohorteModel = new CohorteModel();
        $cohortes = $cohorteModel->getCohorteYears($conn);
        require_once(__DIR__ . '/../views/expedientesView.php');
    }
}

$expedientesController = new ExpedientesController();
$expedientesController->index();
