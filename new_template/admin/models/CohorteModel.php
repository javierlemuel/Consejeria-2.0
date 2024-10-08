<?php

class CohorteModel {

    public function getCohorteYears($conn){
        $sql = "SELECT DISTINCT cohort_year
                FROM cohort";

        $result = $conn->query($sql); 
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }
    public function getFirstSem($conn, $cohort, $year)
    {
        $sql = "SELECT * FROM(
                SELECT crse_code, name, credits, type
                FROM cohort NATURAL JOIN ccom_courses
                WHERE cohort.crse_code = ccom_courses.crse_code
                AND cohort_year = $cohort
                AND crse_year = $year
                AND crse_semester = 1
                UNION ALL 
                SELECT crse_code, name, credits, type
                FROM cohort NATURAL JOIN general_courses
                WHERE cohort.crse_code = general_courses.crse_code
                AND cohort_year = $cohort
                AND crse_year = $year
                AND crse_semester = 1) AS results
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getSecondSem($conn, $cohort, $year)
    {
        $sql = "SELECT * FROM(
                SELECT crse_code, name, credits, type
                FROM cohort NATURAL JOIN ccom_courses
                WHERE cohort.crse_code = ccom_courses.crse_code
                AND cohort_year = $cohort
                AND crse_year = $year
                AND crse_semester = 2
                UNION ALL 
                SELECT crse_code, name, credits, type 
                FROM cohort NATURAL JOIN general_courses
                WHERE cohort.crse_code = general_courses.crse_code
                AND cohort_year = $cohort
                AND crse_year = $year
                AND crse_semester = 2) AS results
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function removeFromCohorteModel($conn, $cohort, $courseID)
    {
        $sql = "DELETE FROM cohort
                WHERE cohort_year = $cohort
                AND crse_code = '$courseID'";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;

    }

    public function addToCohorteModel($conn, $cohort, $course, $year, $semester, $type)
    {
        //Verifica que no exista ya en el cohorte

        $sql = "SELECT * 
                FROM cohort
                WHERE cohort_year = $cohort
                AND crse_code = '$course'";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0)
            return "exists in cohort";

        

        //Verifica que el curso exista en la base de datos 

        if ($type == 'CCOM')
            $table = 'ccom_courses';
        else 
            $table = 'general_courses';

        $sql2 = "SELECT * 
                FROM $table
                WHERE crse_code = '$course'";
        $result2 = $conn->query($sql2);
        if ($result2 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result2->num_rows == 0)
            return "doesn't exist in db";


        // Si no hay de estos errores, inserta en cohorte

        $sql3 = "INSERT INTO cohort
                VALUES($cohort, '$course', $year, $semester)";
        $result3 = $conn->query($sql3);
        if ($result3 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return "success";
    }


    public function getCohorteReqModel($conn, $cohort)
    {
        $sql = "SELECT *
                FROM cohort_requirements
                WHERE cohort_year = $cohort";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }
        return $result;

    }

    public function editCohorteReqModel($conn, $cohort, $dept, $libre, $huma, $ciso, $int, $avz)
    {
        // Verificar si ya exist en la tabla de Reqs o no

        $sql = "SELECT * 
                FROM cohort_requirements
                WHERE cohort_year = $cohort";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows == 0) //El cohorte no tiene record de requisitos
        {
            $sql2 = "INSERT INTO cohort_requirements
                    VALUES($cohort, $huma, $ciso, $dept, $int, $libre, $avz)";
            $result2 = $conn->query($sql2);
            if ($result2 === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }
        }
        else //Si tiene record de requisitos
        {
            $sql2 = "UPDATE cohort_requirements
                    SET credits_huma = $huma,
                    credits_ciso = $ciso,
                    credits_dept = $dept,
                    credits_int = $int,
                    credits_free = $libre,
                    credits_avz = $avz
                    WHERE cohort_year = $cohort";
            $result2 = $conn->query($sql2);
            if ($result2 === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }
        }

        return $result2;
    }


    public function createCohorte($conn, $cohort, $copy){
        // Verifica que no exista ya el cohorte

        $sql = "SELECT * 
                FROM cohort
                WHERE cohort_year = $cohort";
        $result = $conn->query($sql);  
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows == 0) //No existe todavia el cohorte. Great!
        {
            //Si vamos a copiar el cohorte pasado al cohorte nuevo
            if($copy == 'si')
            {
                //Busca el cohorte pasado
                $sql = "SELECT cohort_year
                        FROM cohort
                        ORDER BY cohort_year DESC
                        LIMIT 1";
                $result = $conn->query($sql);
                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
                foreach ($result as $res)
                    $past_year = $res['cohort_year'];

                //Busca la data del cohorte pasado
                $sql2 = "SELECT * 
                        FROM cohort
                        WHERE cohort_year = $past_year";
                $result = $conn->query($sql2);
                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }

                //Cambia el cohorte al nuevo que se va a crear e inserta en DB
                foreach ($result as $res)
                {
                    $sql3 = "INSERT INTO cohort (cohort_year, crse_code, crse_year, crse_semester) 
                            VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql3);
                    $stmt->bind_param("ssis", $cohort, $res['crse_code'], 
                                        $res['crse_year'], $res['crse_semester']);
                    $result2 = $stmt->execute();
                    if ($result2 === false) {
                        throw new Exception("Error2 en la consulta SQL: " . $conn->error);
                    }
                
                }



            }
            else{
                $sql2 = "INSERT INTO cohort
                        VALUES($cohort, 'CCOM3001', 1, 1)"; //Inicializa ese cohorte nuevo con CCOM3001
                $result2 = $conn->query($sql2);
                if ($result2 === false) {
                    throw new Exception("Error2 en la consulta SQL: " . $conn->error);
                }
            }

            return 'success';
        }

        return 'failure';


    }

    public function deleteCohorteModel($conn, $cohort)
    {
        $checker = true;

        $sql1 = "SELECT * FROM student WHERE cohort_year = $cohort";
        $result = $conn->query($sql1);
        if ($result->num_rows > 0){ //Don't delete if cohort found in Students table
            $checker = false;
            $message = 'NoDelS';
        }

        $sql2 = "SELECT * FROM ccom_requirements WHERE cohort_year = $cohort";
        $result = $conn->query($sql2);
        if ($result->num_rows > 0){ //Don't delete if cohort found in CCOM Requirements table
            $checker = false;
            $message = 'NoDelCR';
        }

        $sql3 = "SELECT * FROM general_requirements WHERE cohort_year = $cohort";
        $result = $conn->query($sql3);
        if ($result->num_rows > 0){ //Don't delete if cohort found in General Requirements table
            $checker = false;
            $message = 'NoDelGR';
        }

        if($checker == true)
        {
            $sql4 = "DELETE FROM cohort WHERE cohort_year = $cohort";
            $result = $conn->query($sql4);
            if ($result === false) {
                throw new Exception("Error2 en la consulta SQL: " . $conn->error);
            }
            $message = 'DelSuccess';
        }

        return $message;
    }
}