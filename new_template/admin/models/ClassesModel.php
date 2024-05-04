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

    public function getCohorts($conn)
    {
        $sql = "SELECT DISTINCT cohort_year FROM cohort ORDER BY cohort_year;";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
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

    public function getDummyCourses($conn)
    {
        $sql = "SELECT *
                FROM dummy_courses
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getCurrentlyTakingClasses($conn, $student_num)
    {
        $sql = "SELECT 
                    sc.*,
                    CASE 
                        WHEN cc.crse_code IS NOT NULL THEN cc.name 
                        WHEN gc.crse_code IS NOT NULL THEN gc.name 
                    END AS course_name
                FROM 
                    student_courses sc
                LEFT JOIN 
                    ccom_courses cc ON sc.crse_code = cc.crse_code
                LEFT JOIN 
                    general_courses gc ON sc.crse_code = gc.crse_code
                WHERE 
                    sc.crse_status = 'M' AND student_num = '$student_num';";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }
    public function getCohortCoursesWgradesCCOM($conn, $studentCohort, $student_num)
    {
        # CCOM CONCENTRATION COURSES
        # Union of ccom student courses found in their cohort
        # With ccom student courses not found in cohort but set to mandatory category
        # And general student courses not found in cohort and set to mandatory category
        $sql = "SELECT DISTINCT cohort.crse_code, ccom_courses.name, student_courses.credits, student_courses.crse_grade,
                student_courses.equivalencia, student_courses.convalidacion, student_courses.term, student_courses.category, 
                cohort.crse_year as year, cohort.crse_semester as sem
                FROM cohort
                JOIN ccom_courses ON cohort.crse_code = ccom_courses.crse_code
                LEFT JOIN student_courses ON cohort.crse_code = student_courses.crse_code
                    AND cohort.cohort_year = $studentCohort
                    AND student_courses.student_num = $student_num
                WHERE cohort.cohort_year = $studentCohort

                UNION

                SELECT DISTINCT student_courses.crse_code, ccom_courses.name, student_courses.credits, student_courses.crse_grade,
                student_courses.equivalencia, student_courses.convalidacion, student_courses.term, student_courses.category,
                5 as year, 1 as sem
                FROM student_courses
                JOIN ccom_courses ON student_courses.crse_code = ccom_courses.crse_code
                WHERE student_courses.category = 'mandatory'
                AND student_courses.student_num = $student_num
                AND student_courses.crse_code NOT IN (
                SELECT cohort.crse_code
                FROM cohort
                WHERE cohort.cohort_year = $studentCohort
                )   

                UNION

                SELECT DISTINCT student_courses.crse_code, general_courses.name, student_courses.credits, student_courses.crse_grade,
                student_courses.equivalencia, student_courses.convalidacion, student_courses.term, student_courses.category,
                5 as year, 1 as sem
                FROM student_courses
                JOIN general_courses ON student_courses.crse_code = general_courses.crse_code
                WHERE student_courses.category = 'mandatory'
                AND student_courses.student_num = $student_num  

                ORDER BY year, sem;";

        // $sql = "SELECT cohort.crse_code as crse_code, ccom_courses.name as name, student_courses.credits as credits, student_courses.crse_grade as crse_grade,
        // student_courses.equivalencia as equivalencia, student_courses.convalidacion as convalidacion, student_courses.term as term, 
        // student_courses.category as category
        // FROM cohort
        // JOIN ccom_courses ON cohort.crse_code = ccom_courses.crse_code
        // LEFT JOIN student_courses ON cohort.crse_code = student_courses.crse_code
        // AND cohort.cohort_year = $studentCohort
        // AND student_courses.student_num = $student_num
        // WHERE cohort.cohort_year = $studentCohort
        // ORDER BY cohort.crse_code ASC;";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;

        // $result = $conn->query($sql);

        // if ($result === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // $courses = array();

        // while ($row = $result->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // $sql2 = "SELECT sc.crse_code as crse_code, ccom_courses.name as name, sc.credits as credits, sc.crse_grade as crse_grade, sc.equivalencia as equivalencia,
        //         sc.convalidacion as convalidacion, sc.term as term, sc.category as category
        //         FROM student_courses sc
        //         JOIN ccom_courses ON sc.crse_code = ccom_courses.crse_code
        //         WHERE sc.student_num = $student_num
        //             AND sc.category = 'mandatory'
        //             AND NOT EXISTS (
        //             SELECT 1
        //             FROM cohort
        //             JOIN ccom_courses ON cohort.crse_code = ccom_courses.crse_code
        //             LEFT JOIN student_courses ON cohort.crse_code = student_courses.crse_code
        //                 AND cohort.cohort_year = $studentCohort
        //                 AND student_courses.student_num = $student_num
        //             WHERE cohort.cohort_year = $studentCohort
        //                 AND sc.crse_code = cohort.crse_code)";

        // $result2 = $conn->query($sql2);

        // if ($result2 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // while ($row = $result2->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // $sql3 = "SELECT sc.crse_code as crse_code, general_courses.name as name, sc.credits as credits, sc.crse_grade as crse_grade, sc.equivalencia as equivalencia,
        //                 sc.convalidacion as convalidacion, sc.term as term, sc.category as category
        //         FROM student_courses sc
        //         JOIN general_courses ON sc.crse_code = general_courses.crse_code
        //         WHERE sc.student_num = $student_num
        //             AND sc.category = 'mandatory'";

        // $result3 = $conn->query($sql3);

        // if ($result3 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // while ($row = $result3->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // return $courses;
    }

    public function getCohortCoursesWgradesCCOMfree($conn, $studentCohort, $student_num)
    {
        # CCOM ELECTIVE COURSES
        // Preparar la consulta SQL para la actualización
        $sql0 = "SELECT minor
                FROM student
                WHERE student_num = ?";
        // Preparar la sentencia
        $stmt0 = $conn->prepare($sql0);
        if (!$stmt0) {
            // Manejar el error de preparación de la consulta
            return FALSE;
        }

        $stmt0->bind_param("s", $student_num);

        $student_minor_id = 0;
  
        // Ejecutar
        if ($stmt0->execute()) {
            // Sacar la nota
            $stmt0->bind_result($student_minor_id);
            $stmt0->fetch();

            // Cerrar
            $stmt0->close();
        } else {
            // Error
            echo "Error executing query.";
        }

        $sql1 = "SELECT DISTINCT c.crse_code, c.name, sc.credits, c.minor_id, sc.crse_grade, sc.equivalencia, sc.convalidacion, sc.term, sc.category, sc.level
                FROM ccom_courses c
                JOIN student_courses sc ON c.crse_code = sc.crse_code
                WHERE sc.student_num = $student_num
                AND (c.minor_id <> $student_minor_id OR c.minor_id IS NULL OR c.minor_id = 0)
                AND category = 'elective'
                
                UNION

                SELECT DISTINCT c.crse_code, c.name, sc.credits, NULL AS minor_id, sc.crse_grade, sc.equivalencia, sc.convalidacion, sc.term, sc.category, sc.level
                FROM general_courses c
                JOIN student_courses sc ON c.crse_code = sc.crse_code
                WHERE sc.student_num = $student_num
                AND category = 'elective'

                ORDER BY crse_code ASC;
                ";

        // $sql = "SELECT student_courses.crse_code, general_courses.name, student_courses.credits, student_courses.category,
        //                 student_courses.crse_grade, student_courses.term, student_courses.equivalencia, student_courses.convalidacion
        //         FROM student_courses
        //         JOIN general_courses ON student_courses.crse_code = general_courses.crse_code
        //         WHERE student_num = $student_num AND category = 'elective'
        //         ORDER BY crse_code ASC;";

        $result = $conn->query($sql1);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;

        // $courses = array();

        // while ($row = $result->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // $sql2 = "SELECT student_courses.crse_code, ccom_courses.name, student_courses.credits, student_courses.category,
        //                 student_courses.crse_grade, student_courses.term, student_courses.equivalencia, student_courses.convalidacion
        //         FROM student_courses
        //         JOIN ccom_courses ON student_courses.crse_code = ccom_courses.crse_code
        //         WHERE student_num = $student_num AND category = 'elective'
        //         ORDER BY crse_code ASC;";

        // $result2 = $conn->query($sql2);

        // if ($result2 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // while ($row = $result2->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // return $courses;
    }

    public function getCohortCoursesWgradesNotCCOMfree($conn, $studentCohort, $student_num)
    {
        # FREE ELECTIVE COURSES
        // Preparar la consulta SQL para la actualización
        $sql0 = "SELECT minor
                FROM student
                WHERE student_num = ?";
        // Preparar la sentencia
        $stmt0 = $conn->prepare($sql0);
        if (!$stmt0) {
            // Manejar el error de preparación de la consulta
            return FALSE;
        }

        $stmt0->bind_param("s", $student_num);

        $student_minor_id = -1;
        $fetched_student_minor_id = '';
  
        // Ejecutar
        if ($stmt0->execute()) {
            // Sacar la nota
            $stmt0->bind_result($fetched_student_minor_id);
            $stmt0->fetch();
        
            // Cerrar
            $stmt0->close();
        
            // Actualizar $student_minor_id solo si el valor es diferente de -1
            if ($fetched_student_minor_id !== 0 && $fetched_student_minor_id != NULL) {
                $student_minor_id = $fetched_student_minor_id;
            }
        }else {
            // Error
            echo "Error executing query.";
        }

        $sql = "(SELECT DISTINCT c.crse_code, c.name, sc.credits, sc.crse_grade, sc.equivalencia, sc.convalidacion, sc.term, sc.category
                FROM general_courses c
                JOIN student_courses sc ON c.crse_code = sc.crse_code
                WHERE sc.student_num = $student_num
                AND sc.category = 'free'
                )
                UNION
                (SELECT DISTINCT c.crse_code, c.name, sc.credits, sc.crse_grade, sc.equivalencia, sc.convalidacion, sc.term, sc.category
                FROM ccom_courses c
                JOIN student_courses sc ON c.crse_code = sc.crse_code
                WHERE sc.student_num = $student_num
                AND c.minor_id = $student_minor_id)
                UNION
                (SELECT DISTINCT c.crse_code, c.name, sc.credits, sc.crse_grade, sc.equivalencia, sc.convalidacion, sc.term, sc.category
                FROM ccom_courses c
                JOIN student_courses sc ON c.crse_code = sc.crse_code
                WHERE sc.student_num = $student_num
                AND sc.category = 'free'
                AND c.minor_id != $student_minor_id)
                ORDER BY crse_code ASC;";

        //         $sql = "SELECT student_courses.crse_code, general_courses.name, student_courses.credits, student_courses.category,
        //         student_courses.crse_grade, student_courses.term, student_courses.equivalencia, student_courses.convalidacion
        // FROM student_courses
        // JOIN general_courses ON student_courses.crse_code = general_courses.crse_code
        // WHERE student_num = $student_num AND category = 'free'
        // ORDER BY crse_code ASC;";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;

        // $courses = array();

        // while ($row = $result->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // $sql2 = "SELECT student_courses.crse_code, ccom_courses.name, student_courses.credits, student_courses.category,
        //                 student_courses.crse_grade, student_courses.term, student_courses.equivalencia, student_courses.convalidacion
        //         FROM student_courses
        //         JOIN ccom_courses ON student_courses.crse_code = ccom_courses.crse_code
        //         WHERE student_num = $student_num AND category = 'free'
        //         ORDER BY crse_code ASC;";

        // $result2 = $conn->query($sql2);

        // if ($result2 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // while ($row = $result2->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // return $courses;
    }

    public function getCohortCoursesWgradesNotCCOM($conn, $studentCohort, $student_num)
    {
        # GENERAL COURSES
        # Union of general student courses found in their cohort
        # General student courses not in their cohort
        # And ccom student courses that have been set to general category
        $sql = "SELECT DISTINCT cohort.crse_code, general_courses.name, student_courses.credits, student_courses.crse_grade,
                        student_courses.equivalencia, student_courses.convalidacion, student_courses.term, student_courses.category, 
                        general_courses.type as type, cohort.crse_year as year, cohort.crse_semester as sem
                        FROM cohort
                        JOIN general_courses ON cohort.crse_code = general_courses.crse_code
                        LEFT JOIN student_courses ON cohort.crse_code = student_courses.crse_code
                            AND cohort.cohort_year = $studentCohort
                            AND student_courses.student_num = $student_num
                        WHERE cohort.cohort_year = $studentCohort
                UNION
                        SELECT DISTINCT general_courses.crse_code, general_courses.name, student_courses.credits, student_courses.crse_grade,
                                student_courses.equivalencia, student_courses.convalidacion, student_courses.term, student_courses.category,
                                general_courses.type as type, 1 as year, 1 as sem
                        FROM general_courses JOIN student_courses ON general_courses.crse_code = student_courses.crse_code
                        WHERE student_courses.category = 'general'
                        AND student_num = $student_num
                        AND student_courses.crse_code not in (SELECT DISTINCT crse_code FROM cohort WHERE cohort_year = $studentCohort)
                UNION
                        SELECT DISTINCT ccom_courses.crse_code, ccom_courses.name, student_courses.credits, student_courses.crse_grade,
                                student_courses.equivalencia, student_courses.convalidacion, student_courses.term, student_courses.category,
                                ccom_courses.type as type, 1 as year, 1 as sem
                        FROM ccom_courses JOIN student_courses ON ccom_courses.crse_code = student_courses.crse_code
                        WHERE student_courses.category = 'general'
                        AND student_num = $student_num
                        
                ORDER BY type, year, sem;";

        $result = $conn->query($sql);
        $_SESSION['CISO_credits'] = 0;
        $_SESSION['HUMA_credits'] = 0;

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $crse_code = $row['crse_code'];

                $sql2 = "SELECT sum(credits) AS sum
                FROM general_courses
                WHERE crse_code = '$crse_code'
                AND type = 'CISO'";
                $result2 = $conn->query($sql2);

                if ($result2 === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
                else if ($result2->num_rows > 0) {
                    while ($row = $result2->fetch_assoc()) {
                        // Add CISO credit count to session
                        $_SESSION['CISO_credits'] += $row['sum'];
                    }
                }

                $sql3 = "SELECT sum(credits) AS sum
                FROM general_courses
                WHERE crse_code = '$crse_code'
                AND type = 'HUMA'";
                $result3 = $conn->query($sql3);

                if ($result3 === false) {
                    throw new Exception("Error en la consulta SQL: " . $conn->error);
                }
                else if ($result3->num_rows > 0) {
                    while ($row = $result3->fetch_assoc()) {
                        // Add HUMA credit count to session
                        $_SESSION['HUMA_credits'] += $row['sum'];
                    }
                }
            }
        }
        
        return $result;

        // $sql = "SELECT cohort.crse_code as crse_code, general_courses.name as name, student_courses.credits as credits, student_courses.crse_grade as crse_grade,
        //                 student_courses.equivalencia as equivalencia, student_courses.convalidacion as convalidacion, student_courses.term as term, 
        //                 student_courses.category as category
        //         FROM cohort
        //         JOIN general_courses ON cohort.crse_code = general_courses.crse_code
        //         LEFT JOIN student_courses ON cohort.crse_code = student_courses.crse_code
        //             AND cohort.cohort_year = $studentCohort
        //             AND student_courses.student_num = $student_num
        //         WHERE cohort.cohort_year = $studentCohort
        //         ORDER BY cohort.crse_code ASC;";

        // $courses = array();

        // while ($row = $result->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // $sql2 = "SELECT sc.crse_code as crse_code, general_courses.name as name, sc.credits as credits, sc.crse_grade as crse_grade, sc.equivalencia as equivalencia,
        //         sc.convalidacion as convalidacion, sc.term as term, sc.category as category
        //         FROM student_courses sc
        //         JOIN general_courses ON sc.crse_code = general_courses.crse_code
        //         WHERE sc.student_num = $student_num
        //             AND sc.category = 'general'
        //             AND NOT EXISTS (
        //             SELECT 1
        //             FROM cohort
        //             JOIN general_courses ON cohort.crse_code = general_courses.crse_code
        //             LEFT JOIN student_courses ON cohort.crse_code = student_courses.crse_code
        //                 AND cohort.cohort_year = $studentCohort
        //                 AND student_courses.student_num = $student_num
        //             WHERE cohort.cohort_year = $studentCohort
        //                 AND sc.crse_code = cohort.crse_code)";

        // $result2 = $conn->query($sql2);

        // if ($result2 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // while ($row = $result2->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // $sql3 = "SELECT sc.crse_code as crse_code, ccom_courses.name as name, sc.credits as credits, sc.crse_grade as crse_grade, sc.equivalencia as equivalencia,
        //                 sc.convalidacion as convalidacion, sc.term as term, sc.category as category
        //         FROM student_courses sc
        //         JOIN ccom_courses ON sc.crse_code = ccom_courses.crse_code
        //         WHERE sc.student_num = $student_num
        //             AND sc.category = 'general'";

        // $result3 = $conn->query($sql3);

        // if ($result3 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        // while ($row = $result3->fetch_assoc()) {
        //     $courses[] = $row;
        // }

        // return $courses;
    }

    public function getAllOtherCoursesWgrades($conn, $student_num)
    {
        $sql = "SELECT student_courses.*, 'no' as db, '' as name
                FROM student_courses
                WHERE crse_code NOT IN (
                    SELECT crse_code
                    FROM ccom_courses
                    UNION
                    SELECT crse_code
                    FROM general_courses
                )
                AND student_num = $student_num

                UNION

                SELECT sc.*, 'yes' as db, cc.name as name
                FROM student_courses as sc JOIN ccom_courses as cc ON sc.crse_code = cc.crse_code
                WHERE sc.student_num = $student_num
                AND category = 'other'

                UNION

                SELECT sc.*, 'yes' as db, gc.name as name
                FROM student_courses as sc JOIN general_courses as gc ON sc.crse_code = gc.crse_code
                WHERE sc.student_num = $student_num
                AND category = 'other'
                ";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function getOfferCourses($conn, $termA)
    {
        $sql = "SELECT *
                FROM offer
                WHERE crse_code != 'XXXX'
                AND term = '$termA'
                ORDER BY crse_code ASC";

        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $courses = [];

        while ($row = $result->fetch_assoc()) {
            $code = $row['crse_code'];
            $term = $row['term'];
            $sql2 = "SELECT crse_code, credits, name, type
                    FROM ccom_courses
                    WHERE crse_code = '$code'
                    UNION ALL
                    SELECT crse_code, credits, name, type
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
        $term = $this->getTerm($conn);
        $sql = "SELECT term
                FROM offer
                WHERE crse_code = '$courseID'
                AND term = '$term'";
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
        $term = $this->getTerm($conn);
        $sql = "DELETE FROM offer
                WHERE crse_code = '$courseID'
                AND term = '$term'";
        $result = $conn->query($sql);

        echo "ENTERED FUNCTION";
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return 'DelOfferSuccess';
    }

    public function setNewTerm($conn, $term)
    {
        //Estamos seteando un nuevo semestre de consejeria

        //Borramos los cursos en oferta del semestre pasado
        // $sql = "DELETE FROM offer
        //         WHERE term != ''";
        // $result = $conn->query($sql);
        // if ($result === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }

        //Borramos los edit flags de los estudiantes
        $sql2 = "UPDATE student
                SET edited_date = NULL
                WHERE edited_date is not NULL";
        $result2 = $conn->query($sql2);
        if ($result2 === false) {
            throw new Exception("Error en la consulta SQL KHE: " . $conn->error);
        }

        //Borramos los cursos que los estudiantes escogieron en sus consejerias
        // $sql4 = "DELETE FROM will_take
        //         WHERE crse_code != ''";
        // $result4 = $conn->query($sql4);
        // if ($result4 === false) {
        //     throw new Exception("Error en la consulta SQL: " . $conn->error);
        // }     

        //Insertamos el nuevo term/semestre con el curso 'dummy' XXXX, como row de titulo del term
        $sql5 = "UPDATE offer
                SET term = '$term'
                WHERE crse_code = 'XXXX'";
        $result5 = $conn->query($sql5);
        if ($result5 === false) {   
            throw new Exception("Error2 en la consulta SQL:". $conn->error);
        }

        $sqli = "INSERT INTO OFFER
                VALUES('CCOM3001', '$term'), ('CCOM3002', '$term')";

        $resulti = $conn->query($sqli);
        if($resulti === false)
            throw new Exception("Error Insert Term en la consulta SQL: " . $conn->error);

        return $resulti;
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

        $term = '';
        
        while ($row = $result->fetch_assoc()) {
            $term = $row['term'];
            break;
        }

        return $term;

    }

    public function getTerms($conn)
   {
        $sql = "SELECT DISTINCT term
                FROM offer";

        $result = $conn->query($sql);

        if($result === false)
            throw new Exception("Error en la consulta SQL: ". $conn->error);

        return $result;
   }

    public function getMatriculadosModel($conn, $course)
    {
        $term = $this->getTerm($conn);
        $sql = "SELECT count(student_num) AS count
                FROM will_take
                WHERE crse_code = '$course'
                AND term = '$term'";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL HELLO: " . $conn->error);
        }


        if ($result->num_rows == 0)
            return 0;

        while ($row = $result->fetch_assoc()) {
            $count = $row['count'];
            break;
        }

        return $count;

    }


    public function getStudentsMatriculadosModel($conn, $course)
    {
        $term = $this->getTerm($conn);
        $sql = "SELECT *
                FROM will_take NATURAL JOIN student
                WHERE will_take.student_num = student.student_num AND
                crse_code = '$course'
                AND term = '$term'";
        $result = $conn->query($sql);
        if ($result === false) {
            throw new Exception("Error en la consulta SQL HELLO: " . $conn->error);
        }


        return $result;

    }
}
