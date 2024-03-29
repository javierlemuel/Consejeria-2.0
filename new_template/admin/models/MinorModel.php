<?php
class MinorModel {

   public function getMinors($conn){
        $sql = "SELECT *
        FROM minor";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;

   }

   public function editMinor($conn, $mID, $name)
   {
        
        $sql = "UPDATE minor
                SET name = '$name'
                WHERE ID = $mID";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return "edit_sucess";

   }


   public function addMinor($conn, $name)
   {
        $sql = "INSERT INTO minor (name)
                VALUES ('$name')";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return "add_sucess";

   }

   public function deleteMinorModel($conn, $minorID)
   {
        $sql = "DELETE FROM minor
                WHERE ID = $minorID";
        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        $sql2 = "UPDATE ccom_courses
                SET minor_id = NULL
                WHERE minor_id = $minorID";
        $res2 = $conn->query($sql2);
        if ($res2 === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return "del_success";

   }

   public function getMinorCourses($conn, $id)
   {
        $sql = "SELECT crse_code FROM ccom_courses
                WHERE minor_id = $id";
        $result = $conn->query($sql);

        if($result === false){
            throw new Exception("Error en la consulta SQL: ". $conn->error);
        }

        return $result;
   }
}
