<?php
// models/StudentModel.php
class StudentModel
{
    public $pagination_limit = 20;
    public $amountOfRows = 1;

    public function calculateOffset($p_num)
    {
        $page = max(1, (int) ($p_num ?? 1));  // Ensure the page number is at least 1
        return $this->pagination_limit * ($page - 1);
    }

    public function getPageAmount()
    {
        return ceil($this->amountOfRows / $this->pagination_limit);
    }

    public function getCohorts($conn)
    {
        $sql = "SELECT DISTINCT cohort_year FROM cohort ORDER BY cohort_year;";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getStudents(mysqli $conn, $p = null, $status = null, $q = null, $conducted_counseling = null)
    {
        // Pagination settings
        if (!isset($p)) {
            $p_limit = PHP_INT_MAX;
            $offset = 0;
        } else {
            $p_limit = $this->pagination_limit ?? PHP_INT_MAX; // Use pagination limit or max value
            $offset = $this->calculateOffset($p);
        }

        // search pattern
        $search = "%{$q}%";

        // Build the base SQL query for counting total rows
        $countSql = "SELECT COUNT(DISTINCT student_num) AS total_rows
                 FROM student
                 WHERE 1 = 1";

        $params = [];  // Parameters for bind_param
        $types = "";   // Parameter types

        // TODO: Check if all filters are taken care of

        // Conditionally add status filter to the count query
        if ($status === 'Activos') {
            $countSql .= " AND status = 'Activo'";
        } elseif ($status === 'Inactivos') {
            $countSql .= " AND status = 'Inactivo'";
        } elseif ($status === 'Graduados') {
            $countSql .= " AND status = 'Graduado'";
        } elseif ($status === 'Graduandos') {
            $countSql .= " AND status = 'Graduando'";
        }


        // Conditionally add search filter to the count query
        if (!empty($q)) {
            $countSql .= " AND (student_num LIKE ? OR name1 LIKE ? OR name2 LIKE ? OR last_name1 LIKE ? OR last_name2 LIKE ?)";
            $types .= "sssss";
            $params = array_fill(0, 5, $search);
        }

        if (ctype_digit($conducted_counseling)) {
            $countSql .= " AND conducted_counseling = ?";
            $params[] = $conducted_counseling;
            $types .= "i";
        }
        // Prepare and bind parameters for the count query
        $countStmt = $conn->prepare($countSql);
        if (!empty($types)) {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $row = $countResult->fetch_assoc();
        $this->amountOfRows = $row['total_rows']; // Total rows 
        $countStmt->close();

        // Build the main query for fetching paginated results
        $sql = "SELECT DISTINCT student_num, name1, name2, last_name1, last_name2, conducted_counseling, status, edited_date
            FROM student
            WHERE 1 = 1";

        // Reuse the same filters in the main query
        if ($status === 'Activos') {
            $sql .= " AND status = 'Activo'";
        } elseif ($status === 'Inactivos') {
            $sql .= " AND status = 'Inactivo'";
        } elseif ($status === 'Graduados') {
            $sql .= " AND status = 'Graduado'";
        } elseif ($status === 'Graduandos') {
            $sql .= " AND status = 'Graduando'";
        }

        if (!empty($q)) {
            $sql .= " AND (student_num LIKE ? OR name1 LIKE ? OR name2 LIKE ? OR last_name1 LIKE ? OR last_name2 LIKE ?)";
        }

        if (ctype_digit($conducted_counseling)) {
            $sql .= " AND conducted_counseling = ?";
        }

        // Add sorting and pagination
        $sql .= " ORDER BY name1 ASC LIMIT ? OFFSET ?";
        $types .= "ii"; // Add integer types for limit and offset
        array_push($params, $p_limit, $offset);

        // Prepare and bind parameters for the main query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        // Process and return results
        $students = [];
        $term = $this->getTerm($conn); // Get the current term

        while ($row = $result->fetch_assoc()) {
            $student_num = $row['student_num'];
            $formatted_student_num = substr($student_num, 0, 3) . '-' . substr($student_num, 3, 2) . '-' . substr($student_num, 5);
            $row['formatted_student_num'] = $formatted_student_num;

            // Secondary query to check if counseling was given
            $sql2 = "SELECT DISTINCT student_num FROM recommended_courses WHERE student_num = ? AND term = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ss", $student_num, $term);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            $row['given_counseling'] = $result2->num_rows > 0 ? 1 : 0;

            // Close the secondary statement
            $stmt2->close();

            $students[] = $row;
        }

        // Close the primary statement
        $stmt->close();

        return $students;
    }

    public function insertStudent($conn, $nombre, $nombre2, $apellidoP, $apellidoM, $email, $minor, $numero, $cohorte, $estatus, $birthday)
    {
        // Preparar la consulta SQL

        $sql0 = "SELECT * FROM student WHERE email = ? or student_num = ?";
        $stmt =  $conn->prepare($sql0);
        $stmt->bind_param("si", $email, $numero);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $stmt->close();
            $_SESSION['students_list_msg'] = "No se pudo insertar el estudiante " . $numero . " por número de estudiante o email repetido";
            return FALSE;
        }

        $sql = "INSERT INTO student (name1, name2, last_name1, last_name2, email, minor, student_num, cohort_year, status, dob, edited_date, conducted_counseling, counseling_lock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);

        $edited = date("Y-m-d");

        $cc = 0;
        // Vincular los parámetros con los valores
        $stmt->bind_param("sssssssssssss", $nombre, $nombre2, $apellidoP, $apellidoM, $email, $minor, $numero, $cohorte, $estatus, $birthday, $edited, $cc, $cc);

        // Ejecutar la sentencia
        $result = $stmt->execute();
        $stmt->close();
        // Verificar si la inserción se realizó con éxito
        if ($result === true) {
            // Inserción exitosa
            $_SESSION['students_list_msg'] = "El estudiante " . $numero . " fue insertado!!";
            return TRUE;
        } else {
            // Error en la inserción
            $_SESSION['students_list_msg'] = "No se pudo insertar el estudiante " . $numero;
            return FALSE;
        }
    }

