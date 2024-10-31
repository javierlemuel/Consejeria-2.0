<?php
require_once(__DIR__ . "/../global_classes/utils.php");
class CreateClassModel
{
    public function createCcomCourse(
        mysqli $conn,
        $crse_code,
        $crse_name,
        $cred,
        $type,
        $level,
        $minor
    ) {
        // Sanitize the input and check if it is correct
        // Remove anything that is not number or letter
        $sanitized_code = preg_replace('/[^a-z0-9 ]/i', '', $crse_code);
        // Check if course is of pattern LLLLNNNN
        $crse_code_is_matched = isValidCode($sanitized_code);
        if (!$crse_code_is_matched) {
            // course code is wrong, stop
            return "El código no es formato valido.";
        }

        // Check if credit is integer
        if (!ctype_digit($cred)) {
            return "El credito no puede tener letras.";
        }

        $sql = $conn->prepare("SELECT *
                FROM ccom_courses
                WHERE crse_code = ?");

        $sql->bind_param("s", $crse_code);
        $sql->execute();
        $ccom_courses = $sql->get_result()->fetch_all();

        //Find if the course already exits
        //If it doesn't exist, create new course
        if (count($ccom_courses) == 0) {


            $sql = $conn->prepare("INSERT INTO ccom_courses
                        VALUES(?, ?, ?, ?, ?, ?)");

            $sql->bind_param("ssisss", $sanitized_code, $crse_name, $cred, $type, $level, $minor);

            $new_course = $sql->execute();
            if ($new_course === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }
        } else //return if course exists
        {
            return "Ese código de curso ya existe!!";
        }
        return;
    }

    public function createGeneralCourse(
        mysqli $conn,
        $crse_code,
        $crse_name,
        $cred,
        $type,
        $required
    ) {
        // Sanitize the input and check if it is correct
        // Remove anything that is not number or letter
        $sanitized_code = preg_replace('/[^a-z0-9 ]/i', '', $crse_code);
        // Check if course is of pattern LLLLNNNN
        $crse_code_is_matched = isValidCode($sanitized_code);

        if (!$crse_code_is_matched) {
            // course code is wrong, stop
            return "El código no es formato valido.";
        }

        $sql = $conn->prepare("SELECT crse_code
                FROM general_courses
                WHERE crse_code = ?");
        $sql->bind_param("s", $sanitized_code);
        $sql->execute();
        $general_courses = $sql->get_result()->fetch_all();
        //Find if the course already exits
        //If it doesn't exist, create new course
        if (count($general_courses) == 0) {

            $sql = $conn->prepare("INSERT INTO general_courses
                        VALUES(?,?,?,?,?)");

            $sql->bind_param("ssiss", $sanitized_code, $crse_name, $cred, $required, $type);
            $new_course = $sql->execute();
            if ($new_course === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }
        } else //return if course exists
        {
            return "Ese código de curso ya existe!!";
        }
        return;
    }

    public function getMinors($conn)
    {
        $sql = "SELECT *
                FROM minor";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }
}
