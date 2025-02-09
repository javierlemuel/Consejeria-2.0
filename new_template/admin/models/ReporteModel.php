<?php
// models/StudentModel.php
class ReporteModel {

    public function getStudentsAconsejados($conn){
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM recommended_courses
        WHERE term = '$term'";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        
        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;

   }

    public function getStudentsSinCCOM($conn)
   {
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM recommended_courses
        WHERE student_num NOT IN (
            SELECT DISTINCT student_num
            FROM recommended_courses
            WHERE crse_code LIKE 'CCOM%'
            AND term = '$term'
            )
        AND term = '$term'";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;
   }

    public function getStudentsNoConsejeria($conn)
   {
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM student
        WHERE status = 'Activo' AND student_num NOT IN (
            SELECT DISTINCT student_num
            FROM will_take)";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;
   }

    public function getStudentsActivos($conn)
   {
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM student
        WHERE status = 'Activo'";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;
   }

    public function getStudentsInactivos($conn)
   {
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM student
        WHERE status = 'Inactivo'";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;
   }

    public function getStudentsInfo($conn, $type, $term)
   {

        if ($type == 'consCCOM')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM recommended_courses NATURAL JOIN student
            WHERE term = '$term'";
        }
        else if ($type == 'consSinCCOM')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM recommended_courses NATURAL JOIN student
            WHERE student_num NOT IN (
                SELECT DISTINCT student_num
                FROM recommended_courses 
                WHERE crse_code LIKE 'CCOM%'
                AND term = '$term'
                )
            AND term = '$term'"; // por que lo saca de recommended courses y no de ccom_courses??
        }
        else if ($type == 'noCons')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM student
            WHERE status = 'Activo' AND student_num NOT IN (
                SELECT DISTINCT student_num
                FROM will_take)";
        }
        else if ($type == 'Cons')
        {
            $sql = "SELECT DISTINCT student_num, name1, name2, last_name1, last_name2
            FROM will_take NATURAL JOIN student
            WHERE term = '$term'";
        }
        else if ($type == 'active')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM student
            WHERE status = 'Activo'";
        }
        else if ($type == 'openinactive')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM student
            WHERE status = 'Inactivo'";
        }
        else if ($type == 'incomplete')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2, crse_code, crse_grade
            FROM student NATURAL JOIN student_courses
            WHERE crse_grade LIKE '%I%'";
        }
 
        else { // si no cae en ninguno de los anteriores $type debe ser un curso, y va a buscar
            // los estudiantes que lo tomaran el proximo term
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM will_take NATURAL JOIN student
            WHERE crse_code = '$type' AND term = '$term'";
        }

        $students = [];
        $result2 = $conn->query($sql);

            if ($result2 === false) {
                throw new Exception("Error2 en la consulta SQL: " . $conn->error);
            }
            
            $combinedData = [];

            while($row2 = $result2->fetch_assoc()) {
               $combinedData[] = $row2;
            }
            foreach ($combinedData as &$data) {
                $data['full_name'] = $data['name1']." ".$data['name2']." ".$data['last_name1']." ".$data['last_name2'];
            }
            $students = array_merge($students, $combinedData);

            return $students;
   }

    public function getRegistrados($conn){
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM will_take
        WHERE term = '$term';";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;

   }

    public function getEditados($conn)
   {
        $sql = "SELECT COUNT(edited_date) AS count
                FROM student
                WHERE edited_date is not NULL
                AND edited_date != '0000-00-00'";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;
   }


    public function getStudentsPerClass($conn){
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        $sql = "SELECT crse_code, COUNT(*) AS count
        FROM ccom_courses NATURAL JOIN will_take
        WHERE crse_code LIKE 'CCOM%'
        AND term = '$term'
        GROUP BY crse_code;";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;

   }

//     public function getTerm($conn){
//         $sql = "SELECT term
//                 FROM offer
//                 WHERE crse_code = 'XXXX'";

//         $result = $conn->query($sql);

//         if ($result === false) {
//             throw new Exception("Error en la consulta SQL: " . $conn->error);
//         }

//         foreach ($result as $res)
//             $term = $res['term'];

