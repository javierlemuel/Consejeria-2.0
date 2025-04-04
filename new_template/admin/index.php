<?php
// index.php
require_once 'config/database.php';
// die("Hola");
define('__ROOT__', dirname(dirname(__FILE__)));

// Inicia o reanuda la sesión
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signout'])) {
        // Si se ha enviado el formulario de cierre de sesión
        // Destruir la sesión actual y redirigir al inicio de sesión

        session_start();
        $_SESSION = array(); // Limpiar todas las variables de sesión
        session_destroy(); // Destruir la sesión

        // Redirigir al usuario al inicio de sesión
        header("Location: index.php");
        exit;
    }
}

// Verifica si la sesión de autenticación está establecida
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    if (
        isset($_GET['lista']) || isset($_GET['classes']) || isset($_GET['ccomelectives']) ||
        isset($_GET['generalclasses']) || isset($_GET['dummyclasses']) || isset($_GET['addOffer']) 
        || isset($_GET['removeOffer']) || isset($_GET['offer'])
    ) {
        require_once 'controllers/classesController.php';
    } elseif (isset($_GET['createclass'])) {
        require_once 'controllers/createClassController.php';
    } elseif (isset($_GET['admin'])) {
        require_once 'controllers/adminController.php';
    } elseif (
        isset($_GET['class']) || isset($_GET['edit']) || isset($_GET['editReqs'])
        || isset($_GET['addReq']) || isset($_GET['removeCourse'])
    ) {
        require_once 'controllers/classController.php';
    } elseif (isset($_GET['cohort']) || isset($_GET['newcohort']) || isset($_GET['removeCohort'])) {
        require_once 'controllers/cohorteController.php';
    } elseif (isset($_GET['reports'])) {
        require_once 'controllers/reporteController.php';
    } elseif (isset($_GET['minor'])) {
        require_once 'controllers/minorController.php';
    } elseif (isset($_GET['terms']) || isset($_GET['newterm']) || isset($_GET['updateterms'])
                || isset($_GET['activateTerm']) || isset($_GET['activateCounselingTerm'])) {
        require_once 'controllers/termsController.php';
    } else {
        require_once 'controllers/expedientesController.php'; // Incluye aquí
    }
} else {
    // La sesión no está autenticada, muestra la página de inicio de sesión
    require_once 'views/loginView.php';
}
