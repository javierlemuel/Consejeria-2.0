<?php
if (!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] !== true) {
    header("Location: ../index.php");
    exit;
}

$privileges = isset($_SESSION['privileges']) ? $_SESSION['privileges'] : null;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Consejeria UPRA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/perfect-scrollbar.min.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css" />
    <link defer rel="stylesheet" type="text/css" media="screen" href="assets/css/animate.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="assets/css/estilos.css" />
    <script src="assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="assets/js/popper.min.js"></script>
    <script defer src="assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="assets/js/sweetalert.min.js"></script>
    <script defer src="assets/js/sidebar.js"></script>
</head>

<body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">
    <!-- sidebar menu overlay -->
    <div x-cloak class="fixed inset-0 z-50 bg-[black]/60 lg:hidden" :class="{'hidden' : !$store.app.sidebar}" @click="$store.app.toggleSidebar()"></div>

    <!-- screen loader -->
    <div class="screen_loader animate__animated fixed inset-0 z-[60] grid place-content-center bg-[#fafafa] dark:bg-[#060818]">
        <svg width="64" height="64" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4361ee">
            <path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
                <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="-360 67 67" dur="2.5s" repeatCount="indefinite" />
            </path>
            <path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
                <animateTransform attributeName="transform" type="rotate" from="0 67 67" to="360 67 67" dur="8s" repeatCount="indefinite" />
            </path>
        </svg>
    </div>

    <!-- scroll to top button -->
    <div class="fixed bottom-6 z-50 ltr:right-6 rtl:left-6" x-data="scrollToTop">
        <template x-if="showTopButton">
            <button type="button" class="btn btn-outline-primary animate-pulse rounded-full bg-[#fafafa] p-2 dark:bg-[#060818] dark:hover:bg-primary" @click="goToTop">
                <svg width="24" height="24" class="h-4 w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd" d="M12 20.75C12.4142 20.75 12.75 20.4142 12.75 20L12.75 10.75L11.25 10.75L11.25 20C11.25 20.4142 11.5858 20.75 12 20.75Z" fill="currentColor" />
                    <path d="M6.00002 10.75C5.69667 10.75 5.4232 10.5673 5.30711 10.287C5.19103 10.0068 5.25519 9.68417 5.46969 9.46967L11.4697 3.46967C11.6103 3.32902 11.8011 3.25 12 3.25C12.1989 3.25 12.3897 3.32902 12.5304 3.46967L18.5304 9.46967C18.7449 9.68417 18.809 10.0068 18.6929 10.287C18.5768 10.5673 18.3034 10.75 18 10.75L6.00002 10.75Z" fill="currentColor" />
                </svg>
            </button>
        </template>
    </div>

    <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">
        <!-- start sidebar section -->
        <div id="sidebar"></div>
        <!-- end sidebar section -->

        <div class="main-content flex flex-col min-h-screen">
            <!-- start header section -->
            <header class="z-40" :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
                <div class="shadow-sm">
                    <div class="relative flex w-full items-center" style="background-color: #2b2b2b; padding: 5px 5px; dark:bg-[#0e1726]">
                        <div class="horizontal-logo flex items-center justify-between ltr:mr-2 rtl:ml-2 lg:hidden">
                            <a href="index.php" class="main-logo flex shrink-0 items-center">
                                <img class="inline w-8 ltr:-ml-1 rtl:-mr-1" src="assets/images/university.png" alt="image" />
                                <span class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 text-white dark:text-white-light md:inline">CONSEJERIA UPRA</span>
                            </a>

                            <a href="javascript:;" class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden" @click="$store.app.toggleSidebar()">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            </a>
                        </div>
                        <div x-data="header" class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                            <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }" @click.outside="search = false">
                            </div>
                            <div class="dropdown flex-shrink-0" x-data="dropdown" @click.outside="open = false">
                                <a href="javascript:;" class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60" @click="toggle()">
                                    <span> <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                            <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                            <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </span>
                                </a>
                                <!-- user-profile -->
                                <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="top-11 w-[230px] !py-0 font-semibold text-dark ltr:right-0 rtl:left-0 dark:text-white-dark dark:text-white-light/90">
                                    <li class="border-t border-white-light dark:border-white-light/10">
                                        <form method="post" action="index.php">
                                            <input type="hidden" name="signout" value="1">
                                            <button type="submit" class="!py-3 text-danger">
                                                <svg class="h-4.5 w-4.5 rotate-90 ltr:mr-2 rtl:ml-2" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path opacity="0.5" d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                Sign Out
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </ul>
                </div>
            </header>
            <!-- end header section -->

            <div class="animate__animated p-6" :class="[$store.app.animation]">
                <!-- start main content section -->
                <div class="flex-1 text-sm ">
                    <div style='text-align: center'>
                        <?php
                        if (isset($_SESSION['consejeria_msg'])) {
                            if (strpos($_SESSION['consejeria_msg'], 'No') !== false)
                                echo "<h2 style='color:red; bold' class='text-xl'>" . $_SESSION['consejeria_msg'] . "</h2>";
                            else
                                echo "<h2 style='color:limegreen; bold' class='text-xl'>" . $_SESSION['consejeria_msg'] . "</h2>";
                            unset($_SESSION['consejeria_msg']);
                        }
                        ?>
                    </div>
                    <div>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em;">Nombre: <?php echo $studentData['name1'] . " " . $studentData['last_name1'] . " " . $studentData['last_name2']; ?></h2>
                        <!-- formateo de nuermo de estudiante-->
                        <?php
                        $studentNum = $studentData['student_num'];
                        $formattedStudentNum = substr($studentNum, 0, 3) . '-' . substr($studentNum, 3, 2) . '-' . substr($studentNum, 5);
                        ?>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 1em;">Número de estudiante: <?php echo $formattedStudentNum; ?></h2>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 1em;">Correo electrónico: <?php echo $studentData['email']; ?></h2>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 1em;">Cohorte: <?php echo $studentData['cohort_year']; ?></h2>
                        <?php
                        if ($studentHaveMinor != NULL) {
                            echo '<h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; margin-top: 1em; margin-bottom: 1em;">Concentración Menor: ' . $studentHaveMinor . '</h2>';
                        }
                        ?>
                        <form id="myForm" method="POST" action="index.php">
                            <input type="hidden" name="action" value="selecteStudent">
                            <input type="hidden" name="student_num" value="<?= $studentNum ?>">
                            <a href="#" onclick="document.getElementById('myForm').submit();" class='btn btn-primary ltr:ml-2 rtl:mr-2' style="width: 10vw; text-align: center">Editar Estudiante</a>
                        </form>
                        <div class="flex justify-center items-center h-screen">
                            <div class="w-1/3">
                                <form id="termForm" action="index.php" method="POST"> <!-- Este form utiliza JAVASCRIPT para enviar los valores -->
                                    <input type="hidden" name="action" value="studentCounseling">
                                    <?php if ($studentRecommendedTerms === null || empty($studentRecommendedTerms)): ?>
                                        <h2 class="text-center">El estudiante aún no tiene ninguna recomendación en ningún semestre.</h2>
                                    <?php else: ?>
                                        <div>
                                            <label for="estatus" class="block text-center text-lg">Escoge el term para ver las recomendaciones</label>
                                            <select id="termSelect" class="form-select text-white-dark w-full" name="estatus" x-data="dropdown" @click.outside="open = false">
                                                <option value=""></option>
                                                <?php foreach ($studentRecommendedTerms as $term): ?>
                                                    <option value="<?php echo $term ?>"><?php echo $term ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="hidden" name="student_num" value="<?= $studentData['student_num'] ?>">
                                            <input type="hidden" id="selectedTerm" name="selectedTerm">
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                        <!-- tabla de los cursos recomendados -->
                        <?php
                        // Verificar si la variable $studentRecommendedClasses no es NULL y no está vacía
                        if ($studentRecommendedClasses !== NULL && !empty($studentRecommendedClasses)) {
                        ?>
                            <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Cursos Recomendados en el Semestre: <?php echo $selectedTerm ?></h2>
                            <!-- tabla de los cursos recomendados -->
                            <div class="table-responsive">
                                <table style="font-size: 12px; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <th style="padding: 5px;">Código Del Curso</th>
                                            <th style="padding: 5px;">Nombre</th>
                                            <th style="padding: 5px;">Créditos</th>
                                            <th style="padding: 5px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                        foreach ($studentRecommendedClasses as $recomendedCourse) {
                                            echo "<tr>";
                                            echo "<form method='POST' action='index.php'>";
                                            echo "<input type='hidden' name='deleteRecomendation' value='deleteRecomendation'>";
                                            echo "<td style='padding: 5px;'>" . $recomendedCourse['crse_code'] . "</td>";
                                            echo "<input type='hidden' name='crse_code' value='" . $recomendedCourse['crse_code'] . "'/>";
                                            echo "<td style='padding: 5px;'>" . $recomendedCourse['name'] . "</td>";
                                            echo "<td style='padding: 5px;'>" . $recomendedCourse['credits'] . "</td>";
                                            echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                            echo "<input type='hidden' name='selectedTerm' value=" . $selectedTerm . ">";
                                            if ($privileges == 1) {
                                                echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2' style='background-color: #fc0345;'>Eliminar</button></td>";
                                            }
                                            echo "</form>";
                                            echo "</tr>";
                                        }

                                        ?>

                                    </tbody>
                                </table>
                                <form method='POST' action='index.php'>
                                    <input type='hidden' name='student_num' value=<?= $studentData['student_num'] ?>>
                                    <input type='hidden' name='deleteAllRecommendationsforOneStudent' value='deleteAllRecommendationsforOneStudent'>
                                    <button type='submit' name='action' value='deleteAllRecommendationsforOneStudent' class='btn btn-primary ltr:ml-2 rtl:mr-2' style='background-color: #fc0345; margin-top: 5vh; float: right;'>Eliminar todas</button>
                                </form>
                                <form method='POST' action='index.php'>
                                    <input type='hidden' name='confirmRecomendation' value='confirmRecomendation'>
                                    <input type='hidden' name='student_num' value=<?= $studentData['student_num'] ?>>
                                    <button type='submit' name='action' value='confirmCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2' style='background-color: #229944; margin-top: 5vh;'>Confirmar</button>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- Añadir Calificacion para el estuidante -->
                        <?php if ($privileges == 1) { ?>
                            <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Añadir Calificación</h2>
                            <h2 class="m-0 dark:text-white-dark" style="font-size: 1em; text-align: center; margin-top: 1em; margin-bottom: 1em;">Si la clase existe en la base de datos no tiene que proveer los créditos. Si no se provee el semestre se inserta con el semestre actual.</h2>
                            <div class="table-responsive">
                                <table style="font-size: 12px; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <th style="padding: 5px;"></th>
                                            <th style="padding: 5px;">Código Del Curso</th>
                                            <th style="padding: 5px;">Créditos</th>
                                            <th style="padding: 5px;">Categoría</th>
                                            <th style="padding: 5px;">Nota</th>
                                            <th style="padding: 5px;">Estatus</th>
                                            <th style="padding: 5px;">Semestre</th>
                                            <th style="padding: 5px;">Equivalencia</th>
                                            <th style="padding: 5px;">Convalidación</th>
                                            <th style="padding: 5px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <form method='POST' action='index.php'>
                                                <td></td>
                                                <td style='padding: 5px;'><input type='text' name='crse_code' class='form-input' value='' required></td>
                                                <td style='padding: 5px;'><input type='text' name='credits' class='form-input' style='width: 4em;' value=''></td>
                                                <td style='padding: 5px;'>
                                                    <select name='category' class='form-input'>
                                                        <option value=''></option>
                                                        <option value='mandatory'>Concentración CCOM</option>
                                                        <option value='elective'>Electiva CCOM</option>
                                                        <option value='general'>General</option>
                                                        <option value='free'>Electiva Libre</option>
                                                        <option value='other'>Otras Clases</option>
                                                    </select>
                                                </td>
                                                <td style='padding: 5px;'><input type='text' name='grade' class='form-input' style='width: 4em;' value=''></td>
                                                <td style='padding: 5px;'>
                                                    <select name='status' class='form-input'>
                                                        <option value=''></option>
                                                        <option value='M'>Actualmente Tomando</option>
                                                    </select>
                                                </td>
                                                <td style='padding: 5px;'><input type='text' name='term' class='form-input' style='width: 4.5em;' value=''></td>
                                                <td style='padding: 5px;'><input type='text' name='equivalencia' class='form-input' value=''/></td>
                                                <td style='padding: 5px;'><input type='text' name='convalidacion' class='form-input' value=''/></td>
                                                <input type='hidden' name='student_num' value="<?php echo $studentData['student_num']; ?>">
                                                <input type='hidden' name='insertGrade' value='insertGrade'>
                                                <td style='padding: 5px;'><button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Añadir</button></td>
                                            </form>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Clases Que Toma Actualmente</h2>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="student_num" value="<?= $studentData['student_num'] ?>">
                            <!-- basic table -->
                            <div class="table-responsive">
                                <table style="font-size: 12px; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <th style="padding: 5px;">Código Del Curso</th>
                                            <th style="padding: 5px;">Nombre</th>
                                            <th style="padding: 5px;">Créditos</th>
                                            <th style="padding: 5px;">Nota</th>
                                            <th style="padding: 5px;">Semestre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                        foreach ($currentlyTaking as $curso) {
                                            echo "<tr>";
                                            echo "<form method='POST' action='index.php'>";
                                            echo "<td style='padding: 5px;'>" . $curso['crse_code'] . "</td>";
                                            echo "<input type='hidden' name='crse_code' value='" . $curso['crse_code'] . "'/>";
                                            echo "<td style='padding: 5px;'>" . $curso['course_name'] . "</td>";
                                            echo "<td style='padding: 5px;'>" . $curso['credits'] . "</td>";
                                            echo "<input type='hidden' name='credits' value='" . $curso['credits'] . "'/>";
                                            echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em;' value='" . $curso['crse_grade'] . "'/></td>";
                                            echo "<td style='padding: 5px;'>" . $curso['term'] . "</td>";
                                            echo "<input type='hidden' name='term' value='" . $curso['term'] . "'/>";
                                            echo "<input type='hidden' name='updateGrade' value='updateGrade'>";
                                            if ($privileges == 1) {
                                                echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Actualizar</button></td>";
                                            }
                                            echo "</tr>";
                                            echo "<input type='hidden' name='equivalencia' value=''/>";
                                            echo "<input type='hidden' name='convalidacion' value=''/>";
                                            echo "<input type='hidden' name='old_term' value='" . $curso['term'] . "'/>";
                                            echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                            echo "<input type='hidden' name='old_category' value=" . $curso['category'] . ">";
                                            echo "<input type='hidden' name='category' value=" . $curso['category'] . ">";
                                            echo "</form>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Clases de CCOM</h2>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="student_num" value="<?= $studentData['student_num'] ?>">
                            <!-- basic table -->
                            <div class="table-responsive">
                                <table style="font-size: 12px; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <th style="padding: 5px;"></th>
                                            <th style="padding: 5px;">Código Del Curso</th>
                                            <th style="padding: 5px;">Nombre</th>
                                            <th style="padding: 5px;">Créditos</th>
                                            <th style="padding: 5px;">Categoría</th>
                                            <th style="padding: 5px;">Nota</th>
                                            <th style="padding: 5px;">Semestre</th>
                                            <th style="padding: 5px;">Equivalencia</th>
                                            <th style="padding: 5px;">Convalidación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                        # CCOM CONCENTRATION COURSES
                                        $ccom_credits = 0;
                                        foreach ($ccomByCohort as $curso) {
                                            if (!empty($curso['crse_grade'])) {
                                                // Check if the input string does not contain specific substrings
                                                if (
                                                    strpos($curso['crse_grade'], 'I') === false && strpos($curso['crse_grade'], 'F') === false &&
                                                    strpos($curso['crse_grade'], 'NP') === false && strpos($curso['crse_grade'], 'W') === false && strpos($curso['crse_grade'], 'D') === false
                                                ) {
                                                    $ccom_credits += intval($curso['credits']);
                                                }
                                            }
                                            echo "<tr>";
                                            echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                            echo "<form method='POST' action='index.php'>";
                                            echo "<td style='padding: 5px;'>" . $curso['crse_code'] . "</td>";
                                            echo "<input type='hidden' name='crse_code' value='" . $curso['crse_code'] . "'/>";
                                            echo "<td style='padding: 5px;'>" . $curso['name'] . "</td>";
                                            echo "<td style='padding: 5px;'> <input type='number' name='credits' class='form-input' style='width: 5em;' value='" . $curso['credits'] . "'/></td>";
                                            echo "<td style='padding: 5px;'> 
                                            <select name='category' class='form-input' style='width: 8em;'>";
                                            $options = array("", "mandatory", "free", "general", "elective");
                                        ?>
                                            <option value="" <?php if ($curso['category'] == '') echo 'selected'; ?>></option>
                                            <option value="mandatory" <?php if ($curso['category'] == 'mandatory') echo 'selected'; ?>>Concentración CCOM</option>
                                            <option value="elective" <?php if ($curso['category'] == 'elective') echo 'selected'; ?>>Electiva CCOM</option>
                                            <option value="general" <?php if ($curso['category'] == 'general') echo 'selected'; ?>>General</option>
                                            <option value="free" <?php if ($curso['category'] == 'free') echo 'selected'; ?>>Electiva Libre</option>
                                            <option value="other" <?php if ($curso['category'] == 'other') echo 'selected'; ?>>Otras Clases</option>
                                        <?php
                                            echo "</select></td>";
                                            echo "<input type='hidden' name='old_category' value=" . $curso['category'] . ">";
                                            if (in_array($curso['crse_grade'], ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                                echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em; color: red;' value='" . ($curso['crse_grade'] ?? '') . "'/></td>";
                                            } else {
                                                echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em;' value='" . $curso['crse_grade'] . "'/> </td>";
                                            }
                                            echo "<td style='padding: 5px;'> <input type='text' name='term' class='form-input' style='width: 5em;' value='" . $curso['term'] . "' required/></td>";
                                            echo "<input type='hidden' name='old_term' value='" . $curso['term'] . "'/>";
                                            echo "<td style='padding: 5px;'> <input type='text' name='equivalencia' class='form-input' value='" . $curso['equivalencia'] . "'/></td>";
                                            echo "<td style='padding: 5px;'> <input type='text' name='convalidacion' class='form-input' value='" . $curso['convalidacion'] . "'/></td>";
                                            echo "<input type='hidden' name='updateGrade' value='updateGrade'>";
                                            if ($privileges == 1) {
                                                echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Actualizar</button></td>";
                                            }
                                            echo "</tr>";
                                            echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                            echo "</form>";
                                        }
                                        echo "<tr>";
                                        echo "<td></td><td></td>";
                                        echo "<td style='text-align: right'> <b>Créditos Aprobados: </b> </td>";
                                        echo "<td style='padding: 5px;'> " . $ccom_credits . "</td>";
                                        echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                                        echo "</tr>";
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Clases Generales</h2>
                            <!-- basic table -->
                            <div class="table-responsive">
                                <table style="font-size: 12px; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <th style="padding: 5px;"></th>
                                            <th style="padding: 5px;">Código Del Curso</th>
                                            <th style="padding: 5px;">Nombre</th>
                                            <th style="padding: 5px;">Créditos</th>
                                            <th style="padding: 5px;">Categoría</th>
                                            <th style="padding: 5px;">Nota</th>
                                            <th style="padding: 5px;">Semestre</th>
                                            <th style="padding: 5px;">Equivalencia</th>
                                            <th style="padding: 5px;">Convalidación</th>
                                            <th style="padding: 5px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        # GENERAL COURSES
                                        // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                        $general_credits = 0;
                                        foreach ($notccomByCohort as $curso) {
                                            if (!empty($curso['crse_grade'])) {
                                                // Check if the input string does not contain specific substrings
                                                if (
                                                    strpos($curso['crse_grade'], 'I') === false && strpos($curso['crse_grade'], 'F') === false &&
                                                    strpos($curso['crse_grade'], 'NP') === false && strpos($curso['crse_grade'], 'W') === false
                                                ) {
                                                    $general_credits += intval($curso['credits']);
                                                }
                                            }
                                            echo "<tr>";
                                            echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                            echo "<form method='POST' action='index.php'>";
                                            echo "<td style='padding: 5px;'>" . $curso['crse_code'] . "</td>";
                                            echo "<input type='hidden' name='crse_code' value='" . $curso['crse_code'] . "'/>";
                                            echo "<td style='padding: 5px;'>" . $curso['name'] . "</td>";
                                            echo "<td style='padding: 5px;'> <input type='number' name='credits' class='form-input' style='width: 5em;' value='" . $curso['credits'] . "'/></td>";
                                            echo "<td style='padding: 5px;'> 
                                            <select name='category' class='form-input' style='width: 8em;'>";
                                            $options = array("", "mandatory", "free", "general", "elective");
                                        ?>
                                            <option value="" <?php if ($curso['category'] == '') echo 'selected'; ?>></option>
                                            <option value="mandatory" <?php if ($curso['category'] == 'mandatory') echo 'selected'; ?>>Concentración CCOM</option>
                                            <option value="elective" <?php if ($curso['category'] == 'elective') echo 'selected'; ?>>Electiva CCOM</option>
                                            <option value="general" <?php if ($curso['category'] == 'general') echo 'selected'; ?>>General</option>
                                            <option value="free" <?php if ($curso['category'] == 'free') echo 'selected'; ?>>Electiva Libre</option>
                                            <option value="other" <?php if ($curso['category'] == 'other') echo 'selected'; ?>>Otras Clases</option>
                                        <?php
                                            echo "</select></td>";
                                            echo "<input type='hidden' name='old_category' value=" . $curso['category'] . ">";
                                            if (in_array($curso['crse_grade'], ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                                echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em; color: red;' value='" . ($curso['crse_grade'] ?? '') . "' /></td>";
                                            } else {
                                                echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em;' value='" . $curso['crse_grade'] . "' /> </td>";
                                            }
                                            echo "<td style='padding: 5px;'> <input type='text' name='term' class='form-input' style='width: 5em;' value='" . $curso['term'] . "'/></td>";
                                            echo "<input type='hidden' name='old_term' value='" . $curso['term'] . "'/>";
                                            echo "<td style='padding: 5px;'> <input type='text' name='equivalencia' class='form-input' value='" . $curso['equivalencia'] . "'/></td>";
                                            echo "<td style='padding: 5px;'> <input type='text' name='convalidacion' class='form-input' value='" . $curso['convalidacion'] . "'/></td>";
                                            echo "<input type='hidden' name='updateGrade' value='updateGrade'>";
                                            if ($privileges == 1) {
                                            echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Actualizar</button></td>";
                                            echo "<td style='padding: 5px;'> <button type='submit' name='action' value='' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Eliminar</i></button></td>"; // TENGO QUE HACER LA FUNCIONALIDAD DE BORRAR
                                            }echo "</tr>";
                                            echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                            echo "</form>";
                                        }
                                        echo "<tr>";
                                        echo "<td></td><td></td>";
                                        echo "<td style='text-align: right'> <b>Créditos Aprobados: </b> </td>";
                                        echo "<td style='padding: 5px;'> " . $general_credits . "</td>";
                                        echo "<td>HUMA: " . $_SESSION['HUMA_credits'] . "</td>
                                            <td colspan=2>CISO: " . $_SESSION['CISO_credits'] . "</td>
                                            <td></td><td></td><td></td><td></td><td></td>";
                                        echo "</tr>";
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Clases Electivas de CCOM</h2>
                            <?php
                            # CCOM ELECTIVE COURSES
                            if (empty($ccomFreeByNotCohort)) {
                                echo '<h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; text-align: center; margin-top: 1em; margin-bottom: 1em;">El estudiante aún no a tomado ninguna Electiva de CCOM</h2>';
                            } else {
                            ?>
                                <!-- basic table -->
                                <div class="table-responsive">
                                    <table style="font-size: 12px; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="padding: 5px;"></th>
                                                <th style="padding: 5px;">Código Del Curso</th>
                                                <th style="padding: 5px;">Nombre</th>
                                                <th style="padding: 5px;">Créditos</th>
                                                <th style="padding: 5px;">Categoría</th>
                                                <th style="padding: 5px;">Nivel</th>
                                                <th style="padding: 5px;">Nota</th>
                                                <th style="padding: 5px;">Semestre</th>
                                                <th style="padding: 5px;">Equivalencia</th>
                                                <th style="padding: 5px;">Convalidación</th>
                                                <th style="padding: 5px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                            $elective_credits = 0;
                                            foreach ($ccomFreeByNotCohort as $curso) {
                                                if (!empty($curso['crse_grade'])) {
                                                    // Check if the input string does not contain specific substrings
                                                    if (
                                                        strpos($curso['crse_grade'], 'I') === false && strpos($curso['crse_grade'], 'F') === false &&
                                                        strpos($curso['crse_grade'], 'NP') === false && strpos($curso['crse_grade'], 'W') === false && strpos($curso['crse_grade'], 'D') === false
                                                    ) {
                                                        $elective_credits += intval($curso['credits']);
                                                    }
                                                }
                                                echo "<tr>";
                                                echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                                echo "<form method='POST' action='index.php'>";
                                                echo "<td style='padding: 5px;'>" . $curso['crse_code'] . "</td>";
                                                echo "<input type='hidden' name='crse_code' value='" . $curso['crse_code'] . "'/>";
                                                echo "<td style='padding: 5px;'>" . $curso['name'] . "</td>";
                                                echo "<td style='padding: 5px;'> <input type='number' name='credits' class='form-input' style='width: 5em;' value='" . $curso['credits'] . "'/></td>";
                                                echo "<td><select name='category' class='form-input' style='width: 8em;'>";
                                                $options = array("", "mandatory", "free", "general", "elective");
                                            ?>
                                                <option value="" <?php if ($curso['category'] == '') echo 'selected'; ?>></option>
                                                <option value="mandatory" <?php if ($curso['category'] == 'mandatory') echo 'selected'; ?>>Concentración CCOM</option>
                                                <option value="elective" <?php if ($curso['category'] == 'elective') echo 'selected'; ?>>Electiva CCOM</option>
                                                <option value="general" <?php if ($curso['category'] == 'general') echo 'selected'; ?>>General</option>
                                                <option value="free" <?php if ($curso['category'] == 'free') echo 'selected'; ?>>Electiva Libre</option>
                                                <option value="other" <?php if ($curso['category'] == 'other') echo 'selected'; ?>>Otras Clases</option>
                                                <?php
                                                echo "</select></td>";
                                                echo "<td style='padding: 5px;'>
                                                <select name='level' class='form-input' style='width: 8em;'>";
                                                ?>
                                                <option value='NULL' <?php if ($curso['level'] != 'intermediate' && $curso['level'] != 'advanced') echo 'selected' ?>>N/A</option>
                                                <option value='intermediate' <?php if ($curso['level'] == 'intermediate') echo 'selected' ?>>Intermedia</option>
                                                <option value='advanced' <?php if ($curso['level'] == 'advanced') echo 'selected' ?>>Avanzada</option>
                                            <?php
                                                echo "</select></td>";
                                                echo "<input type='hidden' name='old_category' value=" . $curso['category'] . ">";
                                                if (in_array($curso['crse_grade'], ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                                    echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em; color: red;' value='" . ($curso['crse_grade'] ?? '') . "' /></td>";
                                                } else {
                                                    echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em;' value='" . $curso['crse_grade'] . "' /> </td>";
                                                }
                                                echo "<td style='padding: 5px;'> <input type='text' name='term' class='form-input' style='width: 5em;' value='" . $curso['term'] . "'/></td>";
                                                echo "<input type='hidden' name='old_term' value='" . $curso['term'] . "'/>";
                                                echo "<td style='padding: 5px;'> <input type='text' name='equivalencia' class='form-input' value='" . $curso['equivalencia'] . "'/></td>";
                                                echo "<td style='padding: 5px;'> <input type='text' name='convalidacion' class='form-input' value='" . $curso['convalidacion'] . "'/></td>";
                                                echo "<input type='hidden' name='updateGrade' value='updateGrade'>";
                                                if ($privileges == 1) {
                                                    echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Actualizar</button></td>";
                                                }
                                                echo "</tr>";
                                                echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                                echo "</form>";
                                            }
                                            echo "<tr>";
                                            echo "<td></td><td></td>";
                                            echo "<td style='text-align: right'> <b>Créditos Aprobados: </b> </td>";
                                            echo "<td style='padding: 5px;'> " . $elective_credits . "</td>";
                                            echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                                            echo "</tr>";
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                            <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Clases Electivas Libres</h2>
                            <?php
                            # FREE ELECTIVE COURSES
                            if (empty($notccomByNotCohort)) {
                                echo '<h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; text-align: center; margin-top: 1em; margin-bottom: 1em;">El estudiante aún no a tomado ninguna Electiva libre</h2>';
                            } else {
                            ?>
                                <!-- basic table -->
                                <div class="table-responsive">
                                    <table style="font-size: 12px; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="padding: 5px;"></th>
                                                <th style="padding: 5px;">Código Del Curso</th>
                                                <th style="padding: 5px;">Nombre</th>
                                                <th style="padding: 5px;">Créditos</th>
                                                <th style="padding: 5px;">Categoría</th>
                                                <th style="padding: 5px;">Nota</th>
                                                <th style="padding: 5px;">Semestre</th>
                                                <th style="padding: 5px;">Equivalencia</th>
                                                <th style="padding: 5px;">Convalidación</th>
                                                <th style="padding: 5px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                            $free_credits = 0;
                                            foreach ($notccomByNotCohort as $curso) {
                                                $free_credits += intval($curso['credits']); 
                                                echo "<tr>";
                                                echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                                echo "<form method='POST' action='index.php'>";
                                                echo "<td style='padding: 5px;'>" . $curso['crse_code'] . "</td>";
                                                echo "<input type='hidden' name='crse_code' value='" . $curso['crse_code'] . "'/>";
                                                echo "<td style='padding: 5px;'>" . $curso['name'] . "</td>";
                                                echo "<td style='padding: 5px;'> <input type='number' name='credits' class='form-input' style='width: 5em;' value='" . $curso['credits'] . "'/></td>";
                                                echo "<td style='padding: 5px;'> 
                                                <select name='category' class='form-input' style='width: 8em;'>";
                                                $options = array("", "mandatory", "free", "general", "elective");
                                            ?>
                                                <option value="" <?php if ($curso['category'] == '') echo 'selected'; ?>></option>
                                                <option value="mandatory" <?php if ($curso['category'] == 'mandatory') echo 'selected'; ?>>Concentración CCOM</option>
                                                <option value="elective" <?php if ($curso['category'] == 'elective') echo 'selected'; ?>>Electiva CCOM</option>
                                                <option value="general" <?php if ($curso['category'] == 'general') echo 'selected'; ?>>General</option>
                                                <option value="free" <?php if ($curso['category'] == 'free') echo 'selected'; ?>>Electiva Libre</option>
                                                <option value="other" <?php if ($curso['category'] == 'other') echo 'selected'; ?>>Otras Clases</option>
                                            <?php
                                                echo "</select></td>";
                                                echo "<input type='hidden' name='old_category' value=" . $curso['category'] . ">";
                                                if (in_array($curso['crse_grade'], ['D', 'F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                                    echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em; color: red;' value='" . ($curso['crse_grade'] ?? '') . "' /></td>";
                                                } else {
                                                    echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em;' value='" . $curso['crse_grade'] . "' /> </td>";
                                                }
                                                echo "<td style='padding: 5px;'> <input type='text' name='term' class='form-input' style='width: 5em;' value='" . $curso['term'] . "'/></td>";
                                                echo "<input type='hidden' name='old_term' value='" . $curso['term'] . "'/>";
                                                echo "<td style='padding: 5px;'> <input type='text' name='equivalencia' class='form-input' value='" . $curso['equivalencia'] . "'/></td>";
                                                echo "<td style='padding: 5px;'> <input type='text' name='convalidacion' class='form-input' value='" . $curso['convalidacion'] . "'/></td>";
                                                echo "<input type='hidden' name='updateGrade' value='updateGrade'>";
                                                if ($privileges == 1) {
                                                    echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Actualizar</button></td>";
                                                }
                                                echo "</tr>";
                                                echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                                echo "</form>";
                                            }
                                            echo "<tr>";
                                            echo "<td></td><td></td>";
                                            echo "<td style='text-align: right'> <b>Créditos Aprobados: </b> </td>";
                                            echo "<td style='padding: 5px;'> " . $free_credits . "</td>";
                                            echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
                                            echo "</tr>";
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                        </form>
                    <?php } ?>
                    <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Otras Clases</h2>
                    <?php
                    if ($otherClasses->num_rows === 0) {
                        echo '<h2 class="m-0 dark:text-white-dark" style="font-size: 1.5em; text-align: center; margin-top: 1em; margin-bottom: 1em;">No hay otras clases que el estudiante haya tomado</h2>';
                    } else {
                    ?>

                        <!-- basic table -->
                        <div class="table-responsive">
                            <table style="font-size: 12px; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="padding: 5px;"></th>
                                        <th style="padding: 5px;">Código Del Curso</th>
                                        <th style="padding: 5px;">Nombre</th>
                                        <th style="padding: 5px;">Créditos</th>
                                        <th style="padding: 5px;">Categoría</th>
                                        <th style="padding: 5px;">Nota</th>
                                        <th style="padding: 5px;">Semestre</th>
                                        <th style="padding: 5px;">Equivalencia</th>
                                        <th style="padding: 5px;">Convalidación</th>
                                        <th style="padding: 5px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                    foreach ($otherClasses as $curso) {
                                        echo "<tr>";
                                        echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                        echo "<form method='POST' action='index.php'>";
                                        echo "<td style='padding: 5px;'>" . $curso['crse_code'] . "</td>";
                                        echo "<td style='padding: 5px;'>" . $curso['name'] . "</td>";
                                        echo "<input type='hidden' name='crse_code' value='" . $curso['crse_code'] . "'/>";
                                        echo "<td style='padding: 5px;'> <input type='number' name='credits' class='form-input' style='width: 5em;' value='" . $curso['credits'] . "'/></td>";
                                        echo "<td style='padding: 5px;'> 
                                                <select name='category' class='form-input' style='width: 8em;'>";
                                        $options = array("", "mandatory", "free", "general", "elective");
                                    ?>
                                        <option value="" <?php if ($curso['category'] == '') echo 'selected'; ?>></option>
                                        <option value="mandatory" <?php if ($curso['category'] == 'mandatory') echo 'selected'; ?>>Concentración CCOM</option>
                                        <option value="elective" <?php if ($curso['category'] == 'elective') echo 'selected'; ?>>Electiva CCOM</option>
                                        <option value="general" <?php if ($curso['category'] == 'general') echo 'selected'; ?>>General</option>
                                        <option value="free" <?php if ($curso['category'] == 'free') echo 'selected'; ?>>Electiva Libre</option>
                                        <option value="other" <?php if ($curso['category'] == 'other') echo 'selected'; ?>>Otras Clases</option>
                                    <?php
                                        echo "</select></td>";
                                        if (in_array($curso['crse_grade'], ['F', 'F*', 'NP', 'I', 'W', 'W*', 'NR'])) {
                                            echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em; color: red;' value='" . $curso['crse_grade'] . "'/></td>";
                                        } else {
                                            echo "<td style='padding: 5px;'> <input type='text' name='grade' class='form-input' style='width: 4em;' value='" . $curso['crse_grade'] . "'/></td>";
                                        }
                                        echo "<td style='padding: 5px;'> <input type='text' name='term' class='form-input' style='width: 5em;' value='" . $curso['term'] . "'/></td>";
                                        echo "<input type='hidden' name='old_term' value='" . $curso['term'] . "'/>";
                                        echo "<input type='hidden' name='old_category' value= '" . $curso['category'] . "'/>";
                                        echo "<td style='padding: 5px;'> <input type='text' name='equivalencia' class='form-input' value='" . $curso['equivalencia'] . "'/></td>";
                                        echo "<td style='padding: 5px;'> <input type='text' name='convalidacion' class='form-input' value='" . $curso['convalidacion'] . "'/></td>";
                                        echo "<input type='hidden' name='updateGrade' value='updateGrade'>";
                                        if (/*$curso['db'] == 'yes' && */$privileges == 1)
                                            echo "<td style='padding: 5px;'> <button type='submit' name='action' value='studentCounseling' class='btn btn-primary ltr:ml-2 rtl:mr-2'>Actualizar</button></td>";
                                        echo "</tr>";
                                        echo "<input type='hidden' name='student_num' value=" . $studentData['student_num'] . ">";
                                        echo "</form>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <?php if ($privileges == 1) { ?>
                        <h2 class="m-0 dark:text-white-dark" style="font-size: 2em; font-weight: bold; text-align: center; margin-top: 1em; margin-bottom: 1em;">Clases a recomendar</h2>
                        <!-- Vertical line tabs -->
                        <div class="mb-5 flex flex-col sm:flex-row" x-data="{ tab: 'home'}">
                            <!-- buttons -->
                            <div class="mx-10 mb-5 sm:mb-0">
                                <ul class="w-24 m-auto text-center font-semibold">
                                    <li>
                                        <a href="javascript:" class="p-3.5 py-4 -mb-[1px] block ltr:border-r rtl:border-l border-white-light dark:border-[#191e3a] relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-[1px] before:bottom-0 before:top-0 ltr:before:-right-[1px] rtl:before:-left-[1px] before:m-auto before:h-0 hover:before:h-[80%] before:bg-secondary" :class="{'text-secondary before:!h-[80%]' : tab === 'mandatory'}" @click="tab='mandatory'">CCOM Requeridas</a>
                                    </li>
                                    <li>
                                        <a href="javascript:" class="p-3.5 py-4 -mb-[1px] block ltr:border-r rtl:border-l border-white-light dark:border-[#191e3a] relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-[1px] before:bottom-0 before:top-0 ltr:before:-right-[1px] rtl:before:-left-[1px] before:m-auto before:h-0 hover:before:h-[80%] before:bg-secondary" :class="{'text-secondary before:!h-[80%]' : tab === 'ccomElective'}" @click="tab='ccomElective'">CCOM Electivas</a>
                                    </li>
                                    <li>
                                        <a href="javascript:" class="p-3.5 py-4 -mb-[1px] block ltr:border-r rtl:border-l border-white-light dark:border-[#191e3a] relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-[1px] before:bottom-0 before:top-0 ltr:before:-right-[1px] rtl:before:-left-[1px] before:m-auto before:h-0 before:bg-secondary hover:before:h-[80%]" :class="{'text-secondary before:!h-[80%]' : tab === 'elective'}" @click="tab='elective'">Cursos Dummy</a>
                                    </li>
                                    <li>
                                        <a href="javascript:" class="p-3.5 py-4 -mb-[1px] block ltr:border-r rtl:border-l border-white-light dark:border-[#191e3a] relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-[1px] before:bottom-0 before:top-0 ltr:before:-right-[1px] rtl:before:-left-[1px] before:m-auto before:h-0 before:bg-secondary hover:before:h-[80%]" :class="{'text-secondary before:!h-[80%]' : tab === 'general'}" @click="tab='general'">Generales</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- description -->
                            <div class="flex-1 text-sm ">
                                <div x-show="tab === 'mandatory'">
                                    <!-- basic table -->
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Código Del Curso</th>
                                                    <th>Nombre</th>
                                                    <th>Créditos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                                foreach ($mandatoryClasses as $curso) {
                                                    echo "<tr>";
                                                    echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                                    echo "<td>" . $curso['crse_code'] . "</td>";
                                                    echo "<td>" . $curso['name'] . "</td>";
                                                    echo "<td>" . $curso['credits'] . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="tab === 'elective'">
                                    <!-- basic table -->
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Código Del Curso</th>
                                                    <th>Nombre</th>
                                                    <th>Créditos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                                foreach ($dummyClasses as $curso) {
                                                    echo "<tr>";
                                                    echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                                    echo "<td>" . $curso['crse_code'] . "</td>";
                                                    echo "<td>" . $curso['name'] . "</td>";
                                                    echo "<td>" . $curso['credits'] . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="tab === 'ccomElective'">
                                    <!-- basic table -->
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Código Del Curso</th>
                                                    <th>Nombre</th>
                                                    <th>Créditos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                                foreach ($electiveClasses as $curso) {
                                                    echo "<tr>";
                                                    echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                                    echo "<td>" . $curso['crse_code'] . "</td>";
                                                    echo "<td>" . $curso['name'] . "</td>";
                                                    echo "<td>" . $curso['credits'] . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div x-show="tab === 'general'">
                                    <!-- basic table -->
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Código Del Curso</th>
                                                    <th>Nombre</th>
                                                    <th>Créditos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Itera sobre los datos de los cursos y llena las celdas de la tabla
                                                foreach ($generalClasses as $curso) {

                                                    echo "<tr>";
                                                    echo "<td><input type='checkbox' name='seleccion[]' value='" . $curso['crse_code'] . "'></td>";
                                                    echo "<td>" . $curso['crse_code'] . "</td>";
                                                    echo "<td>" . $curso['name'] . "</td>";
                                                    echo "<td>" . $curso['credits'] . "</td>";
                                                    echo "</tr>";
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type='hidden' name='makecounseling' value='makecounseling'>
                        <input type='hidden' name='student_num' value="<?php echo $studentData['student_num'] ?>">
                        <button type="submit" name="action" value="studentCounseling" class="btn btn-primary ltr:ml-2 rtl:mr-2">Someter Consejería</button>
                        </form>
                        <br> 
                        <!-- <form method="POST" action="index.php">
                            <input type='hidden' name='openCounseling' value='openCounseling'>
                            <input type='hidden' name='student_num' value="<?php echo $studentData['student_num'] ?>">
                            <button type="submit" name="action" value="openCounseling" class="btn btn-primary ltr:ml-2 rtl:mr-2">Reiniciar Consejería en Frontend</button>
                        </form> -->
                        <?php } ?>
                        <br> 
                        <?php if($studentData['counseling_lock'] == 0) { ?>
                            <form method="POST" action="index.php">
                            <input type='hidden' name='block' value='block'>
                            <input type='hidden' name='student_num' value="<?php echo $studentData['student_num'] ?>">
                            <button type="submit" name="action" value="blockCounseling" class="btn btn-primary ltr:ml-2 rtl:mr-2">Cerrar Consejería al Estudiante</button>
                            </form>
                        <?php } 
                            else if($studentData['counseling_lock'] == 1) { ?>
                            <form method="POST" action="index.php">
                            <input type='hidden' name='openCounseling' value='openCounseling'>
                            <input type='hidden' name='student_num' value="<?php echo $studentData['student_num'] ?>">
                            <button type="submit" name="action" value="blockCounseling" class="btn btn-primary ltr:ml-2 rtl:mr-2">Abrir Consejería al Estudiante</button>
                            </form>
                        <?php } ?>
                        <button class="btn btn-danger !mt-6" onclick="window.location.href = 'index.php'">Volver a Estudiantes</button>
                    </div>
                </div>
                <!-- end main content section -->
            </div>

            <!-- start footer section -->
            <div class="p-6 pt-0 mt-auto text-center dark:text-white-dark ltr:sm:text-left rtl:sm:text-right">
                © <span id="footer-year">2022</span>. UPRA All rights reserved.
            </div>
            <!-- end footer section -->
        </div>
    </div>

    <script src="assets/js/alpine-collaspe.min.js"></script>
    <script src="assets/js/alpine-persist.min.js"></script>
    <script defer src="assets/js/alpine-ui.min.js"></script>
    <script defer src="assets/js/alpine-focus.min.js"></script>
    <script defer src="assets/js/alpine.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- <script src="assets/js/courses.js"></script> -->

    <script>
        $(document).ready(function() {
            $("#sidebar").load("sidebar.php");
        });
        document.getElementById('termSelect').addEventListener('change', function() {
            var selectedTerm = this.value;
            document.getElementById('selectedTerm').value = selectedTerm;
            document.getElementById('termForm').submit();
        });

        document.addEventListener('alpine:init', () => {
            // main section
            Alpine.data('scrollToTop', () => ({
                showTopButton: false,
                init() {
                    window.onscroll = () => {
                        this.scrollFunction();
                    };
                },

                scrollFunction() {
                    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                        this.showTopButton = true;
                    } else {
                        this.showTopButton = false;
                    }
                },

                goToTop() {
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                },
            }));


            // sidebar section
            Alpine.data('sidebar', () => ({
                init() {
                    const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
                    if (selector) {
                        selector.classList.add('active');
                        const ul = selector.closest('ul.sub-menu');
                        if (ul) {
                            let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                            if (ele) {
                                ele = ele[0];
                                setTimeout(() => {
                                    ele.click();
                                });
                            }
                        }
                    }
                },
            }));

            // header section
            Alpine.data('header', () => ({
                init() {
                    const selector = document.querySelector('ul.horizontal-menu a[href="' + window.location.pathname + '"]');
                    if (selector) {
                        selector.classList.add('active');
                        const ul = selector.closest('ul.sub-menu');
                        if (ul) {
                            let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                            if (ele) {
                                ele = ele[0];
                                setTimeout(() => {
                                    ele.classList.add('active');
                                });
                            }
                        }
                    }
                },

                messages: [{
                        id: 1,
                        image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>',
                        title: 'Congratulations!',
                        message: 'Your OS has been updated.',
                        time: '1hr',
                    },
                    {
                        id: 2,
                        image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-info-light dark:bg-info text-info dark:text-info-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></span>',
                        title: 'Did you know?',
                        message: 'You can switch between artboards.',
                        time: '2hr',
                    },
                    {
                        id: 3,
                        image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-danger-light dark:bg-danger text-danger dark:text-danger-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>',
                        title: 'Something went wrong!',
                        message: 'Send Reposrt',
                        time: '2days',
                    },
                    {
                        id: 4,
                        image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-warning-light dark:bg-warning text-warning dark:text-warning-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">    <circle cx="12" cy="12" r="10"></circle>    <line x1="12" y1="8" x2="12" y2="12"></line>    <line x1="12" y1="16" x2="12.01" y2="16"></line></svg></span>',
                        title: 'Warning',
                        message: 'Your password strength is low.',
                        time: '5days',
                    },
                ],

                removeMessage(value) {
                    this.messages = this.messages.filter((d) => d.id !== value);
                },
            }));
        });
        // elec script

        const clearCourse = (course, elements, category) => {
            var query = document.getElementById(course).value; /* Value inputted by user */
            var elements = document.getElementsByClassName(elements); /* Get the li elements in the list */
            var myList = document.getElementById(category); /* Var to reference the list */
            var length = (document.getElementsByClassName(element).length); /* # of li elements */
            var checker = 'false'; /* boolean-ish value to determine if value was found */

            myList.querySelectorAll('li').forEach(function(item) {
                if (item.innerHTML.indexOf(query) !== -1)
                    item.remove();
            });
        }

        const clearCourses = (courses) => {
            document.getElementById(courses).innerHTML = "";
        }

        document.addEventListener("alpine:init", () => {
            Alpine.data("collapse", () => ({
                collapse: false,

                collapseSidebar() {
                    this.collapse = !this.collapse;
                },
            }));

            Alpine.data("dropdown", (initialOpenState = false) => ({
                open: initialOpenState,

                toggle() {
                    this.open = !this.open;
                },
            }));
        });

        // document.addEventListener("DOMContentLoaded", () => {
        //     //Necessary to check these buttons exist at moment of loading
        //     // hook up click events for both buttons
        //     //document.querySelector("#images/espresso_info.jpg").addEventListener("click", joinList("espresso"));
        //     //$("#clear_form").addEventListener("click", clearForm);
        //     document.querySelector(".elec").addEventListener("click") => {
        //         document.getElementById("elec").innerHTML = "";
        //     }

        //     // set focus on first text box after the form loads
        //     //$("#email_1").focus();
        // });
    </script>
    <!-- dropdown script -->
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("dropdown", (initialOpenState = false) => ({
                open: initialOpenState,

                toggle() {
                    this.open = !this.open;
                },
            }));



        });
    </script>
</body>

</html>