//         return $term;
//    }

    // funciones nuevas
    public function updateInactiveStudents($conn) {
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        $sql = "SELECT DISTINCT student_num
        FROM student
        WHERE status = 'Activo' AND student_num NOT IN (
            SELECT student_num
            FROM student NATURAL JOIN student_courses
            WHERE term = '$term')";
        $result1 = $conn->query($sql);

        if ($result1 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        foreach ($result1 as $student) {
            $temp = $student['student_num']; // student number to insert into query
            $sql = "UPDATE student SET status = 'Inactivo' WHERE student_num = $temp";
            $result2 = $conn->query($sql);
        }

        
        if ($result2 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        // foreach ($result1 as $student) { // students that are marked as active
        //     // add each term to an array in a dict
        //     if (!isset($activeStudents[$student['student_num']])) {
        //         $activeStudents[$student['student_num']] = [];
        //         $activeStudents[$student['student_num']] = $student['term'];
        //     } else {
        //         $activeStudents[$student['student_num']] = $student['term'];
        //     } // aqui lo que quiero es guardar todos los terms en un array, y ese array
        //     // se guarda en ese numero de estudiante en el dict
        //     // despues se chequea a ver si el term actual esta en ese array
        //     // si no ese key (numero de estudiante) se guarda en otro array, y se marcan esos como inactive
        // }
    }

    public function getClassesByStudent($conn) {
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        $sql = "SELECT student_num, crse_code FROM will_take NATURAL JOIN student WHERE term = '$term';";

        $result1 = $conn->query($sql);

        if ($result1 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $currentStudent = 0;
        foreach ($result1 as $class) {
            if ($currentStudent != $class['student_num']) {
                $currentStudent = $class['student_num'];
                $classes[$currentStudent] = [];
            }

            $classes[$currentStudent][] = $class['crse_code'];
        }

        return $classes;
    }

    public function getStudentsIncompletos($conn) {
        $sql = "SELECT COUNT(crse_code) AS count
        FROM student_courses
        WHERE crse_grade LIKE '%I%'";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        
        if ($result->num_rows > 0)
            foreach($result as $res)
                return $res['count'];
        else    
            return 0;
    }

    public function getRepeatedCourses($conn) {
        // get the active term from the terms model
        $termsModel = new TermsModel();
        $term = $termsModel->getActiveTerm($conn);
        // counter to find how many courses are repeated
        $counter = 0;

        // este query busca todos los cursos de todos los estudiantes y los pone en orden
        $sql = "SELECT student_num, crse_code, term, crse_grade
                FROM student_courses
                WHERE term = ?
                ORDER BY student_num, crse_code, term, crse_grade";

        // se procesa el query y se guardan los resultados en $result
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $term); // selecciona el term activo
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // para cada curso en $result se hace un query para buscar cuantos hay con el mismo 
        // numero de estudiante, codigo del curso, nota y term
        foreach ($result as $course) {
            $sql = "SELECT *
                FROM student_courses
                WHERE student_num = ? AND crse_code = ? AND crse_grade = ? AND term = ?";

            // se procesa el query y se guarda en $result2
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $course['student_num'], 
                                $course['crse_code'], $course['crse_grade'], $term);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $stmt->close();

            // si hay mas de una fila con esa informacion se verifica que no este en la tabla nueva
            if ($result2->num_rows > 1) {
                $sql = "SELECT *
                    FROM new_student_courses
                    WHERE student_num = ? AND crse_code = ? AND crse_grade = ? AND term = ?";

                // se procesa el query y se guarda en $result3
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isss", $course['student_num'], 
                                    $course['crse_code'], $course['crse_grade'], $term);
                $stmt->execute();
                $result3 = $stmt->get_result();
                $stmt->close();

                // si no esta en la tabla nueva se hace el query para insertar la informacion
                // guardada en el $result2
                if ($result3->num_rows < 1) {
                    $equivalencia = '';
                    $convalidacion = '';

                    foreach ($result2 as $courseInfo) {
                        $equivalencia .= $courseInfo['equivalencia'];
                        $convalidacion .= $courseInfo['convalidacion'];
                    }

                    // se procesa el query y se guarda en $result3
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isss", $course['student_num'], 
                                        $course['crse_code'], $course['crse_grade'], $term);
                    $stmt->execute();
                    $result3 = $stmt->get_result();
                    $stmt->close();

                    $sql = "INSERT INTO new_student_courses (student_num, crse_code, credits, category,
                                        level, crse_grade, crse_status, term, equivalencia, convalidacion)
                    VALUES (?,?,?,?,?,?,?,?,?,?)";

                    $stmt = $conn->prepare($sql);

                    $row = $result2->fetch_assoc();
                    $stmt->bind_param("isisssssss", $row['student_num'], $row['crse_code'],
                                        $row['credits'], $row['category'],
                                        $row['level'], $row['crse_grade'], $row['crse_status'],
                                        $term, $equivalencia, $convalidacion);
                    $stmt->execute();
                    $stmt->close();

                    // se incrementa el counter para indicar que habia un curso con duplicados
                    $counter += 1;
                }
            }
        }
        return $counter;
    }
}