<?php
if (!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true) {
    header("Location: ../index.php");
    exit;
}
require_once(__DIR__ . '/../models/TermsModel.php');
require_once(__DIR__ . '/../config/database.php');

class TermsController
{
    public function index()
    {
        global $conn;
        $TermModel = new TermsModel();
        $termArray = $TermModel->getTerms($conn);

        require_once(__DIR__ . '/../views/termsView.php');
    }
}

$TermController = new TermsController();

if (isset($_GET['terms'])) {
    $TermController->index('');
}

if (isset($_GET['newterm'])) {
    if (isset($_POST['term'])) {
        global $conn;
        $termsModel = new TermsModel();

        $term = $_POST['term'];
        $addedTerm = $termsModel->createNewTerm($term, $conn);

        $termArray = $termsModel->getTerms($conn);

        require_once(__DIR__ . '/../views/termsView.php');
    }
}

if (isset($_GET['updateterms'])) {
    $termsModel = new TermsModel();
    $termsModel->updateAllTerms($conn);
    $termArray = $termsModel->getTerms($conn);
    require_once(__DIR__ . '/../views/termsView.php');
}

if (isset($_GET['activateTerm'])) {
    $termsModel = new TermsModel();
    $term = $_GET['code'];
    $termsModel->setActiveTerm($term, $conn);
    $termArray = $termsModel->getTerms($conn);
    require_once(__DIR__ . '/../views/termsView.php');
}

if (isset($_GET['activateCounselingTerm'])) {
    $termsModel = new TermsModel();
    $term = $_GET['code'];
    $termsModel->setCounselingTerm($term, $conn);
    $termArray = $termsModel->getTerms($conn);
    require_once(__DIR__ . '/../views/termsView.php');
}