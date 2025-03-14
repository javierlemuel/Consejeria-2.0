<!-- start header section -->
<header class="z-40" :class="{'dark' : $store.app.semidark && $store.app.menu === 'horizontal'}">
    <div class="shadow-sm">
        <div class="relative flex w-full items-center  px-5 py-2.5 dark:bg-[#0e1726]" style="background-color: #2b2b2b;">
            <div class="horizontal-logo flex items-center justify-between ltr:mr-2 rtl:ml-2 lg:hidden">
                <a href="index.php?page=counseling" class="main-logo flex shrink-0 items-center">
                    <img class="inline w-8 ltr:-ml-1 rtl:-mr-1" src="assets/images/university.png" alt="image" />
                    <span class="hidden align-middle text-white text-2xl font-semibold transition-all duration-300 ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light md:inline">CONSEJERIA UPRA</span>
                </a>

                <a href="javascript:;" class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden" @click="$store.app.toggleSidebar()">

                    <svg class="m-auto h-5 w-5" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <div class="hidden ltr:mr-2 rtl:ml-2 sm:block topnav" id="navbar">
                <!-- <ul class="flex items-center space-x-4 rtl:space-x-reverse text-white-light dark:text-[#d0d2d6]"> -->
                <ul class="hidden lg:flex lg:mx-auto lg:flex lg:items-center lg:w-auto lg:space-x-6 items-center space-x-4 rtl:space-x-reverse text-white-light dark:text-[#d0d2d6]">
                    <li>
                        <a href="index.php?page=counseling" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent text-lg font-bold">
                            Consejería
                        </a>
                    </li>

                    <li>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown">
                            <button class="btn-link hover:text-primary  hover:border-primary  text-lg font-bold relative" @click="toggle">
                                Secuencia Curricular<!--<span class="dropdown-arrow"></span>-->
                            </button>
                            <?php
                            //var_dump($_SESSION['cohortes']);
                            ?>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0 whitespace-nowrap">
                                <?php
                                foreach ($_SESSION['cohortes'] as $year) {
                                    echo '<li><a href="index.php?cohort=' . $year . '" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent text-lg font-bold">Cohorte' . $year . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </li>

                    <li>
                        <a href="index.php?page=expediente" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent text-lg font-bold">
                            Expediente
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=links" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent  text-lg font-bold">
                            Enlaces Frecuentes
                        </a>
                    </li>
                </ul>

            </div>


            <div x-data="header" class="flex items-center space-x-1.5 ltr:ml-auto rtl:mr-auto rtl:space-x-reverse dark:text-[#d0d2d6] sm:flex-1 ltr:sm:ml-0 sm:rtl:mr-0 lg:space-x-2">
                <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }" @click.outside="search = false">
                </div>


                <!-- <a href="javascript:;" class="collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary lg:hidden" @click="$store.app.toggleSidebar()">

                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </a> -->

                <div class="lg:hidden ">
                    <button id="toggleButton" class="navbar-burger flex items-center text-blue-600 collapse-icon flex flex-none rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 hover:text-primary ltr:ml-2 rtl:mr-2 dark:bg-dark/40 dark:text-[#d0d2d6] dark:hover:bg-dark/60 dark:hover:text-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 7L4 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path opacity="0.5" d="M20 12L4 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M20 17L4 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
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
                        <li>
                            <div class="relative overflow-hidden rounded-t-md !p-5 text-white">
                                <div class="absolute inset-0 h-full w-full bg-warning bg-cover bg-center bg-no-repeat"></div>
                                <h4 class="relative z-10 text-lg font-semibold">Mensajes</h4>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center px-4 py-3 mb-2" @click.self="toggle">
                                <span class="px-1 dark:text-gray-500">

                                    <?php
                                    if ($_SESSION['student_note'] == NULL) {
                                        //var_dump($_SESSION);
                                        echo '<div class="text-sm font-semibold dark:text-white-light/90">No tienes mensajes.</div>';
                                    } else {
                                        // var_dump($_SESSION);
                                        echo '<div class="text-sm font-semibold dark:text-white-light/90">Dra. Valenzuela, Consejera</div>
                                        <div><div>';
                                        echo $_SESSION['student_note'];
                                        echo '</div>';
                                    }
                                    ?>
                                </span>
                                <!-- <span class="whitespace-pre rounded bg-white-dark/20 px-1 font-semibold text-dark/60 ltr:ml-auto ltr:mr-2 rtl:mr-auto rtl:ml-2 dark:text-white-dark" x-text="msg.time"></span> -->
                                <!-- <button type="button" class="text-neutral-300 hover:text-danger" @click="removeMessage(msg.id)">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                                        <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                </button> -->
                            </div>
                        </li>
                        <template>
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
        <!-- Responsive Navbar -->

        <ul id="responsiveNav" class="hidden lg:hidden bg-gray-800 py-4 px-6 text-white relative flex w-full items-center  px-5 py-2.5" style="background-color: #2b2b2b;">
            <li>
                <a href="index.php?page=counseling" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent text-lg font-bold">
                    Consejería
                </a>
            </li>

            <li>
                <div x-data="{ active: null }">
                    <div>
                        <button type="button" class="block p-2 text-white  dark:bg-dark/40 border-b border-transparent text-lg font-bold" x-on:click="active === 1 ? active = null : active = 1">Secuencia Curricular

                        </button>
                        <div x-cloak x-show="active === 1" x-collapse>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0 whitespace-nowrap">
                                <?php
                                foreach ($_SESSION['cohortes'] as $year) {
                                    echo '<li><a href="index.php?cohort=' . $year . '" class="block p-2 ml-3 hover:text-primary dark:bg-dark/40  border-b border-transparent text-lg">Cohorte' . $year . '</a></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>

            <li>
                <a href="index.php?page=expediente" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent text-lg font-bold">
                    Expediente
                </a>
            </li>
            <li>
                <a href="index.php?page=links" class="block p-2 hover:text-primary dark:bg-dark/40 dark:hover:bg-dark/60 border-b border-transparent  text-lg font-bold">
                    Enlaces Frecuentes
                </a>
            </li>
        </ul>



    </div>

</header>
<!-- end header section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Toggle responsive navbar links on button click
        $("#toggleButton").click(function() {
            $("#responsiveNav").toggle();
        });
    });
</script>
