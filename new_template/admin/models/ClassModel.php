<?php
// models/StudentModel.php
class ClassModel {

    public function getCourse($conn, $course)
    {
        $table = 'general_courses';

        if((strpos($course, 'CCOM') !== false))
            $table = 'ccom_courses';

        $sql = "SELECT *
                FROM $table 
                WHERE crse_code = '$course'";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getMinors($conn){
        $sql = "SELECT *
                FROM minor";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function updateCourse($conn, $oldcourse, $course_id, $course_name, $credits,
    $type, $level, $minor)
    {
        $sql = "UPDATE ccom_courses
                SET crse_code = '$course_id',
                name = '$course_name',
                credits = $credits,
                type = '$type',
                level = '$level',
                minor_id = '$minor'
                WHERE crse_code = '$oldcourse'
                ";

        
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function removeCourse($conn, $course)
    {
        $checker = true;
        $sql1 = "SELECT * FROM student_courses WHERE crse_code = '$course'";
        $result = $conn->query($sql1);
        if ($result->num_rows > 0){ //Don't delete if course found in Student Courses table
            $checker = false;
            $message = 'NoDelSC';
        }

        $sql2 = "SELECT * FROM recommended_courses WHERE crse_code = '$course'";
        $result = $conn->query($sql2);
        if ($result->num_rows > 0){ //Don't delete if course found in Recommended Courses table
            $checker = false;
            $message = 'NoDelRC';
        }

        $sql3 = "SELECT * FROM will_take WHERE crse_code = '$course'";
        $result = $conn->query($sql3);
        if ($result->num_rows > 0){ //Don't delete if course found in Will Take table
            $checker = false;
            $message = 'NoDelWT';
        }

        $sql4 = "SELECT * FROM ccom_requirements WHERE req_crse_code = '$course'";
        $result = $conn->query($sql4);
        if ($result->num_rows > 0){ //Don't delete if course found in CCOM Requirements table
            $checker = false;
            $message = 'NoDelCR';
        }

        $sql5 = "SELECT * FROM general_requirements WHERE req_crse_code = '$course'";
        $result = $conn->query($sql5);
        if ($result->num_rows > 0){ //Don't delete if course found in General Requirements table
            $checker = false;
            $message = 'NoDelGR';
        }

        if((strpos($course, 'CCOM') !== false))
            $table = 'ccom_courses';
        else   
            $table = 'general_courses';

        if($checker == true)
        {
            $sql6 = "DELETE FROM $table WHERE crse_code = '$course';";
            $result = $conn->query($sql6);
            if ($result === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }
            $message = 'DelSuccess';
        }

        return $message;


    }

    public function updateGeneralCourse($conn, $old_course_id, $course_id, 
    $course_name, $credits, $type, $required)
    {
        $sql = "UPDATE general_courses
                SET crse_code = '$course_id',
                name = '$course_name',
                credits = '$credits',
                type = '$type',
                required = '$required'
                WHERE crse_code = '$old_course_id'
                ";
        
        echo "Editing course: $old_course_id";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getRequisitosModel($conn, $table, $course)
    {
        $sql = "SELECT * 
                FROM $table
                WHERE crse_code = '$course'";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function editRequisites($conn, $course, $table, $req, $oldreq, $cohort, $oldcohort, $type)
    {
        

        //Verifica si el curso requisito existe
        $sql = "SELECT crse_code
        FROM ccom_courses
        WHERE crse_code = '$req'
        UNION ALL
        SELECT crse_code
        FROM general_courses
        WHERE crse_code = '$req'";

        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows == 0)
            return 'No course';


        //Verifica si el cohorte existe
        $sql = "SELECT cohort_year
        FROM cohort
        WHERE cohort_year = $cohort";

        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows == 0)
            return 'No cohort';


        //Verifica que el requisito no existe en ese curso ya
        $sql2 = "SELECT *
        FROM $table
        WHERE crse_code = '$course'
        AND req_crse_code = '$req'
        AND cohort_year = $cohort";
        $result2 = $conn->query($sql2);
        if ($result2 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result2->num_rows > 0)
            return 'Req exist';


        //Editar los requisitos del curso entonces
        $sql3 = "UPDATE $table
                SET req_crse_code = '$req',
                type = '$type',
                cohort_year = $cohort
                WHERE crse_code = '$course'
                AND req_crse_code = '$oldreq'
                AND cohort_year = '$oldcohort'";
        $result3 = $conn->query($sql3); 

        if ($result3 === false) {
            throw new Exception("Error3 en la consulta SQL3: " . $conn->error);
        }

        return 'success';
    }


    public function deleteReq($conn, $course, $req, $cohort, $table)
    {
        $sql = "DELETE FROM $table
                WHERE crse_code = '$course'
                AND req_crse_code = '$req'
                AND cohort_year = $cohort";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return 'deleted';

    }

    public function addReq($conn, $course, $req, $cohort, $type, $table)
    {
        
        //Verifica si el curso requisito existe
        $sql = "SELECT crse_code
        FROM ccom_courses
        WHERE crse_code = '$req'
        UNION ALL
        SELECT crse_code
        FROM general_courses
        WHERE crse_code = '$req'";

        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows == 0)
            return 'No course';


        //Verifica si el cohorte existe
        $sql = "SELECT cohort_year
        FROM cohort
        WHERE cohort_year = $cohort";

        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows == 0)
            return 'No cohort';


        //Verifica que el requisito no existe en ese curso ya
        $sql2 = "SELECT *
        FROM $table
        WHERE crse_code = '$course'
        AND req_crse_code = '$req'
        AND cohort_year = $cohort";
        $result2 = $conn->query($sql2);
        if ($result2 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result2->num_rows > 0)
            return 'Req exist';


        //Si no, insertamos esta fila nueva.

        $sql3 = "INSERT INTO $table
                VALUES('$course', $cohort, '$type', '$req')";
        $result3 = $conn->query($sql3);

        if ($result3 === false)
            throw new Exception("Error3 en la consulta SQL3: " . $conn->error);

        return 'insert success';

    }
    public function selectCourseWNull($conn, $course) {
        
        $table = 'general_courses';
    
        if (strpos($course, 'CCOM') !== false) {
            $table = 'ccom_courses';
        }
    
        $sql = "SELECT *
                FROM $table 
                WHERE crse_code = '$course'";
    
        $result = $conn->query($sql);
    
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }
    
        // Verificar si se encontraron filas
        if ($result->num_rows == 0) {
            return null; // Devolver null si no se encontraron filas
        }
    
        // Obtener los datos como un arreglo asociativo
        $courseData = $result->fetch_assoc();
    
        return $courseData;
    }
}