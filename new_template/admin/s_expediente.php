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
                    <div class="relative flex w-full items-center bg-warning px-5 py-2.5 dark:bg-[#0e1726]">
                        <div class="horizontal-logo flex items-center justify-between ltr:mr-2 rtl:ml-2 lg:hidden">
                            <a href="index.html" class="main-logo flex shrink-0 items-center">
                                <img class="inline w-8 ltr:-ml-1 rtl:-mr-1" src="assets/images/university.png" alt="image" />
                                <span class="hidden align-middle text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">CONSEJERIA UPRA</span>
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


                            <div class="dropdown" x-data="dropdown" @click.outside="open = false">
                                <a href="javascript:;" class="block rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60" @click="toggle">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M22 10C22.0185 10.7271 22 11.0542 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <path d="M6 8L8.1589 9.79908C9.99553 11.3296 10.9139 12.0949 12 12.0949C13.0861 12.0949 14.0045 11.3296 15.8411 9.79908" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        <circle cx="19" cy="5" r="3" stroke="currentColor" stroke-width="1.5" />
                                    </svg>
                                </a>
                                <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="top-11 w-[300px] !py-0 text-xs text-dark ltr:-right-16 rtl:-left-16 dark:text-white-dark sm:w-[375px] sm:ltr:-right-2 sm:rtl:-left-2">
                                    <li class="mb-5">
                                        <div class="relative overflow-hidden rounded-t-md !p-5 text-white">
                                            <div class="absolute inset-0 h-full w-full bg-[url('../images/menu-heade.jpg')] bg-cover bg-center bg-no-repeat"></div>
                                            <h4 class="relative z-10 text-lg font-semibold">Messages</h4>
                                        </div>
                                    </li>
                                    <template x-for="msg in messages">
                                        <li>
                                            <div class="flex items-center px-5 py-3" @click.self="toggle">
                                                <div x-html="msg.image"></div>
                                                <span class="px-3 dark:text-gray-500">
                                                    <div class="text-sm font-semibold dark:text-white-light/90" x-text="msg.title"></div>
                                                    <div x-text="msg.message"></div>
                                                </span>
                                                <span class="whitespace-pre rounded bg-white-dark/20 px-1 font-semibold text-dark/60 ltr:ml-auto ltr:mr-2 rtl:mr-auto rtl:ml-2 dark:text-white-dark" x-text="msg.time"></span>
                                                <button type="button" class="text-neutral-300 hover:text-danger" @click="removeMessage(msg.id)">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                                        <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </li>
                                    </template>
                                    <template x-if="messages.length">
                                        <li class="mt-5 border-t border-white-light text-center dark:border-white/10">
                                            <div class="group flex cursor-pointer items-center justify-center px-4 py-4 font-semibold text-primary dark:text-gray-400" @click="toggle">
                                                <span class="group-hover:underline ltr:mr-1 rtl:ml-1">VIEW ALL ACTIVITIES</span>
                                                <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1 ltr:ml-1 rtl:mr-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </li>
                                    </template>
                                    <template x-if="!messages.length">
                                        <li class="mb-5">
                                            <div class="!grid min-h-[200px] place-content-center text-lg hover:!bg-transparent">
                                                <div class="mx-auto mb-4 rounded-full text-primary ring-4 ring-primary/30">
                                                    <svg width="40" height="40" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M20 10C20 4.47715 15.5228 0 10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10Z" fill="currentColor" />
                                                        <path d="M10 4.25C10.4142 4.25 10.75 4.58579 10.75 5V11C10.75 11.4142 10.4142 11.75 10 11.75C9.58579 11.75 9.25 11.4142 9.25 11V5C9.25 4.58579 9.58579 4.25 10 4.25Z" fill="currentColor" />
                                                        <path d="M10 15C10.5523 15 11 14.5523 11 14C11 13.4477 10.5523 13 10 13C9.44772 13 9 13.4477 9 14C9 14.5523 9.44772 15 10 15Z" fill="currentColor" />
                                                    </svg>
                                                </div>
                                                No data available.
                                            </div>
                                        </li>
                                    </template>
                                </ul>
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
                                        <a href="login.php" class="!py-3 text-danger" @click="toggle">
                                            <svg class="h-4.5 w-4.5 rotate-90 ltr:mr-2 rtl:ml-2" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.5" d="M17 9.00195C19.175 9.01406 20.3529 9.11051 21.1213 9.8789C22 10.7576 22 12.1718 22 15.0002V16.0002C22 18.8286 22 20.2429 21.1213 21.1215C20.2426 22.0002 18.8284 22.0002 16 22.0002H8C5.17157 22.0002 3.75736 22.0002 2.87868 21.1215C2 20.2429 2 18.8286 2 16.0002L2 15.0002C2 12.1718 2 10.7576 2.87868 9.87889C3.64706 9.11051 4.82497 9.01406 7 9.00195" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M12 15L12 2M12 2L15 5.5M12 2L9 5.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            Sign Out
                                        </a>
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
                <!-- <div class="mb-5 flex flex-col sm:flex-row" x-data="{ tab: '2017'}"> Cambiamos 'tab' a 'info' por defecto -->
                <!-- buttons -->
                <!-- <div class="mx-10 mb-5 sm:mb-0">
                    <ul class="w-24 m-auto text-center font-semibold">
                        <li>
                            </li>
                        <li>
                            <a href="javascript:" class="p-3.5 py-4 -mb-[1px] block ltr:border-r rtl:border-l border-white-light dark:border-[#191e3a] relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-[1px] before:bottom-0 before:top-0 ltr:before:-right-[1px] rtl:before:-left-[1px] before:m-auto before:h-0 hover:before:h-[80%] before:bg-secondary" :class="{'text-secondary before:!h-[80%]' : tab === '2017'}" @click="tab='2017'">2017</a>
                        <li>
                            <a href="javascript:" class="p-3.5 py-4 -mb-[1px] block ltr:border-r rtl:border-l border-white-light dark:border-[#191e3a] relative before:transition-all before:duration-700 hover:text-secondary before:absolute before:w-[1px] before:bottom-0 before:top-0 ltr:before:-right-[1px] rtl:before:-left-[1px] before:m-auto before:h-0 before:bg-secondary hover:before:h-[80%]" :class="{'text-secondary before:!h-[80%]' : tab === '2022'}" @click="tab='2022'">2022</a>
                        </li>
                    </ul>
                </div> -->

                <!-- description -->
                <div class="flex-1 text-sm ">
                    <div>
    <!-- start cohort 2017 -->
    <div x-data="{ tab1: null }">
    <!-- start cohort 2017 -->
    <?php
    $path = 's_expediente.json';
    $jsonString = file_get_contents($path);
    $jsonData = json_decode($jsonString, true);

    echo "<div class='mb-5'>
            <!-- tabs-años -->
            <div>
                <ul class='flex flex-wrap mt-3 mb-5 border-b border-white-light dark:border-[#191e3a]'>";

    // Generate the outer year tabs
    foreach ($jsonData['Year'] as $yearTitle => $yearData) {
        if ($yearTitle !== 'Electivas')
        {
            echo "<li>
                    <a href='javascript:' class='p-5 py-3 -mb-[1px] flex items-c​‌​enter hover:border-b border-transparent hover:!border-secondary hover:text-secondary' 
                        :class='{ \"border-b !border-secondary text-secondary active\": tab1 === \"$yearTitle\" }' 
                        @click='tab1 = \"$yearTitle\"'>
                        " . strtoupper($yearTitle) . " AÑO
                    </a>
                </li>";
        }
        else{
            echo "<li>
                    <a href='javascript:' class='p-5 py-3 -mb-[1px] flex items-c​‌​enter hover:border-b border-transparent hover:!border-secondary hover:text-secondary' 
                        :class='{ \"border-b !border-secondary text-secondary active\": tab1 === \"$yearTitle\" }' 
                        @click='tab1 = \"$yearTitle\"'>
                        " . strtoupper($yearTitle) . "
                    </a>
                </li>";
        }
    }

    echo "</ul>
        </div>";

    // Display data for the selected year
    foreach ($jsonData['Year'] as $yearTitle => $yearData) {
        echo "<div x-show='tab1 === \"$yearTitle\"'>
                ";

        

        foreach ($yearData as $semesterTitle => $semesterCourses) {
            echo "<div class='mb-2 p-2 bg-gray-200 text-gray-700 rounded-md'>
                    " . strtoupper($semesterTitle);
                    
            if($semesterTitle !== ""){
                echo" SEMESTRE
                </div>";
            }
            else{
                echo"</div>";
            }

                echo "<table>
                <thead>
                    <tr>
                        <th></th>
                        <th>CODIGO</th>
                        <th>NOMBRE</th>
                        <th>CREDITOS</th>
                        <th>NOTA</th>
                        <th>SEMESTRE</th>
                        <th>EQUIVALENCIA</th>
                    </tr>
                </thead>
                <tbody>";
            foreach ($semesterCourses as $course) {
                echo "<tr>
                        <td></td>
                        <td class='whitespace-normal'>" . $course['code'] . "</td>
                        <td>" . $course['name'] . "</td>
                        <td>" . $course['credits'] . "</td>
                        <td>" . $course['nota'] . "</td>
                        <td>" . $course['semestre'] . "</td>
                        <td> <input type='text' style='border: 5px' placeholder='". $course['equivalencia']. "'> </td>
                    </tr>";
            }
        

        echo "</tbody>
            </table>";
        }        
        echo "</div>";
    }
    ?>
</div>






<form action="index.php" class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div>
            <label for="gridCode">Nombre</label>
            <input id="gridCode" type="text" placeholder="Raul Jimenez" class="form-input" readonly/>
        </div>
        <div>
            <label for="gridName">Email</label>
            <input id="gridName" type="text" placeholder="raul.jimenez3@upr.edu" class="form-input" readonly/>
        </div>
        <div>
        <label for="gridCred">Número de estudiante</label>
        <input id="gridCred" type="number" placeholder="840-22-1240" class="form-input" readonly />
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div>
        <label for="gridYear">Año</label>
        <input id="gridYear" type="number" placeholder="1" class="form-input" readonly />
        </div>
        <div>
        <label for="gridSem">Semestre</label>
        <input id="gridSem" type="number" placeholder="1" class="form-input" readonly />
        </div>
        <div>
            <label for="gridType">Minor</label>
            <select id="gridType" class="form-select text-white-dark">
                <option></option>
                <option>Web Design</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary !mt-6">Someter</button>
</form>

</div>

                        
</div>          
                        
                    <!-- forms grid -->

                </div>

                
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
        $(document).ready(function(){
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