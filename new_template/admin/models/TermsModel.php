<?php
class TermsModel
{

    public function getTerms($conn)
    {
        $sql = "SELECT term_id, code, year, semester, active, counseling
        FROM term
        ORDER BY code";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $terms = [];
        while ($row = $result->fetch_assoc()) {
            $terms[] = ['term_id' => $row['term_id'], 'term' => $row['code'], 
            'year' => $row['year'], 'semester' => $row['semester'], 'active' => $row['active'],
            'counseling' => $row['counseling']];
        }

        return $terms;
    }

    // funciones nuevas 
    public function createNewTerm($term, $conn) {
        $term = strtoupper($term); // converts to uppercase regardless of input format
        $year = substr($term, 0, 1); // takes the first character of the term (the letter)
        $year = intval(ord($year)) - 65; // converts it into an ASCII value and substracts 65 to make
                                        // A start at 0 then count up from there
        $year = 200 + $year; // calculates the base
        $year = $year * 10 + intval(substr($term, 1, 1));

        $year = $year . '-' . intval($year) + 1;

        $semester = intval(substr($term, 2, 1));

        switch ($semester) {
            case 1:
                $semester = "Primero";
                break;
            case 2:
                $semester = "Segundo";
                break;
            case 3:
                $semester = "Verano";
                break;
        }

        $sql = "INSERT INTO term (code, year, semester, active)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$term, $year, $semester, 0]);

        return $term;
    }

    public function updateAllTerms($conn) {
        $sql = "SELECT DISTINCT term
        FROM student_courses
        WHERE term NOT IN (
            SELECT code
            FROM term
            )
        ORDER BY term";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $this->createNewTerm($row['term'], $conn);
        }
    }

    public function setActiveTerm($term, $conn) {
        // deactivate all terms
        $sql = "UPDATE term
                SET active = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // activate selected term
        $sql = "UPDATE term
                SET active = 1
                WHERE term_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$term]);
    }

    public function getActiveTerm($conn) {
        $sql = "SELECT DISTINCT code
        FROM term
        WHERE active = 1";

        $result = $conn->query($sql);

        
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $row = $result->fetch_assoc();
        $term = $row['code'];
        return $term;
    }

    public function setCounselingTerm($term, $conn) {
        // deactivate all terms
        $sql = "UPDATE term
                SET counseling = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // activate selected term
        $sql = "UPDATE term
                SET counseling = 1
                WHERE term_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$term]);
    }

    public function getCounselingTerm($conn) {
        $sql = "SELECT DISTINCT code
        FROM term
        WHERE counseling = 1";

        $result = $conn->query($sql);

        
        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $row = $result->fetch_assoc();
        $term = $row['code'];
        return $term;
    }
}
