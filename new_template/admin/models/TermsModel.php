<?php
class TermsModel
{

    public function getTerms($conn)
    {
        $sql = "SELECT DISTINCT term
        FROM student_courses
        WHERE student_courses.term != 'XXX'
        ORDER BY term";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $terms = [];
        while ($row = $result->fetch_assoc()) {
            $terms[] = $row['term'];
        }

        return $terms;
    }
}
