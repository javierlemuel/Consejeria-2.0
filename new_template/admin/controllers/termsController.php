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
        $terms = $TermModel->getTerms($conn);

        $row_number = 0;
        $year = 2018;

        $termArray = [];


        foreach ($terms as $term) {
            $array = [];

            if (substr($term, -1) === "2") {
                $year += 1;
                //$termArray['semester'] = 'Enero';
                array_push($array, ['semester' => 'Enero']);
                //array_merge($array, ['semester' => 'Enero']);
            } else {
                //$termArray['semester'] = 'Agosto';
                array_push($array, ['semester' => 'Agosto']);
                //array_merge($array, ['semester' => 'Agosto']);
            }
            array_push($array, ['term' => $term]);
            //array_merge($array, ['term' => $term]);
            array_push($array, ['year' => $year]);
            //array_merge($array, ['year' => $year]);

            $row_number += 1;

            array_push($termArray, $array);
        }



        require_once(__DIR__ . '/../views/termsView.php');
    }
}

$TermController = new TermsController();

if (isset($_GET['terms'])) {
    $TermController->index('');
}
