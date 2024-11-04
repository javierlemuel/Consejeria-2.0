<?php

class AdminModel
{
    public function getAdmins($conn)
    {
        $sql = "SELECT *
                FROM advisor
                ORDER BY email ASC";

        $result = $conn->query($sql);

        if ($result === false) {
            throw new Exception("Error en la consulta SQL: " . $conn->error);
        }

        return $result;
    }

    public function registerAdmin(mysqli $conn, $email, $name, $lname, $pass, $privileges)
    {
        //Verifica que ese email de admin no exista ya
        $sql = $conn->prepare("SELECT *
                FROM advisor
                WHERE email = ?");
        $sql->execute([$email]);
        $result = $sql->get_result()->fetch_assoc();

        if ($result) {
            // Rows exist with the provided email
            return 'exist';
        }

        //Inserte el admin nuevo
        $sql = $conn->prepare("INSERT INTO advisor
                VALUES (?,?,?,?,?)");
        $result = $sql->execute([$email, $pass, $name, $lname, $privileges]);

        //Devuelva failure o success dependiendo si se pudo insertar o no
        if ($result === false) {
            return "failure";
        }

        return "success";
    }

    public function getAdmin(mysqli $conn, $email)
    {
        $sql = $conn->prepare("SELECT *
                FROM advisor
                WHERE email = ?");
        $sql->execute([$email]);
        $result = $sql->get_result()->fetch_assoc();


        if ($result)
            // Rows exist with the provided email
            return $result;

        else
            return 'failure';
    }

    public function changeAdminInfoModel(mysqli $conn, $old_email, $email, $fname, $lname, $priv, $pass)
    {
        $sql = $conn->prepare("UPDATE advisor
                SET email = ?,
                pass = ?,
                name = ?,
                last_name = ?, 
                privileges = ?
                WHERE email = ?");
        $result = $sql->execute([$email, $pass, $fname, $lname, $priv, $old_email]);

        // if ($priv == 0 || $priv == '0')
        //     $_SESSION['privileges'] = 0;

        if ($result === false) {
            return "failure";
        }

        return "success";
    }


    public function deleteAdminModel(mysqli $conn, $email)
    {
        $sql = $conn->prepare("DELETE FROM advisor
                WHERE email = ?");
        $result = $sql->execute([$email]);

        if ($result === true)
            return "success";

        return "failure";
    }
}

