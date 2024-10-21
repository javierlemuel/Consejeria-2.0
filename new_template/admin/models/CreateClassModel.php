<?php

class CreateClassModel {
    public function createCcomCourse($conn, $crse_code, $crse_name, $cred,
    $type, $level, $minor)
    {
        $sql = "SELECT *
                FROM ccom_courses
                WHERE crse_code = '$crse_code'";
            
        $result = $conn->query($sql);

        //Find if the course already exits
        if($result)
        {
            //If it doesn't exist, create new course
            if ($result->num_rows == 0)
            {
                $sql = "INSERT INTO ccom_courses
                        VALUES('$crse_code', '$crse_name', $cred, '$type', '$level', $minor)";
                
                $result = $conn->query($sql);
                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
            }
            else //return false if course exists
            {
                return false;
            }
        }
        return $result;
    }

    public function createGeneralCourse($conn, $crse_code, $crse_name, $cred,
    $type, $required)
    {
        // Sanitize the input and check if it is correct
        // Remove anything that is not number or letter
        $sanitized_code = preg_replace( '/[^a-z0-9 ]/i', '', $crse_code);
        // Check if course is of pattern LLLLNNNN
        $crse_code_is_matched = preg_match("/^[A-Z]{4}[0-9]{4}$/", $sanitized_code); 
        if (!$crse_code_is_matched) {
            // course code is wrong, stop
            return "El código no es formato valido.";
        }

        $sql = "SELECT crse_code
                FROM general_courses
                WHERE crse_code = '$crse_code'";
            
        $result = $conn->query($sql);

        //Find if the course already exits
        if($result)
        {
            //If it doesn't exist, create new course
            if ($result->num_rows == 0)
            {
                $sql = "INSERT INTO general_courses
                        VALUES('$crse_code', '$crse_name', $cred, $required, '$type')";
                
                $result = $conn->query($sql);
                if ($result === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
            }
            else //return if course exists
            {
                return "Ese código de curso ya existe!!";
            }
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
}