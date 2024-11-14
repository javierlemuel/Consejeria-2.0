<?php
// models/LoginModel.php
class StudentModel
{
    public function getStudentInfo($conn, $student_num)
    {
        $sql = "SELECT DISTINCT  email, name1, name2, last_name1, last_name2, cohort_year, minor
                FROM student
                WHERE student.student_num = ?";

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentInfo = $result->fetch_assoc();
        if ($studentInfo['name2'] != null) {
            $studentName = $studentInfo['name1'] . " " . $studentInfo['name2'] . " " . $studentInfo['last_name1'] . " " . $studentInfo['last_name2'];
        } else
            $studentName = $studentInfo['name1'] . " " . $studentInfo['last_name1'] . " " . $studentInfo['last_name2'];

        $studentInfo['full_student_name'] = $studentName;
        $formatted_student_num = substr($student_num, 0, 3) . '-' . substr($student_num, 3, 2) . '-' . substr($student_num, 5);
        $studentInfo['formatted_student_num'] = $formatted_student_num;

        return $studentInfo;
    }

    public function getStudentCCOMCourses($conn, $student_num, $cohort_year)
    {
        $sql = "SELECT DISTINCT ccom_courses.crse_code, ccom_courses.name, ccom_courses.credits, student_courses.crse_grade, student_courses.crse_status, 
                student_courses.convalidacion, student_courses.equivalencia,  student_courses.term, ccom_courses.type,
                cohort.cohort_year,
                CASE WHEN ccom_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' ELSE NULL END AS recommended

        FROM ccom_courses
        LEFT JOIN student_courses ON ccom_courses.crse_code = student_courses.crse_code
                AND student_courses.student_num = ?
        LEFT JOIN cohort ON ccom_courses.crse_code = cohort.crse_code
        WHERE ccom_courses.type = 'mandatory' AND cohort.cohort_year = ?
        ORDER BY ccom_courses.crse_code ASC;";

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("sss", $student_num, $student_num, $cohort_year);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentRecord = [];
        while ($row = $result->fetch_assoc()) {
            $studentRecord[] = $row;
        }
        return $studentRecord;
    }

    public function getStudentGeneralCourses($conn, $student_num, $cohort_year)
    {
        // $sql = "SELECT general_courses.crse_code, general_courses.name, general_courses.credits, student_courses.crse_grade, student_courses.crse_status, 
        //                 student_courses.convalidacion, student_courses.equivalencia,  student_courses.term, general_courses.type,
        //         CASE WHEN general_courses.crse_code IN (SELECT crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' ELSE NULL END AS recommended

        //         FROM general_courses
        //         LEFT JOIN student_courses ON general_courses.crse_code = student_courses.crse_code
        //         AND student_courses.student_num = ?
        //         JOIN cohort on cohort.crse_code = general_courses.crse_code
        //         WHERE cohort.cohort_year = ? AND general_courses.crse_code NOT IN ('INGL3113', 'INGL3114');";

        $sql = "SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'INGL'

            UNION

            SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'ESPA'

            UNION

            SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'MATE'

            UNION

            SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'HUMA'

            UNION

            SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'CISO'

            UNION

            SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'CIBI'

            UNION

            SELECT DISTINCT 
            student_courses.crse_code, 
            general_courses.name, 
            student_courses.credits, 
            student_courses.crse_grade, 
            student_courses.crse_status, 
            student_courses.convalidacion, 
            student_courses.equivalencia, 
            student_courses.term, 
            general_courses.type,
            CASE 
            WHEN general_courses.crse_code IN (SELECT DISTINCT  crse_code FROM recommended_courses WHERE student_num = ?) THEN 'Prox. Sem' 
            ELSE NULL 
            END AS recommended
            FROM student_courses
            JOIN general_courses on general_courses.crse_code = student_courses.crse_code
            WHERE student_courses.student_num = ? AND type = 'FISI'
            ORDER BY type, crse_code;";

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param('ssssssssssssss', $student_num, $student_num, $student_num, $student_num, $student_num, $student_num, $student_num,
        $student_num, $student_num, $student_num, $student_num, $student_num, $student_num, $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentRecord = [];
        while ($row = $result->fetch_assoc()) {
            $studentRecord[] = $row;
        }
        return $studentRecord;
    }

    public function getCCOMElectives($conn, $student_num, $minor)
    {
        if ($minor == 0) {
            $minor_id =  "";
        } else {
            $minor_id =  "AND (cc.minor_id != " . $minor . " OR cc.minor_id IS NULL)";
        }

        $sql = "SELECT DISTINCT cc.crse_code, cc.name, cc.credits, sc.crse_grade, sc.term, sc.equivalencia, sc.convalidacion
        FROM ccom_courses AS cc
        JOIN student_courses AS sc
        ON cc.crse_code = sc.crse_code
        WHERE sc.category = 'elective' AND sc.student_num = ? " . $minor_id;

        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentRecord = [];
        while ($row = $result->fetch_assoc()) {
            $studentRecord[] = $row;
        }
        return $studentRecord;
    }

    public function getFREElectives($conn, $student_num, $minor)
    {
        if ($minor == 0) {
            $minor_id =  "";
        } else {
            $minor_id =  "AND cc.minor_id = " . $minor;
        }

        $sql = "SELECT DISTINCT  sc.crse_code, sc.crse_grade, sc.term, sc.equivalencia, sc.convalidacion, cc.name AS name, cc.credits AS credits
        FROM student_courses AS sc
        RIGHT JOIN ccom_courses AS cc ON sc.crse_code = cc.crse_code
        WHERE sc.student_num = ? AND sc.category = 'free'  $minor_id  UNION
		SELECT DISTINCT  sc.crse_code, sc.crse_grade, sc.term, sc.equivalencia, sc.convalidacion, gc.name AS name, gc.credits AS credits
        FROM student_courses AS sc
        JOIN general_courses AS gc ON sc.crse_code = gc.crse_code
        WHERE sc.student_num = ? AND sc.category = 'free'";


        $stmt = $conn->prepare($sql);

        // sustituye el ? por el valor de $student_num
        $stmt->bind_param("ss", $student_num, $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $studentRecord = [];
        while ($row = $result->fetch_assoc()) {
            $studentRecord[] = $row;
        }
        return $studentRecord;
    }


    public function getOtherCourses($conn, $student_num)
    {
        $sql = "SELECT DISTINCT  *
        FROM student_courses
        WHERE crse_code NOT IN (
            SELECT DISTINCT  crse_code
            FROM ccom_courses
            UNION
            SELECT DISTINCT crse_code
            FROM general_courses
        )
        AND student_num = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $otherCourses = [];
        while ($row = $result->fetch_assoc()) {
            $otherCourses[] = $row;
        }
        return $otherCourses;
    }

    public function getMinor($conn, $student_num)
    {
        $sql = "SELECT DISTINCT minor.name
        FROM minor
        JOIN student AS s ON minor.ID = s.minor
        WHERE s.student_num = ?;";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $student_num);

        // ejecuta el statement
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $minor = $result->fetch_assoc();
        return $minor;
    }
}
