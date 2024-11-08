<?php
// models/LoginModel.php
session_start();
class LoginModel
{
    public function authenticateUser(mysqli $conn, $email, $password)
    {
        // Implementa la lógica de autenticación aquí
        // Por ejemplo, puedes realizar una consulta SQL para verificar las credenciales
        $email = mysqli_real_escape_string($conn, $email); // Evita inyección SQL


        $sql = $conn->prepare("SELECT email, pass, privileges FROM advisor WHERE email = ?");
        $sql->execute([$email]);
        $result = $sql->get_result()->fetch_assoc();

        if ($result) {
            $user = $result;
            if (password_verify($password, $user['pass'])) {
                // Las credenciales son correctas, el usuario está autenticado
                $privileges = $user['privileges'];
                $_SESSION['privileges'] = $privileges;
                return true;
            } else {
                // Las credenciales son incorrectas, la autenticación falló
                $_SESSION['message'] = "no admin";
                return false;
            }
        } else {
            // El email no existe en la base de datos
            $_SESSION['message'] = "no admin";
            return false;
        }
    }
}
