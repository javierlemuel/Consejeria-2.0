<?php
// models/StudentModel.php
class ReporteModel {

    public function getStudentsAconsejados($conn){
        $termsModel = new TermsModel();
        $term = $termsModel->getCounselingTerm($conn);
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
        $term = $termsModel->getCounselingTerm($conn);
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
        $termsModel = new TermsModel();
        $term = $termsModel->getCounselingTerm($conn);
        $sql = "SELECT COUNT(DISTINCT student_num) AS count
        FROM student
        WHERE status = 'Activo' AND counseling_lock = 0 AND student_num NOT IN (
            SELECT DISTINCT student_num
            FROM will_take
            WHERE term = '$term')";
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
            $sql = "SELECT DISTINCT student_num, name1, name2, last_name1, last_name2
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
            AND term = '$term'"; 
        }
        else if ($type == 'noCons')
        {
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2, email
            FROM student
            WHERE status = 'Activo' AND counseling_lock = 0 AND student_num NOT IN (
                SELECT DISTINCT student_num
                FROM will_take
                WHERE term = '$term')";
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
 
        else if (str_contains($type, 'ACCOM')) {
            // si no cae en ninguno de los anteriores $type debe ser un curso aconsejado, y va a buscar
            // los estudiantes que les fue recomendado tomarlo el proximo term
            $sql = "SELECT student_num, name1, name2, last_name1, last_name2
            FROM recommended_courses NATURAL JOIN student
            WHERE crse_code = '$type' AND term = '$term'";
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
        $term = $termsModel->getCounselingTerm($conn);
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

    public function getRevisados($conn)
   {    
        $termsModel = new TermsModel();
        $termInfo = $termsModel->getActiveTermInfo($conn);

        $years = explode('-', $termInfo['year']);

        if ($termInfo['semester'] == 'Primero') {
            $semesterStart = date(DATE_ATOM, mktime(0, 0, 0, 8, 1, $years[0]));
            $semesterEnd = date(DATE_ATOM, mktime(0, 0, 0, 12, 1, $years[0]));
        } else {
            $semesterStart = date(DATE_ATOM, mktime(0, 0, 0, 1, 1, $years[1]));
            $semesterEnd = date(DATE_ATOM, mktime(0, 0, 0, 6, 1, $years[1]));
        }

        $sql = "SELECT COUNT(student_num) AS count
                FROM student
                WHERE edited_date > '$semesterStart' AND edited_date < '$semesterEnd'";

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
        $term = $termsModel->getCounselingTerm($conn);
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

   public function getRecommendationsPerClass($conn){
        $termsModel = new TermsModel();
        $term = $termsModel->getCounselingTerm($conn);
        $sql = "SELECT crse_code, COUNT(*) AS count
        FROM ccom_courses NATURAL JOIN recommended_courses
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

    public function getClassesByStudent($conn, $type) {
        $termsModel = new TermsModel();
        $term = $termsModel->getCounselingTerm($conn);

        if ($type == 'consCCOM')
            $table = 'recommended_courses';
        if ($type == 'Cons')
            $table = 'will_take';

        $sql = "SELECT student_num, crse_code FROM $table NATURAL JOIN student WHERE term = '$term';";

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

    public function moveRepeatedCourses($conn) {
        // este query busca todos los cursos de todos los estudiantes 
        $sql = "SELECT *
                FROM student_courses";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $allCourses = $stmt->get_result(); 
        $stmt->close();

        foreach ($allCourses as $course) {

            $student_num = $course['student_num'];
            $crse_code = $course['crse_code'];
            $term = $course['term'];

            // este query va a verificar si el curso ya esta en la tabla nueva
            $sql = "SELECT student_num, crse_code, term
            FROM student_courses_new
            WHERE student_num = $student_num AND crse_code = '$crse_code' AND term = '$term'";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $currentCourseInNewTable = $stmt->get_result(); 
            $stmt->close();

            if ($currentCourseInNewTable->num_rows < 1) { // if the course hasn't been added
                // este query va a buscar las entradas repetidas en la tabla original
                $sql = "SELECT *
                FROM student_courses
                WHERE student_num = $student_num AND crse_code = '$crse_code' AND term = '$term'";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $currentCourseInOldTable = $stmt->get_result(); 
                $stmt->close();

                $equivalencia = '';
                $convalidacion = '';
                foreach ($currentCourseInOldTable as $oldInfo) {
                    // PARA LAS EQUIVALENCIAS
                    if ($equivalencia == '' && $equivalencia != $oldInfo['equivalencia'])
                    $equivalencia .= $oldInfo['equivalencia'] . ' | ';
                    elseif (substr($equivalencia,0,-3) != $oldInfo['equivalencia'])
                        $equivalencia .= $oldInfo['equivalencia'] . ' | ';
                    // PARA LAS CONVALIDACIONES
                    if ($convalidacion == '' && $convalidacion != $oldInfo['convalidacion'])
                    $convalidacion .= $oldInfo['convalidacion'] . ' | ';
                    elseif (substr($convalidacion,0,-3) != $oldInfo['convalidacion'])
                        $convalidacion .= $oldInfo['convalidacion'] . ' | ';

                    // if (strcasecmp($crse_grade, $oldInfo['crse_grade']) > 0 || $crse_grade == '') { 
                    //     if ($oldInfo['crse_grade'] != 'F' && $oldInfo['crse_grade'] != 'D' && $oldInfo['crse_grade'] != 'W' && strpos($oldInfo['crse_grade'], 'I') == false){
                    //         // falta hacer otra comparacion para los cursos que no son de ccom
                    //         $crse_grade = $oldInfo['crse_grade'];
                    //     }
                    // }
                }

                if (substr_count($equivalencia, '|') >= 1)
                    $equivalencia = substr($equivalencia,0,-3);

                if (substr_count($convalidacion, '|') >= 1)
                    $convalidacion = substr($convalidacion,0,-3);

                $crse_grade = $course['crse_grade'];
                $credits = $course['credits'];
                $category = $course['category'];
                $level = $course['level'];
                $crse_status = $course['crse_status'];

                // este query va a insertar la clase en la tabla nueva
                $sql = "INSERT INTO student_courses_new 
                (student_num, crse_code, term, crse_grade, credits, category, 
                level, crse_status, equivalencia, convalidacion) 
                VALUES ($student_num, '$crse_code', '$term', '$crse_grade', $credits, 
                '$category', '$level', '$crse_status', '$equivalencia', '$convalidacion')";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $currentCourseInOldTable = $stmt->get_result(); 
                $stmt->close();
            }
        }
    }

    public function deleteRepeatedRecommendations($conn) {
        // este query busca todos los cursos recomendados
        $sql = "SELECT student_num, crse_code, term
                FROM recommended_courses";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $recommendedCourses = $stmt->get_result(); 
        $stmt->close();

        foreach( $recommendedCourses as $course) {
            $sql = "SELECT COUNT(student_num)
            FROM recommended_courses
            WHERE student_num = ? AND crse_code = ? AND term = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $course['student_num'], $course['crse_code'], $course['term']);
            $stmt->execute();
            $recommendedCount = $stmt->get_result(); 
            $stmt->close();

            if ($recommendedCount->fetch_row()[0] > 1) {
                $sql = "DELETE FROM recommended_courses
                WHERE student_num = ? AND crse_code = ? AND term = ?
                LIMIT 1";
    
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sss', $course['student_num'], $course['crse_code'], $course['term']);
                $stmt->execute();
                $stmt->close();
            }
        }


        // este query busca todos los cursos que tomara el estudiante
        $sql = "SELECT student_num, crse_code, term
                FROM will_take";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $willTakeCourses = $stmt->get_result(); 
        $stmt->close();

        foreach( $willTakeCourses as $course) {
            $sql = "SELECT COUNT(student_num)
            FROM will_take
            WHERE student_num = ? AND crse_code = ? AND term = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $course['student_num'], $course['crse_code'], $course['term']);
            $stmt->execute();
            $recommendedCount = $stmt->get_result(); 
            $stmt->close();

            if ($recommendedCount->fetch_row()[0] > 1) {
                $sql = "DELETE FROM will_take
                WHERE student_num = ? AND crse_code = ? AND term = ?
                LIMIT 1";
    
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sss', $course['student_num'], $course['crse_code'], $course['term']);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}