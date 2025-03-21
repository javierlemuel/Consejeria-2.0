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
    <script src="assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="assets/js/popper.min.js"></script>
    <script defer src="assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="assets/js/sweetalert.min.js"></script>
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
                <div style='text-align: center'>
                    <?php
                    if (isset($_SESSION['students_list_msg'])) {
                        if (strpos($_SESSION['students_list_msg'], 'No') !== false || strpos($_SESSION['students_list_msg'], 'Error') !== false)
                            echo "<h2 style='color:red; bold' class='text-xl'>" . $_SESSION['students_list_msg'] . "</h2>";
                        else
                            echo "<h2 style='color:limegreen; bold' class='text-xl'>" . $_SESSION['students_list_msg'] . "</h2>";
                        unset($_SESSION['students_list_msg']);
                    }
                    ?>
                </div>
                <!-- start main content section -->
                <div x-data="contacts">
                    <h2 class="text-xl mb-2">Expedientes de Estudiantes </h2>
                    <div class="flex flex-wrap items-center justify-between gap-4">

                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" @click="toggle">
                                <?php
                                echo 'Filtrar por Consejeria' .
                                    (isset($_GET['did_counseling']) && $_GET['did_counseling'] !== ''
                                        ? ($_GET['did_counseling'] == '1' ? ': Hecho' : ': No Hecho')
                                        : '');
                                ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 100 100">
                                    <text x="50" y="65" font-size="48" fill="White">▼</text>
                                </svg>
                            </button>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0 whitespace-nowrap">
                                <li><a href="?did_counseling=1&q=<?php echo $q . ('&status=' . ($_GET['status']) ?? '') ?>" @click="toggle">Hecho Consejeria</a></li>
                                <li><a href="?did_counseling=0&q=<?php echo $q . ('&status=' . ($_GET['status']) ?? '') ?>" @click="toggle">No Hecho Consejeria</a></li>
                            </ul>
                        </div>

                        <!-- Comienzo Boton drop down -->
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" @click="toggle"><?php echo 'Filtrar por ' . (!empty($_GET['status']) ? $_GET['status'] : 'Estatus') ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 100 100">
                                    <text x="50" y="65" font-size="48" fill="White">▼</text>
                                </svg>
                            </button>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0 whitespace-nowrap">
                                <li><a href="?status=Todos&q=<?php echo $q . '&did_counseling=' . ($_GET['did_counseling'] ?? '') ?>" @click="toggle">Todos</a></li>
                                <li><a href="?status=Activos&q=<?php echo $q . '&did_counseling=' . ($_GET['did_counseling'] ?? '') ?>" @click="toggle">Activos</a></li>
                                <li><a href="?status=Inactivos&q=<?php echo $q . '&did_counseling=' . ($_GET['did_counseling'] ?? '') ?>" @click="toggle">Inactivos</a></li>
                                <li><a href="?status=Graduados&q=<?php echo $q . '&did_counseling=' . ($_GET['did_counseling'] ?? '') ?>" @click="toggle">Graduados</a></li>
                                <li><a href="?status=Graduandos&q=<?php echo $q . '&did_counseling=' . ($_GET['did_counseling'] ?? '') ?>" @click="toggle">Graduandos</a></li>
                            </ul>
                        </div>
                        <!-- Final del boton de dropdown-->
                        <div class="flex w-full flex-col gap-4 sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                            <div class="flex gap-3">
                                <div>
                                    <?php if ($privileges == 1) { ?>
                                        <button type="button" class="btn btn-primary" @click="editUser">
                                            <svg
                                                width="24"
                                                height="24"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                                                <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                                                <path
                                                    opacity="0.5"
                                                    d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                                                    stroke="currentColor"
                                                    stroke-width="1.5" />
                                                <path
                                                    d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12"
                                                    stroke="currentColor"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round" />
                                            </svg>
                                            Crear estudiante
                                        </button>
                                    <?php } ?>
                                    <div class="fixed inset-0 z-[999] hidden overflow-y-auto bg-[black]/60" :class="addContactModal && '!block'">
                                        <div class="flex min-h-screen items-center justify-center px-4" @click.self="addContactModal = false">
                                            <div
                                                x-show="addContactModal"
                                                x-transition
                                                x-transition.duration.300
                                                class="panel my-8 w-[90%] max-w-lg overflow-hidden rounded-lg border-0 p-0 md:w-full">
                                                <button
                                                    type="button"
                                                    class="absolute top-4 text-white-dark hover:text-dark ltr:right-4 rtl:left-4"
                                                    @click="addContactModal = false">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="24px"
                                                        height="24px"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="h-6 w-6">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                                <h3
                                                    class="bg-[#fbfbfb] py-3 text-lg font-medium ltr:pl-5 ltr:pr-[50px] rtl:pr-5 rtl:pl-[50px] dark:bg-[#121c2c]"
                                                    x-text="params.id ? 'Editar estudiante' : 'Crear estudiante'"></h3>
                                                <div class="p-5">
                                                    <form id="studentForm" action="#" method="POST">
                                                        <div class="mb-5 grid grid-cols-1 md:grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                            <input type="hidden" name="action" value="addStudent">
                                                            <div>
                                                                <label for="nombre">Primer <br> Nombre</label>
                                                                <input x-model="params.nombre" id="nombre" name="nombre" type="text" class="form-input" maxlength="15" required />
                                                            </div>
                                                            <div>
                                                                <label for="nombre2">Segundo Nombre</label>
                                                                <input id="nombre2" name="nombre2" type="text" class="form-input" maxlength="15" />
                                                            </div>
                                                            <div>
                                                                <label for="apellidoP">Apellido Paterno</label>
                                                                <input x-model="params.apellidoP" id="apellidoP" name="apellidoP" type="text" class="form-input" maxlength="20" required />
                                                            </div>
                                                            <div>
                                                                <label for="apellidoM">Apellido Materno</label>
                                                                <input id="apellidoM" name="apellidoM" type="text" class="form-input" maxlength="20" />
                                                            </div>
                                                        </div>
                                                        <div class="mb-5">
                                                            <label for="email">Email</label>
                                                            <input
                                                                id="email"
                                                                name="email"
                                                                type="email"
                                                                placeholder="yeyo.soto2@upr.edu"
                                                                class="form-input"
                                                                x-model="params.email"
                                                                required />
                                                        </div>
                                                        <div class="mb-5">
                                                            <label for="minor">Minor</label>
                                                            <select id="gridYear" class="form-select text-white-dark" name="minor">
                                                                <option value="0">N/A</option>
                                                                <!-- JAVIER -->
                                                                <?php foreach ($minors as $minor) { ?>
                                                                    <option value="<?php echo $minor['ID']; ?>"><?php echo $minor['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-5">
                                                            <label for="numero">Número de estudiante</label>
                                                            <input
                                                                id="numero"
                                                                name="numero_estu"
                                                                type="text"
                                                                placeholder="840-xx-xxxx"
                                                                class="form-input"
                                                                x-model="params.numero"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9);"
                                                                maxlength="9"
                                                                required />
                                                        </div>
                                                        <div class="mb-5">
                                                            <label for="cohorte">Cohorte</label>
                                                            <select id="gridYear" class="form-select text-white-dark" name="cohorte" x-model="params.cohorte" required>
                                                                <?php foreach ($cohortes as $c) { ?>
                                                                    <option value="<?php echo $c['cohort_year'] ?>"><?php echo $c['cohort_year'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-5">
                                                            <label for="estatus">Estatus</label>
                                                            <select id="status" x-model="params.estatus" class="form-select text-white-dark" name="estatus" required>
                                                                <option>Activo</option>
                                                                <option>Inactivo</option>
                                                                <option>Graduado</option>
                                                                <option>Graduando</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-5">
                                                            <label for="birthday">Cumpleaños</label>
                                                            <input type="date" x-model="params.birthday" id="birthday" name="birthday" required>
                                                        </div>

                                                        <div class="mt-8 flex items-center justify-end">
                                                            <button type="button" class="btn btn-outline-danger" @click="addContactModal = false">
                                                                Cancelar
                                                            </button>
                                                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4" x-text="params.id ? 'Update' : 'Añadir'"></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="relative">
                                <form action="index.php" method="GET">
                                    <input
                                        type="text"
                                        name="q"
                                        placeholder="Buscar por nombre o #"
                                        value="<?php echo $_GET['q'] ?? "" ?>"
                                        class="peer form-input py-2 ltr:pr-11 rtl:pl-11" />
                                    <!-- Agrega campos ocultos para los parámetros de filtro de estado -->
                                    <input type="hidden" name="status" value="<?php echo $statusFilter; ?>">
                                    <div class="absolute top-1/2 -translate-y-1/2 peer-focus:text-primary ltr:right-[11px] rtl:left-[11px]">
                                        <button type="submit">
                                            <svg class="mx-auto" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                                                <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <br>
                    <!-- inicio de tabla para presentar los estudiantes-->
                    <div class="table-responsive">
                        <table class="table-striped">
                            <thead>
                                <tr>
                                    <th>Estudiante</th>
                                    <th>Número de estudiante</th>
                                    <th>Recomendación</th>
                                    <th>Consejería</th>
                                    <th>Estatus</th>
                                    <th>Ultima Actualización</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $student['name1'] ?> <?= $student['name2'] ?> <?= $student['last_name1'] ?> <?= $student['last_name2'] ?></td>
                                        <td><?= $student['formatted_student_num'] ?></td>
                                        <td>
                                            <?php if ($student['status'] == 'Graduando' || $student['status'] == 'Graduado'): ?>
                                                <span class="badge whitespace-nowrap badge-outline-primary">No necesita</span>
                                            <?php elseif ($student['given_counseling'] == "0"): ?>
                                                <span class="badge whitespace-nowrap badge-outline-danger">No realizada</span>
                                            <?php else: ?>
                                                <span class="badge whitespace-nowrap badge-outline-primary">Realizada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($student['status'] == 'Graduando' || $student['status'] == 'Graduado'): ?>
                                                <span class="badge whitespace-nowrap badge-outline-primary">No necesita</span>
                                            <?php elseif ($student['conducted_counseling'] == 0): ?>
                                                <span class="badge whitespace-nowrap badge-outline-danger">No realizada</span>
                                            <?php else: ?>
                                                <span class="badge whitespace-nowrap badge-outline-primary">Realizada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($student['status'] == 'Inactivo'): ?>
                                                <span class="badge whitespace-nowrap badge-outline-danger">Inactivo</span>
                                            <?php elseif ($student['status'] == 'Graduado'): ?>
                                                <span class="badge whitespace-nowrap badge-outline-primary">Graduado</span>
                                            <?php elseif ($student['status'] == 'Graduando'): ?>
                                                <span class="badge whitespace-nowrap badge-outline-primary">Graduando</span>
                                            <?php else: ?>
                                                <span class="badge whitespace-nowrap badge-outline-success">Activo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $student['edited_date'] ?></td>
                                        <td>
                                            <form method="POST" action="index.php">
                                                <input type="hidden" name="action" value="selecteStudent">
                                                <input type="hidden" name="student_num" value="<?= $student['student_num'] ?>">
                                                <button type="submit" class="btn btn-primary ltr:ml-2 rtl:mr-2" x-text="params.id ? 'Update' : 'Editar'"></button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="POST" action="index.php">
                                                <input type="hidden" name="action" value="studentCounseling">
                                                <input type="hidden" name="student_num" value="<?= $student['student_num'] ?>">
                                                <button type="submit" class="btn btn-primary ltr:ml-2 rtl:mr-2" x-text="params.id ? 'Update' : 'Consejería'"></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div> <br>
                    <!-- final de tabla para presentar los estudiantes-->
                    <!--inicio de paginacion -->

                    <?php include_once(__ROOT__ . "/admin/global_classes/utils.php") ?>
                    <?php generate_pagination($p ?? 1, $amountOfPages ?? 1); ?>
                </div> <br>
                <!-- end main content section -->

                <!-- start footer section -->
                <div class="p-6 pt-0 mt-auto text-center dark:text-white-dark ltr:sm:text-left rtl:sm:text-right">
                    © <span id="footer-year">2022</span>. UPRA All rights reserved.
                </div>
                <!-- end footer section -->
            </div>
        </div>

        <?php
        // Check if the session variables are set
        if (isset($_SESSION['registertxt'])) {
            // Output the text content in a JavaScript block
            echo '<script>';
            echo 'var txtContent = ' . json_encode($_SESSION['registertxt']) . ';';
            echo 'var txtFileName = "archivo_de_registro.txt";'; // Set your desired filename here
            echo 'var blob = new Blob([txtContent], { type: "text/plain" });'; // Use "text/plain" for plain text
            echo 'var a = document.createElement("a");';
            echo 'a.href = URL.createObjectURL(blob);';
            echo 'a.target = "_blank";'; // Open in a new window
            echo 'document.body.appendChild(a);';
            echo 'a.click();';
            echo 'document.body.removeChild(a);';
            echo '</script>';

            // Clear the session variable
            unset($_SESSION['registertxt']);
        }

        if (isset($_SESSION['registermodeltxt']) && $_SESSION['registermodeltxt'] != '') {
            // Set the desired filename
            $date = date("Y-m-d");
            $fileName = "error_log_" . $date;

            echo '<script>';
            echo 'var txtContent = ' . json_encode($_SESSION['registermodeltxt']) . ';';
            echo 'var blob = new Blob([txtContent], { type: "text/plain" });';
            echo 'var a = document.createElement("a");';
            echo 'a.href = URL.createObjectURL(blob);';
            echo 'a.download = ' . json_encode($fileName) . ';';
            echo 'document.body.appendChild(a);';
            echo 'a.click();';
            echo 'document.body.removeChild(a);';
            echo 'URL.revokeObjectURL(a.href);';
            echo '</script>';

            // Clear the session variable
            unset($_SESSION['registermodeltxt']);
        }

        ?>

        <script src="assets/js/alpine-collaspe.min.js"></script>
        <script src="assets/js/alpine-persist.min.js"></script>
        <script defer src="assets/js/alpine-ui.min.js"></script>
        <script defer src="assets/js/alpine-focus.min.js"></script>
        <script defer src="assets/js/alpine.min.js"></script>
        <script src="assets/js/custom.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>


        <script>
            $(document).ready(function() {
                $("#sidebar").load("sidebar.php");
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
        </script>
        <!-- dropdown script -->
        <script>
            function changePage(page) {
                // Redirige a la página correspondiente
                window.location.href = "?page=" + page;
            }

            document.addEventListener("alpine:init", () => {
                Alpine.data("dropdown", (initialOpenState = false) => ({
                    open: initialOpenState,

                    toggle() {
                        this.open = !this.open;
                    },
                }));

                Alpine.data('app', () => ({
                    showUploadModal: false,
                    formData: {
                        file: null,
                    },
                    openUploadModal() {
                        this.showUploadModal = true;
                    },
                    closeUploadModal() {
                        this.showUploadModal = false;
                    },
                    submitForm() {
                        // Aquí puedes realizar acciones con el archivo seleccionado, como enviarlo a un servidor.
                        // Luego, cierra el modal.
                        if (this.formData.file) {
                            console.log("Archivo seleccionado:", this.formData.file);
                            // Aquí puedes realizar las acciones necesarias con el archivo.
                        } else {
                            console.log("Ningún archivo seleccionado.");
                        }
                        this.showUploadModal = false;
                    },
                }));

                //contacts
                Alpine.data('contacts', () => ({
                    defaultParams: {
                        id: null,
                        nombre: '',
                        email: '',
                        minor: '',
                        numero: '',
                        cohorte: '',
                        birthday: '',
                    },
                    displayType: 'list',
                    addContactModal: false,
                    params: {
                        id: null,
                        nombre: '',
                        email: '',
                        minor: '',
                        numero: '',
                        cohorte: '',
                        birthday: '',
                    },
                    filterdContactsList: [],
                    searchUser: '',
                    contactList: [{
                            id: 1,
                            path: 'profile-35.png',
                            nombre: 'Joel Melvin Ramos Soto',
                            email: 'joel.ramos4@upr.edu',
                            minor: 'Web Design',
                            consejeria: 'Realizada',
                            numero: '840-22-5677',
                            cohorte: 2022,
                            priority: 'activo',
                            birthday: '2013-09-12',
                        },
                        {
                            id: 2,
                            path: 'profile-35.png',
                            nombre: 'Melissa Diaz Gonzalez',
                            email: 'melissa.diaz10@upr.edu',
                            minor: '',
                            consejeria: 'No realizada',
                            numero: '840-23-1290',
                            cohorte: 2022,
                            priority: 'activo',
                            birthday: '2014-05-10',
                        },
                        {
                            id: 3,
                            path: 'profile-35.png',
                            nombre: 'Melvin Raúl Lopez Reyes',
                            email: 'melvin.lopez2@upr.edu',
                            minor: 'Web Design',
                            consejeria: 'Realizada',
                            numero: '840-22-2345',
                            cohorte: 2022,
                            priority: 'activo',
                            birthday: '2015-03-03',
                        },
                        {
                            id: 4,
                            path: 'profile-35.png',
                            nombre: 'Jean Deida Quinones',
                            email: 'jean.deida3@upr.edu',
                            minor: '',
                            consejeria: 'Realizada',
                            numero: '840-23-1256',
                            cohorte: 2022,
                            priority: 'activo',
                            birthday: '2004-09-12',
                        },
                        {
                            id: 5,
                            path: 'profile-35.png',
                            nombre: 'Natalia Marta Zapato Monterrey',
                            email: 'natalia.zapato@upr.du',
                            minor: 'Web Design',
                            consejeria: 'Realizada',
                            numero: '840-23-1278',
                            cohorte: 2022,
                            priority: 'activo',
                            birthday: '2006-04-12',
                        },
                    ],

                    init() {
                        this.searchContacts();
                    },

                    searchContacts() {
                        this.filterdContactsList = this.contactList.filter((d) => d.nombre.toLowerCase().includes(this.searchUser.toLowerCase()));
                    },

                    editUser(user) {
                        this.params = this.defaultParams;
                        if (user) {
                            this.params = JSON.parse(JSON.stringify(user));
                        }

                        this.addContactModal = true;
                    },

                    saveUser() {
                        if (!this.params.nombre) {
                            this.showMessage('Name is required.', 'error');
                            return true;
                        }
                        if (!this.params.email) {
                            this.showMessage('Email is required.', 'error');
                            return true;
                        }
                        if (!this.params.numero) {
                            this.showMessage('Number is required.', 'error');
                            return true;
                        }

                        if (this.params.id) {
                            //update user
                            let user = this.contactList.find((d) => d.id === this.params.id);
                            user.nombre = this.params.nombre,
                                user.nombre2 = this.params.nombre2,
                                user.apellidoP = this.params.apellidoP,
                                user.apellidoM = this.params.apellidoM,
                                user.email = this.params.email;
                            user.minor = this.params.minor;
                            user.numero = this.params.numero;
                            user.cohorte = this.params.cohorte;
                            user.consejeria = 'No realizada';
                            user.priority = 'activo';
                            user.birthday = this.params.birthday;
                        } else {
                            //add user
                            let maxUserId = this.contactList.length ?
                                this.contactList.reduce((max, character) => (character.id > max ? character.id : max), this.contactList[0].id) :
                                0;

                            let user = {
                                id: maxUserId + 1,
                                path: 'profile-35.png',
                                nombre: this.params.nombre,
                                nombre2: this.params.nombre2,
                                apellidoP: this.params.apellidoP,
                                apellidoM: this.params.apellidoM,
                                email: this.params.email,
                                minor: this.params.minor,
                                numero: this.params.numero,
                                cohorte: this.params.cohorte,
                                consejeria: 'No realizada',
                                priority: 'activo',
                                birthday: this.params.birthday,
                            };
                            this.contactList.splice(0, 0, user);
                            this.searchContacts();
                        }

                        this.showMessage('User has been saved successfully.');
                        this.addContactModal = false;
                    },

                    deleteUser(user) {
                        this.contactList = this.contactList.filter((d) => d.id != user.id);
                        // this.ids = this.ids.filter((d) => d != user.id);
                        this.searchContacts();
                        this.showMessage('User has been deleted successfully.');
                    },

                    setDisplayType(type) {
                        this.displayType = type;
                    },

                    showMessage(msg = '', type = 'success') {
                        const toast = window.Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                        });
                        toast.fire({
                            icon: type,
                            title: msg,
                            padding: '10px 20px',
                        });
                    },

                    tabChanged(type) {
                        this.selectedTab = type;
                        this.searchContacts();
                        this.isShowTaskMenu = false;
                    },

                    setPriority(contact, nombre) {
                        let item = this.filterdContactsList.find((d) => d.id === contact.id);
                        item.priority = nombre;
                        this.searchContacts(false);
                    },
                }));
            });
        </script>
</body>

</html>