    public function selectStudent($student_num, $conn)
    {
        // Preparar la consulta SQL
        $sql = "SELECT * FROM student WHERE student_num = ?";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("s", $student_num);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        $stmt->close();
        // Verificar si se encontraron resultados
        if ($result->num_rows == 1) {
            while ($row = $result->fetch_assoc()) {
                $studentData = $row;
            }
        } else {
            return NULL;
        }
        // Cierra la sentencia
        //$stmt->close();
        //$conn->close();
        // Devuelve los datos del estudiante
        return $studentData;
    }

    public function studentRecommendedTerms($student_num, $conn)
    {
        // Preparar la consulta SQL
        $sql = "SELECT DISTINCT term FROM recommended_courses WHERE student_num = ?;";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("s", $student_num);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        $stmt->close();
        // Verificar si se encontraron resultados
        $terms = array(); // Inicializar un array para almacenar los términos
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $terms[] = $row['term']; // Agregar el término al array
            }
        } else {
            return NULL;
        }

        return $terms;
    }

    public function studentRecommendedClasses($student_num, $selectedTerm, $conn)
    {
        // Preparar la consulta SQL para obtener los cursos recomendados
        $sql = "
        SELECT
            COALESCE(courses.name, '') AS name,
            COALESCE(courses.credits, '') AS credits,
            recommended_courses.crse_code
        FROM
            recommended_courses
        LEFT JOIN
            (SELECT crse_code, name, credits FROM ccom_courses
             UNION
             SELECT crse_code, name, credits FROM general_courses
             UNION
             SELECT crse_code, name, credits FROM dummy_courses) AS courses
        ON
            recommended_courses.crse_code = courses.crse_code
        WHERE
            recommended_courses.student_num = ?
            AND recommended_courses.term = ?";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular los parámetros con los valores
        $stmt->bind_param("ss", $student_num, $selectedTerm);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        // Cerrar la sentencia
        $stmt->close();

        // Verificar si hay resultados
        if ($result->num_rows === 0) {
            return NULL; // Devolver NULL si no hay filas
        } else {
            // Obtener los resultados como un array asociativo
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data; // Devolver los resultados
        }
    }

    public function editStudent($nombre, $nombre2, $apellidoP, $apellidoM, $old_email, $email, $numeroEst, $fechaNac, $cohorte, $minor, $graduacion, $notaAdmin, $notaEstudiante, $status, $tipo, $date, $conn)
    {
        // Preparar la consulta SQL

        if ($email != $old_email) {
            $sql0 = "SELECT * FROM student WHERE email = ?";
            $stmt =  $conn->prepare($sql0);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $stmt->close();
                $_SESSION['student_edit_msg'] = "No se pudo editar el estudiante " . $numeroEst . " por email repetido";
                return;
            }
        }


        $sql = "UPDATE student 
                SET name1 = ?, 
                    name2 = ?, 
                    last_name1 = ?, 
                    last_name2 = ?, 
                    email = ?, 
                    dob = ?, 
                    cohort_year = ?, 
                    minor = ?, 
                    grad_term = ?, 
                    admin_note = ?, 
                    student_note = ?, 
                    status = ?, 
                    type = ?,
                    edited_date = ? 
                WHERE student_num = ?";

        // Preparar los datos para la consulta
        $params = array(
            $nombre,
            $nombre2,
            $apellidoP,
            $apellidoM,
            $email,
            $fechaNac,
            $cohorte,
            $minor,
            $graduacion,
            $notaAdmin,
            $notaEstudiante,
            $status,
            $tipo,
            $date,
            $numeroEst
        );

        // Tipos de datos para los parámetros
        $types = 'sssssssisssssss';

        // Ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        $result = $stmt->execute();

        // Cerrar la conexión
        $stmt->close();

        if ($result == true)
            $_SESSION['student_edit_msg'] = "El estudiante " . $numeroEst . " fue editado!!";
        else
            $_SESSION['student_edit_msg'] = "No se pudo editar el estudiante " . $numeroEst;

        // Devolver true si la consulta se ejecutó correctamente, o false en caso contrario
        return $result !== false;
    }


    public function insertStudentCSV($conn, $student_num, $nombre, $segundo_nombre, $apellido_materno, $apellido_paterno, $email, $birthdate)
    {
        $archivoRegistro = __DIR__ . '/archivo_de_registro.txt';

        $currentDateTime = date("Y-m-d H:i:s");
        $logMessage = "\n" . $currentDateTime . "\n";
        #error_log($logMessage, 3, $archivoRegistro);
        $_SESSION['registermodeltxt'] .= $logMessage;

        if (strlen($birthdate) == 5)
            $birthdate = '0' . $birthdate;

        // Extraer el mes, día y año de $birthdate
        $mes = substr($birthdate, 0, 2);
        $dia = substr($birthdate, 2, 2);
        $axo = substr($birthdate, 4, 2);

        if ($axo > 50) {
            $axo = $axo + 1900;
        } else {
            $axo = $axo + 2000;
        }
        $birthdate_formatted = sprintf("%04d-%02d-%02d", $axo, $mes, $dia);

        $numberStr = (string) $student_num;

        // Check if the number has at least 5 digits
        if (strlen($numberStr) >= 5) {
            // Extract the 4th and 5th digits
            $fourthDigit = $numberStr[3];
            $fifthDigit = $numberStr[4];

            // Concatenate the 4th and 5th digits into a single string
            $combinedDigits = $fourthDigit . $fifthDigit;
        }

        $cohorts = $this->getCohorts($conn);

        $cohort_year = 2017;


        //echo "Combined Digits: ".$combinedDigits."\n";
        foreach ($cohorts as $cohort) {
            $year = $cohort['cohort_year'][2] . $cohort['cohort_year'][3];
            //echo "YEAR END: ".$year."\n";
            if ($combinedDigits >= $year)
                $cohort_year = $cohort['cohort_year'];
        }

        //echo "COHORT YEAR: ".$cohort_year;

        // if(intval($combinedDigits) <= 21)
        //     $cohort_year = '2017';
        // else    
        //     $cohort_year = '2022';

        // Ejecuta el query de inserción
        $date = date("Y-m-d");
        $cc = 0;
        $query = "INSERT INTO student (student_num, email, name1, name2, last_name1, last_name2, dob, conducted_counseling, counseling_lock, minor, cohort_year, status, edited_date)
                  VALUES ('$student_num', '$email', '$nombre', '$segundo_nombre', '$apellido_paterno', '$apellido_materno', '$birthdate_formatted', $cc, $cc, $cc, $cohort_year, 'Activo', '$date')";

        // Ejecuta el query
        if ($conn->query($query) === TRUE) {
            // Insert exitoso
            #error_log("Estudiante insertado correctamente en la base de datos.\n", 3, $archivoRegistro);
            $_SESSION['registermodeltxt'] .= "Estudiante " . $student_num . " insertado correctamente en la base de datos.\n";
            $_SESSION['students_list_msg'] = "Estudiante(s) fue(ron) insertado(s)!!";
        } else {
            // querie fallo
            #error_log("Error al insertar estudiante en la base de datos: " . $conn->error . "\n", 3, $archivoRegistro);
            $_SESSION['registermodeltxt'] .= "Error al insertar estudiante " . $student_num . " en la base de datos: " . $conn->error . "\n";
            $_SESSION['students_list_msg'] = "Error al insertar estudiante(s) " . $student_num . " en la base de datos: " . $conn->error;
        }
    }

    public function alreadyRecomended($student_num, $class, $term, $conn)
    {
        // Preparar la consulta SQL
        $sql = "SELECT * FROM recommended_courses WHERE student_num = ? AND crse_code = ? AND term = ?";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("sss", $student_num, $class, $term);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        // Verificar si se encontraron resultados
        $stmt->close();
        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insertRecomendation($student_num, $class, $term, $conn)
    {
        #insertRecommendation
        // Preparar la consulta SQL
        $sql = "INSERT INTO recommended_courses (student_num, crse_code, term) VALUES (?, ?, ?)";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bind_param("iss", $student_num, $class, $term);

        // Ejecutar la consulta
        $result = $stmt->execute();

        // Verificar si la inserción fue exitosa
        if ($result) {
            // Obtener la fecha actual
            $date = date("Y-m-d");
            $cc = 0;

            // Consulta SQL para actualizar la columna conducted_counseling y edited_date del estudiante
            $sql = "UPDATE student SET conducted_counseling = ?, edited_date = ? WHERE student_num = ?";

            // Preparar la declaración
            $stmt = $conn->prepare($sql);

            // Vincular los parámetros
            $stmt->bind_param("isi", $cc, $date, $student_num);

            // Ejecutar la consulta
            $result = $stmt->execute();

            // Borrar cursos que el estudiante seleccionó para próximo semestre
            $term = $this->getTerm($conn);
            $sql2 = 'DELETE FROM will_take WHERE student_num = ? AND term = ?';
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param("ss", $student_num, $term);
            $result = $stmt->execute();

            // Cerrar la declaración
            $stmt->close();

            $_SESSION['consejeria_msg'] = "Recomendación de " . $class . " añadida!!";

            return TRUE;
        } else {
            // Cerrar la declaración
            $stmt->close();
            $_SESSION['consejeria_msg'] = "No se pudo añadir recomendación de " . $class . ".";
            return FALSE;
        }
    }

    public function deleteRecomendation($student_num, $class, $term, $conn)
    {
        #deleteRecommendation
        // Preparar la consulta SQL
        $sql = "DELETE FROM recommended_courses WHERE student_num = ? AND crse_code = ? AND term = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bind_param("sss", $student_num, $class, $term);

        // Ejecutar la consulta
        $result = $stmt->execute();

        // Verificar si la eliminación fue exitosa
        if ($result) {
            // Obtener la fecha actual
            $date = date("Y-m-d");
            $cc = 0;

            // Consulta SQL para actualizar la columna conducted_counseling y edited_date del estudiante
            $sql = "UPDATE student SET conducted_counseling = ?, edited_date = ? WHERE student_num = ?";

            // Preparar la declaración
            $stmt = $conn->prepare($sql);

            // Vincular los parámetros
            $stmt->bind_param("isi", $cc, $date, $student_num);

            // Ejecutar la consulta
            $result = $stmt->execute();

            // Borrar cursos que el estudiante seleccionó para próximo semestre
            $term = $this->getTerm($conn);
            $sql2 = 'DELETE FROM will_take WHERE student_num = ? AND term = ?';
            $stmt = $conn->prepare($sql2);
            $stmt->bind_param("ss", $student_num, $term);
            $result = $stmt->execute();

            // Cerrar la declaración
            $stmt->close();

            $_SESSION['consejeria_msg'] = "Recomendación  de " . $class . " fue borrada!!";
            return TRUE;
        } else {
            // Cerrar la declaración
            $stmt->close();
            $_SESSION['consejeria_msg'] = "No se pudo borrar la recomendación de " . $class . ".";
            return FALSE;
        }
    }

    public function openCounseling($student_num, $conn)
    {
        $sql = "UPDATE student SET conducted_counseling = ? WHERE student_num = ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        $cc = 0;

        // Vincular los parámetros
        $stmt->bind_param("ii", $cc, $student_num);

        // Ejecutar la consulta
        $result = $stmt->execute();
        // Borrar cursos que el estudiante seleccionó para próximo semestre
        $term = $this->getTerm($conn);
        $sql2 = 'DELETE FROM will_take WHERE student_num = ? AND term = ?';
        $stmt = $conn->prepare($sql2);
        $stmt->bind_param("ss", $student_num, $term);
        $result = $stmt->execute();

        // Cerrar la declaración
        $stmt->close();

        return;
    }

    public function changeCounselingLock($student_num, $conn, $lockStatus)
    {
        $sql = "UPDATE student SET counseling_lock = ? WHERE student_num = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $lockStatus, $student_num);
        $result = $stmt->execute();

        $stmt->close();

        return;
    }

    public function closeAllCounseling($conn)
    {
        $sql = "UPDATE student SET counseling_lock = 1";

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();

        $stmt->close();

        return;
    }


    public function studentAlreadyHasGrade($student_num, $code, $conn)
    {
        // Preparar la consulta SQL
        $language_generals = array('INGL3101', 'INGL3103', 'INGL3011', 'INGL3102', 'INGL3104', 'INGL3012', 'ESPA3101', 'ESPA3003', 'ESPA3102', 'ESPA3004');

        foreach ($language_generals as $lg)
            if ($lg == $code)
                $code = $this->validateLanguageGenerals($conn, $code);

        $sql = "SELECT * FROM student_courses 
        WHERE student_num = ? AND crse_code = ? AND crse_grade != '' AND crse_status != 'M'";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("is", $student_num, $code);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function studentAlreadyHasGradeWithSemester($student_num, $code, $term, $conn)
    {
        // Preparar la consulta SQL
        $language_generals = array('INGL3101', 'INGL3103', 'INGL3011', 'INGL3102', 'INGL3104', 'INGL3012', 'ESPA3101', 'ESPA3003', 'ESPA3102', 'ESPA3004');

        foreach ($language_generals as $lg)
            if ($lg == $code)
                $code = $this->validateLanguageGenerals($conn, $code);

        $sql = "SELECT * FROM student_courses WHERE student_num = ? AND crse_code = ? AND term = ? AND crse_grade != ''";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("iss", $student_num, $code, $term);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        // Verificar si se encontraron resultados
        //$stmt->close();
        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function UpdateStudentGradeCSV($student_num, $course_code, $grade, $equi, $conva, $credits, $term, $type, $old_term, $status, $conn)
    {
        // Preparar la consulta SQL para la actualización
        $language_generals = array('INGL3101', 'INGL3103', 'INGL3011', 'INGL3102', 'INGL3104', 'INGL3012', 'ESPA3101', 'ESPA3003', 'ESPA3102', 'ESPA3004');

        foreach ($language_generals as $lg)
            if ($lg == $course_code) {
                $old_course_code = $course_code;
                $course_code = $this->validateLanguageGenerals($conn, $course_code);
                $equi .= $old_course_code;
            }

        $sql0 = "SELECT crse_grade, term
                FROM student_courses
                WHERE student_num = ? AND crse_code = ?";
        // Preparar la sentencia
        $stmt0 = $conn->prepare($sql0);
        if (!$stmt0) {
            // Manejar el error de preparación de la consulta
            return FALSE;
        }

        $stmt0->bind_param("is", $student_num, $course_code);

        $crse_grade = '';
        $old_term = '';

        // Ejecutar
        if ($stmt0->execute()) {
            // Sacar la nota
            $stmt0->bind_result($crse_grade, $old_term);
            $stmt0->fetch();

        } else {
            // Error
            echo "Error executing query.";
        }

        $stmt0->close();


        $checker = false; // el checker verifica que el estudiante haya pasado la clase
        if (strpos($crse_grade, 'I') != false) { 
            $_SESSION['registermodeltxt'] .= "El estudiante: " . $student_num . " tenia un incompleto en el curso" . $course_code . ".\n";
        }
        if (strpos($grade, 'D') == true || strpos($grade, 'F') == true)
            if (strpos($crse_grade, 'W') == false || strpos($crse_grade, 'I') == false)
                $checker = true;

        # $crse_grade is the grade they currently have
        # $grade is the grade being inserted
        if ((strcmp($grade, $crse_grade) < 0 || $crse_grade == '') && $checker == false) {
            $sql1 = "UPDATE student_courses 
                    SET credits = ?, category = ?, crse_grade = ?, crse_status = ?, term = ?, equivalencia = ?, convalidacion = ?
                    WHERE student_num = ? AND crse_code = ? AND term = ?";

            // Preparar la sentencia
            $stmt1 = $conn->prepare($sql1);
            if (!$stmt1) {
                // Manejar el error de preparación de la consulta
                echo "Error preparing SQL statement: " . $conn->error . "<br>";
                return FALSE;
            }

            // Vincular los parámetros con los valores
            $stmt1->bind_param("ssssssssss", $credits, $type, $grade, $status, $term, $equi, $conva, $student_num, $course_code, $old_term);

            // Ejecutar la sentencia
            try {
                if ($stmt1->execute()) {
                    // Verificar si la actualización fue exitosa
                    if ($stmt1->affected_rows > 0) {
                        $stmt1->close();
                        $_SESSION['students_list_msg'] = "Cursos de estudiantes fueron actualizados!!";
                        $date = date("Y-m-d");
                        $sql2 = "UPDATE student SET edited_date = '$date' WHERE student_num = $student_num";
                        $result = $conn->query($sql2);

                        if ($result === false) {
                            throw new Exception("Error en la consulta SQL: " . $conn->error);
                        }
                        return TRUE; // La actualización fue exitosa
                    } else {
                        $stmt1->close();
                        $_SESSION['students_list_msg'] = "No hubo cambios en la base de datos";
                        return FALSE; // La actualización no tuvo ningún efecto (ninguna fila afectada)
                    }
                } else {
                    // Ocurrió un error al ejecutar la consulta
                    // Manejar el error según sea necesario
                    //echo "Error executing SQL statement: " . $stmt1->error . "<br>";
                    $_SESSION['students_list_msg'] = "Error al insertar cursos de estudiantes en la base de datos: " . $conn->error;
                    $stmt1->close();
                    return FALSE;
                }
            } catch (Exception $e) {
                $_SESSION['students_list_msg'] = "Error al insertar cursos de estudiantes en la base de datos: " . $conn->error;
                $stmt1->close();
                return FALSE;
            }
        } else
            return TRUE;
    }

    public function UpdateStudentGradeManual($student_num, $course_code, $grade, $equi, $conva, $credits, $term, $category, $level, $old_term, $status, $conn)
    {
        $sql1 = "UPDATE student_courses 
                    SET credits = ?, category = ?, level = ?, crse_grade = ?, crse_status = ?, term = ?, equivalencia = ?, convalidacion = ?
                    WHERE student_num = ? AND crse_code = ? AND term = ?";

        $language_generals = array('INGL3101', 'INGL3103', 'INGL3011', 'INGL3102', 'INGL3104', 'INGL3012', 'ESPA3101', 'ESPA3003', 'ESPA3102', 'ESPA3004');

        foreach ($language_generals as $lg)
            if ($lg == $course_code)
                $course_code = $this->validateLanguageGenerals($conn, $course_code);

        // Preparar la sentencia
        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            // Manejar el error de preparación de la consulta
            echo "Error preparing SQL statement: " . $conn->error . "<br>";
            return FALSE;
        }

        // Vincular los parámetros con los valores
        $stmt1->bind_param("issssssssss", $credits, $category, $level, $grade, $status, $term, $equi, $conva, $student_num, $course_code, $old_term);

        // Ejecutar la sentencias
        if ($stmt1->execute()) {
            // Verificar si la actualización fue exitosa
            if ($stmt1->affected_rows > 0) {
                $stmt1->close();
                $_SESSION['consejeria_msg'] = "Curso $course_code fue actualizado!!";
                $date = date("Y-m-d");
                $sql2 = "UPDATE student SET edited_date = '$date' WHERE student_num = $student_num";
                $result = $conn->query($sql2);

                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
                return TRUE; // La actualización fue exitosa
            } else {
                $stmt1->close();
                $_SESSION['consejeria_msg'] = "No pudo actualizar el curso $course_code!!";
                return FALSE; // La actualización no tuvo ningún efecto (ninguna fila afectada)
            }
        } else {
            // Ocurrió un error al ejecutar la consulta
            // Manejar el error según sea necesario
            $stmt1->close();
            return FALSE;
        }
    }

    public function deleteStudentGrade($student_num, $course_code, $term, $conn)
    {
        $sql1 = "DELETE FROM student_courses 
                WHERE student_num = ? AND crse_code = ? AND term = ?";

        // Preparar la sentencia
        $stmt1 = $conn->prepare($sql1);
        if (!$stmt1) {
            // Manejar el error de preparación de la consulta
            echo "Error preparing SQL statement: " . $conn->error . "<br>";
            return FALSE;
        }

        // Vincular los parámetros con los valores
        $stmt1->bind_param("sss", $student_num, $course_code, $term);

        // Ejecutar la sentencia
        if ($stmt1->execute()) {
            // Verificar si la eliminación fue exitosa
            if ($stmt1->affected_rows > 0) {
                $stmt1->close();
                $_SESSION['consejeria_msg'] = "Curso $course_code fue eliminado!!";
                $date = date("Y-m-d");
                $sql2 = "UPDATE student SET edited_date = '$date' WHERE student_num = $student_num";
                $result = $conn->query($sql2);

                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
                return TRUE; // La eliminación fue exitosa
            } else {
                $stmt1->close();
                $_SESSION['consejeria_msg'] = "No pudo eliminar el curso $course_code!!";
                return FALSE; // La eliminación no tuvo ningún efecto (ninguna fila afectada)
            }
        } else {
            // Ocurrió un error al ejecutar la consulta
            // Manejar el error según sea necesario
            $stmt1->close();
            return FALSE;
        }
    }

    public function InsertStudentGrade($student_num, $course_code, $grade, $equi, $conva, $credits, $term, $category, $status, $conn)
    {
        // variable para ver si el curso es electiva avanzada o intermedia
        $course_level = '';

        // query que busca la nota y el semestre del curso que se busca en este estudiante
        $sql0 = "SELECT crse_grade, term, crse_status
                FROM student_courses
                WHERE student_num = ? AND crse_code = ?";

        // Preparar la sentencia
        $stmt0 = $conn->prepare($sql0);
        if (!$stmt0) {
            // Manejar el error de preparación de la consulta
            return FALSE;
        }

        // adaptando el query con los parametros que entran por la funcion
        $stmt0->bind_param("is", $student_num, $course_code);

        $language_generals = array('INGL3101', 'INGL3103', 'INGL3011', 'INGL3102', 'INGL3104', 'INGL3012', 'ESPA3101', 'ESPA3003', 'ESPA3102', 'ESPA3004');

        foreach ($language_generals as $lg)
        if ($lg == $course_code) {
            $old_course_code = $course_code;
            $course_code = $this->validateLanguageGenerals($conn, $course_code);
            $equi .= $old_course_code;
        }

        // variables para guardar el la nota y el semestre anterior
        $crse_grade = '';
        $old_term = '';
        $old_status = '';

        // Ejecutar
        if ($stmt0->execute()) {
            // the selected columns (grade, term) 
            $stmt0->bind_result($crse_grade, $old_term, $old_status);
            $resultex = $stmt0->get_result();
            $resultex = $resultex->fetch_assoc();
            $stmt0->close();
        } else {
            // Error
            echo "Error executing query.";
        }

        // $result = $stmt0->get_result();
        // if ($result->num_rows == 0) {
        //     return FALSE;
            
        // }

        // $result = $result->fetch_assoc();
        // if ($result->num_rows) {
        //     return FALSE;
        // }

        if (isset($resultex['crse_status'])) {
            $_SESSION['registermodeltxt'] .= "OLD STATUS: " . $resultex['crse_status'] . " \n";
            if ($resultex['crse_status'] == 'M') {
                $sql = "UPDATE student_courses
                        SET crse_grade = ?, crse_status = ?
                        WHERE student_num = ? AND crse_code = ? AND term = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssiss", $grade, $status, $student_num, $course_code, $term);
                $stmt->execute();
                $stmt->close();
                $_SESSION['registermodeltxt'] .= "No debe llegar al insert para $student_num, $course_code \n";
                return TRUE;
            }
        }

        if ((strpos($course_code, 'CCOM') !== false)) {
            $sql0 = "SELECT `level` FROM ccom_courses WHERE crse_code = ?";
            $stmt = $conn->prepare($sql0);
            if (!$stmt) {
                // Manejar el error de preparación de la consulta
                return FALSE;
            }
            $stmt->bind_param("s", $course_code);
            if ($stmt->execute()) {
                // Vincular el resultado de la consulta a una variable
                $stmt->bind_result($course_level);

                // Obtener el resultado de la consulta
                if ($stmt->fetch())
                    $level = $course_level;
            }

            $stmt->close();
        } else
            $level = 'NULL';



        $sql = "SELECT crse_code FROM student_courses WHERE student_num = ? AND crse_code = ?";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Manejar el error de preparación de la consulta
            return FALSE;
        }

        // Vincular los parámetros con los valores
        $stmt->bind_param("ss", $student_num, $course_code);

        // if ($stmt->execute()) {
        //     if ($stmt->affected_rows > 0) {
        //         return 0;
        //     }
        // }

        $stmt->close();


        $_SESSION['registermodeltxt'] .= "Llega al insert para $student_num, $course_code \n";
        // Preparar la consulta SQL para la inserción
        $sql = "INSERT INTO student_courses (student_num, crse_code, credits, category, level, crse_grade, crse_status, term, equivalencia, convalidacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Manejar el error de preparación de la consulta
            return FALSE;
        }

        // Vincular los parámetros con los valores
        $stmt->bind_param("ssssssssss", $student_num, $course_code, $credits, $category, $level, $grade, $status, $term, $equi, $conva);

        // echo $stmt;

        // Ejecutar la sentencia
        try {
            if ($stmt->execute()) {
                // Verificar si la inserción fue exitosa
                if ($stmt->affected_rows > 0) {
                    $stmt->close();
                    $_SESSION['consejeria_msg'] = "Curso $course_code y nota fueron insertados!!";
                    $date = date("Y-m-d");
                    $sql2 = "UPDATE student SET edited_date = '$date' WHERE student_num = $student_num";
                    $result = $conn->query($sql2);

                    if ($result === false) {
                        throw new Exception("Error en la consulta SQL: " . $conn->error);
                    }
                    return TRUE; // La inserción fue exitosa
                } else {
                    $stmt->close();
                    $_SESSION['consejeria_msg'] = "No se pudo insertar nota y curso $course_code.";
                    return FALSE; // La inserción no tuvo ningún efecto (ninguna fila afectada)
                }
            } else {
                // Ocurrió un error al ejecutar la consulta
                $stmt->close();
                $_SESSION['consejeria_msg'] = "No se pudo insertar nota y curso $course_code.";
                return FALSE;
            }
        } catch (Exception $e) {
            $_SESSION['consejeria_msg'] = "No se pudo insertar nota y curso $course_code.";
        }
    }

    public function studentHaveMinor($student_num, $conn)
    {
        // Preparar la consulta SQL para obtener el nombre de la menor
        $sql = "SELECT 
                    CASE
                        WHEN s.minor > 0 THEN m.name
                        ELSE NULL
                    END AS minor_name
                FROM student s
                LEFT JOIN minor m ON s.minor = m.ID
                WHERE s.student_num = ?";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // Manejar el error de preparación de la consulta
            return null;
        }

        // Vincular los parámetros con los valores
        $stmt->bind_param("s", $student_num);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Obtener el resultado de la consulta
            $result = $stmt->get_result();

            // Verificar si se encontró alguna fila
            if ($result->num_rows == 1) {
                // Obtener el nombre de la menor
                $row = $result->fetch_assoc();
                $minor_name = $row['minor_name'];

                // Cerrar la sentencia y liberar recursos
                $stmt->close();

                // Devolver el nombre de la menor
                return $minor_name;
            } else {
                // No se encontró ninguna fila o se encontraron múltiples filas
                $stmt->close();
                return null;
            }
        } else {
            // Manejar el error de ejecución de la consulta
            return null;
        }
    }

    public function alreadyHasGradeInTerm($student_num, $class, $term, $conn)
    {
        // Preparar la consulta SQL
        $language_generals = array('INGL3101', 'INGL3103', 'INGL3011', 'INGL3102', 'INGL3104', 'INGL3012', 'ESPA3101', 'ESPA3003', 'ESPA3102', 'ESPA3004');

        foreach ($language_generals as $lg)
            if ($lg == $class)
                $class = $this->validateLanguageGenerals($conn, $class);

        $sql = "SELECT * FROM student_courses WHERE student_num = ? AND crse_code = ? AND term = ? AND crse_grade != ''";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("sss", $student_num, $class, $term);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        // Verificar si se encontraron resultados
        $stmt->close();
        if ($result->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateEditDate($conn, $student_num, $date)
    {
        $sql = "UPDATE student SET edited_date = ? WHERE student_num = ?";
        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular el parámetro con el valor
        $stmt->bind_param("ss", $date, $student_num);
        // Ejecutar la sentencia
        $stmt->execute();

        return;
    }

    public function getTerm($conn)
    {
        $sql = "SELECT term
                FROM offer
                WHERE crse_code = 'XXXX'";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        foreach ($result as $res)
            $term = $res['term'];

        return $term;
    }

    public function getPrevTerm($conn)
    {
        $sql = "SELECT term
            FROM offer
            WHERE crse_code = 'CCOM3001'
            AND term not in (SELECT term FROM offer WHERE crse_code = 'XXXX')";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        foreach ($result as $res)
            $term = $res['term'];

        return $term;
    }

    public function generateAutoReports($conn, $date)
    {
        # CONSIDER adding error log lines
        $term = $this->getTerm($conn);

        # Find all the students that are not inactive in the database
        $sql1 = 'SELECT student_num, cohort_year, status
                FROM student
                WHERE status = "Activo"';

        $result = $conn->query($sql1);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL1: " . $conn->error);
        }

        foreach ($result as $student) {
            $cohort = $student['cohort_year'];
            # Find all the courses currently in offer for the following semester
            # Select only the courses that are found in that student's cohort
            $sql2 = "SELECT * FROM offer WHERE term = '$term' AND crse_code != 'XXXX'
            AND crse_code in (SELECT crse_code FROM cohort WHERE cohort_year = '$cohort')";
            $res2 = $conn->query($sql2);

            if ($res2 === false) {
                throw new Exception("Error en la consulta SQL2: " . $conn->error);
            }

            foreach ($res2 as $offer_course) {
                $num = $student['student_num'];
                $crse_code = $offer_course['crse_code'];
                # Find if that student has that course recommended already in current semester
                $sql3 = "SELECT * FROM recommended_courses WHERE term = '$term' AND student_num = $num AND crse_code = '$crse_code'";
                $res3 = $conn->query($sql3);
                if ($res3 === false) {
                    throw new Exception("Error en la consulta SQL3: " . $conn->error);
                }
                if ($res3->num_rows == 0) # If course not recommended in current semester
                {
                    # Find that course in student's courses
                    $sql4 = "SELECT * FROM student_courses WHERE student_num = $num AND crse_code = '$crse_code'";
                    $res4 = $conn->query($sql4);
                    if ($res4 === false) {
                        throw new Exception("Error en la consulta SQL4: " . $conn->error);
                    }

                    $checker1 = false;
                    $prevTerm = $this->getPrevTerm($conn);
                    if ($res4->num_rows > 0) # If student has seen this course before
                    {
                        foreach ($res4 as $res4) {
                            if ($res4['crse_status'] == 'P' || $res4['term'] == $prevTerm && $res4['crse_grade'] != 'W') {
                                $checker1 = true; # Dismiss a class if student has passed it or is currently seeing it
                            }
                        }
                    }

                    if ($checker1 == false) # If student hasn't passed this course already or is seeing the course in term before the recommendations term
                    {
                        $sql5 = "SELECT * FROM ccom_requirements WHERE crse_code = '$crse_code' AND cohort_year = '$cohort' AND type = 'pre'
                        UNION
                                 SELECT * FROM general_requirements WHERE crse_code = '$crse_code' AND cohort_year = '$cohort' AND type = 'pre'";
                        $res5 = $conn->query($sql5);
                        if ($res5 === false) {
                            throw new Exception("Error en la consulta SQL5: " . $conn->error);
                        }
                        $checker2 = true;

                        if ($res5->num_rows > 0) # If the course has requirements in this cohort year
                        {
                            foreach ($res5 as $course5) {
                                $req_crse_code = $course5['req_crse_code'];
                                # Find each req in the student's courses
                                $sql6 = "SELECT * FROM student_courses WHERE student_num = $num AND crse_code = '$req_crse_code'";
                                $res6 = $conn->query($sql6);
                                if ($res6 === false) {
                                    throw new Exception("Error en la consulta SQL6: " . $conn->error);
                                }
                                $req_status = '';
                                $req_term = '';
                                if ($res6->num_rows > 0) {
                                    foreach ($res6 as $res6) {
                                        $req_status =  $res6['crse_status'];
                                        $req_term = $res6['term'];
                                        $req_grade = $res6['crse_grade'];
                                    }
                                }

                                if ($req_status == 'P' || $req_term == $prevTerm && $req_grade != 'W') # If req has been passed, then continue to next req
                                    continue;
                                else
                                    $checker2 = false;
                            }
                        }

                        if ($checker2 == true) # If the course's requirements have been met, insert into recommendations
                        {
                            $sql7 = "INSERT INTO recommended_courses VALUES($num, '$crse_code', '$term')";
                            $res7 = $conn->query($sql7);
                            if ($res7 === false) {
                                throw new Exception("Error en la consulta SQL7: " . $conn->error);
                            }

                            $cc = 0;

                            $sql8 = "UPDATE student SET conducted_counseling = $cc, edited_date = '$date' WHERE student_num = $num";
                            $res8 = $conn->query($sql8);
                            if ($res8 === false) {
                                throw new Exception("Error en la consulta SQL8: " . $conn->error);
                            }
                        }
                    } else
                        continue; # Otherwise, go to next course in offer
                }
            }
        }


        $_SESSION['students_list_msg'] = 'Auto recomendaciones han sido actualizadas!!';
        return;
    }

    public function validateLanguageGenerals($conn, $crse_code)
    {
        if ($crse_code == 'INGL3101' || $crse_code == 'INGL3103' || $crse_code == 'INGL3011')
            return 'INGL0001';
        if ($crse_code == 'INGL3102' || $crse_code == 'INGL3104' || $crse_code == 'INGL3012')
            return 'INGL0002';
        if ($crse_code == 'ESPA3101' || $crse_code == 'ESPA3003')
            return 'ESPA0001';
        if ($crse_code == 'ESPA3102' || $crse_code == 'ESPA3004')
            return 'ESPA0002';
    }

    /* New functions */
    public function deleteAllRecommendations($conn)
    {
        $sql = "DELETE FROM recommended_courses NATURAL JOIN student
                WHERE confirmed = 0";
        $res = $conn->query($sql);
        if ($res === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }
    }

    public function confirmCounseling($conn, $id)
    {
        $sql = "UPDATE student
                SET confirmed = 1
                WHERE student_num = $id";
        $res = $conn->query($sql);
        if ($res === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }
    }

    public function deleteAllRecommendationsOnOneStudent($conn, $student_num)
    {
        $sql = "DELETE FROM recommended_courses
                WHERE student_num=$student_num";
        $res = $conn->query($sql);
        if ($res === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }
    }

    public function getClassesStudentWillTake($student_num, $selectedTerm, $conn)
    {
        $sql = "SELECT COALESCE(courses.name, '') AS name, COALESCE(courses.crse_code, '') AS crse_code 
        FROM will_take 
        LEFT JOIN
            (SELECT crse_code, name FROM ccom_courses
             UNION
             SELECT crse_code, name FROM general_courses
             UNION
             SELECT crse_code, name FROM dummy_courses) AS courses
        ON
            will_take.crse_code = courses.crse_code
        WHERE will_take.student_num = ? AND will_take.term = ?";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);
        // Vincular los parámetros con los valores
        $stmt->bind_param("ss", $student_num, $selectedTerm);
        // Ejecutar la sentencia
        $stmt->execute();
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();
        // Cerrar la sentencia
        $stmt->close();

        // Verificar si hay resultados
        if ($result->num_rows === 0) {
            return NULL; // Devolver NULL si no hay filas
        } else {
            // Obtener los resultados como un array asociativo
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data; // Devolver los resultados
        }
    }
}
