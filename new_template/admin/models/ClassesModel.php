<?php
// models/StudentModel.php
class ClassesModel {

    public function getCcomCourses($conn)
    {
        $sql = "SELECT crse_code, name, credits
                FROM ccom_courses 
                WHERE type = 'mandatory' 
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        // $students = [];
        // while ($row = $result->fetch_assoc()) {
        //     $student_num = $row['student_num'];
        //     $formatted_student_num = substr($student_num, 0, 3) . '-' . substr($student_num, 3, 2) . '-' . substr($student_num, 5);
        //     $row['formatted_student_num'] = $formatted_student_num;
        //     $students[] = $row;
        // }

        return $result;
    }

    public function getCcomCoursesE($conn)
    {
        $sql = "SELECT crse_code, name, credits
                FROM ccom_courses 
                WHERE type = 'mandatory' 
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $courses = array();

        // Iteramos sobre las filas y almacenamos los resultados en el array
        while ($row = $result->fetch_assoc()) {
            $courses[] = array(
                'crse_code' => $row['crse_code'],
                'name' => $row['name'],
                'credits' => $row['credits']
            );
        }

        // Liberamos el resultado
        $result->free_result();

        return $courses;
    }

    public function getCcomElectives($conn)
    {
        $sql = "SELECT *
                FROM ccom_courses
                WHERE type != 'mandatory'
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getGeneralCourses($conn)
    {
        $sql = "SELECT *
                FROM general_courses
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getOfferCourses($conn)
    {
        $sql = "SELECT *
                FROM offer
                WHERE crse_code != 'XXXX'
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $courses = [];

        while ($row = $result->fetch_assoc()) {
            $code = $row['crse_code'];
            $term = $row['term'];
            $sql2 = "SELECT *
                    FROM ccom_courses
                    WHERE crse_code = '$code'
                    UNION ALL
                    SELECT *
                    FROM general_courses
                    WHERE crse_code = '$code'";
            $result2 = $conn->query($sql2);

            if ($result2 === false) {
                throw new Exception("Error2 en la consulta SQL: " . $conn->error);
            }
            
            $combinedData = [];

            while($row2 = $result2->fetch_assoc()) {
               $combinedData[] = $row2;
            }
            foreach ($combinedData as &$data) {
                $data['term'] = $term;
            }
            $courses = array_merge($courses, $combinedData);
        }

        // foreach ($courses as $course) {
        //     echo $course['name'];
        // }

        // echo "hey";

        return $courses;

    }

    public function addToOffer($conn,$courseID)
    {
        //Verifica que el curso no exista ya en la oferta
        $sql = "SELECT term
                FROM offer
                WHERE crse_code = '$courseID'";
        $result = $conn->query($sql);   

        if ($result->num_rows == 0)
        {
            $sql = "SELECT term
                    FROM offer
                    WHERE crse_code = 'XXXX'";

            $res = $conn->query($sql);
            if ($res === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }
            else{
                foreach($res as $r)
                    $term = $r['term'];
            
                $sql2 = "INSERT INTO offer
                        VALUES('$courseID', '$term')";
                
                $result = $conn->query($sql2);
                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }

                return 'success';
            }
        }

        return 'failure';
    }

    public function removeFromOffer($conn,$courseID){
        $sql = "DELETE FROM offer
                WHERE crse_code = '$courseID'";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function setNewTerm($conn, $term)
    {
        //Estamos seteando un nuevo semestre de consejeria

        //Borramos los cursos en oferta del semestre pasado
        $sql = "DELETE FROM offer
                WHERE term != ''";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        //Borramos los edit flags de los estudiantes
        $sql2 = "UPDATE student
                SET edited_flag = 0
                WHERE edited_flag = 1";
        $result2 = $conn->query($sql2);
        if ($result2 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        //Borramos el conteo de estudiantes editados desde admin
        $sql3 = "UPDATE advisor
                SET edited_count = 0
                WHERE edited_count > 0";
        $result3 = $conn->query($sql3);
        if ($result3 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        //Borramos los cursos que los estudiantes escogieron en sus consejerias
        $sql4 = "DELETE FROM takes
                WHERE crse_code != ''";
        $result4 = $conn->query($sql4);
        if ($result4 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }     

        //Insertamos el nuevo term/semestre con un curso 'dummy', como row de titulo del term
        $sql5 = "INSERT INTO offer
                VALUES('XXXX', '$term')";
        $result5 = $conn->query($sql5);
        if ($result5 === false) {   
            throw new Exception("Error2 en la consulta SQL:". $conn->error);
        }

        return $result5;
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
        
        while ($row = $result->fetch_assoc()) {
            $term = $row['term'];
            break;
        }

        return $term;

    }

    public function getMatriculadosModel($conn, $course)
    {
        $sql = "SELECT count(student_num) AS count
                FROM takes
                WHERE crse_code = '$course'";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }


        if ($result->num_rows == 0)
            return 0;

        while ($row = $result->fetch_assoc()) {
            $count = $row['count'];
            break;
        }

        return $count;

    }
}