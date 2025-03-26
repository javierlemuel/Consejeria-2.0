<!-- start sidebar section -->
<div :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav x-data="sidebar" class="sidebar fixed top-0 bottom-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3 mt-1 mb-2">
                <a href="index.php?page=counseling" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none" src="assets/images/university.png" alt="image" />
                    <span style='font-size: 16px' class="align-middle text-2xl font-bold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">CONSEJERÍ­­­A UPRA</span>
                </a>
                <a href="javascript:;" class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10" @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold" x-data="{ activeDropdown: 'users' }">

                <a onclick="showinfo()" style="cursor:pointer">
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-bold uppercase dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- <line x1="5" y1="12" x2="19" y2="12"></line> -->
                        </svg>
                        <span><?php 
                        if (isset($_SESSION['full_student_name']))
                            echo $_SESSION['full_student_name'] ?></span>
                    </h2>
                </a>

                <form method="post" id="counseling_form" action="controllers/counselingController.php" class="mh-100">

                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold  dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- <line x1="5" y1="12" x2="19" y2="12"></line> -->
                        </svg>
                        <span>Concentración</span>
                    </h2>
                    <ul id="concentracion"></ul>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold  dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- <line x1="5" y1="12" x2="19" y2="12"></line> -->
                        </svg>
                        <span>Generales</span>
                    </h2>
                    <ul id="generales"></ul>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold  dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- <line x1="5" y1="12" x2="19" y2="12"></line> -->
                        </svg>
                        <span>Humanidades</span>
                    </h2>
                    <ul id="humanidades"></ul>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold  dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- <line x1="5" y1="12" x2="19" y2="12"></line> -->
                        </svg>
                        <span>Ciencias Sociales</span>
                    </h2>
                    <ul id="cienciasSociales"></ul>
                    <h2 class="-mx-4 mb-1 flex items-center bg-white-light/30 py-3 px-7 font-extrabold  dark:bg-dark dark:bg-opacity-[0.08]">
                        <svg class="hidden h-5 w-4 flex-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <!-- <line x1="5" y1="12" x2="19" y2="12"></line> -->
                        </svg>
                        <span>Otras</span>
                    </h2>
                    <ul id="otras"></ul>

                    <?php
                    //if the student conducted the counseling the button will be disable
                    if (isset($_SESSION['counseling_button']))
                        echo $_SESSION['counseling_button'];

                    ?>

                </form>

            </ul>
        </div>
    </nav>

    <!-- modal -->
    <div id="confirmModal" style="display: none;">
        <div class="fixed inset-0 bg-[black]/60 z-[999] overflow-y-auto" :class="open && '!block'">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-transition x-transition.duration.300 class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Confirmar Consejería</h5>
                        <button type="button" class="text-white-dark hover:text-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="dark:text-white-dark/70 text-base font-medium text-[#1f2937]">
                            <p>¿Estás seguro de que quieres confirmar la consejería?</p>
                        </div>
                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger" id="confirmNo">Cancelar</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4" id="confirmYes">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end sidebar section -->
<script src="assets/js/alpine-collaspe.min.js"></script>
<script src="assets/js/alpine-persist.min.js"></script>
<script defer src="assets/js/alpine-ui.min.js"></script>
<script defer src="assets/js/alpine-focus.min.js"></script>
<script defer src="assets/js/alpine.min.js"></script>
<script src="assets/js/custom.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>

    function checkForConfirmDialog() {
        console.log('checking for dialog');
        if (sessionStorage.getItem('showConfirmDialog')) {
            console.log('show dialog is true and must delete entry');
            sessionStorage.removeItem('showConfirmDialog');
            showAlert();
        }
    }

    async function showAlert() {
        new window.Swal({
            icon: 'success',
            title: 'Consejería Confirmada!',
            text: '',
            padding: '2em',
        });
    }

    function counselingButton() {
        $('#counseling_form').submit(function(event) {
            //stop the form to be submited
            event.preventDefault();
            var modal = document.getElementById("confirmModal");
            modal.style.display = "block";

            document.getElementById("confirmYes").addEventListener("click", function() {
                // Submit form``
                sessionStorage.setItem('showConfirmDialog', true);
                document.getElementById("counseling_form").submit();
                modal.style.display = "none";
            });

            // Function to handle "No" button click
            document.getElementById("confirmNo").addEventListener("click", function() {
                // Close the modal
                modal.style.display = "none";
            });

        });



        //if the Confirmar Consejeria buttton is disabled, disable the checkbox input and the remove course option
        if ($('#counseling_button').prop('disabled')) {
            //$(this).prop("disabled", true);
            //$('input[type="checkbox"]').prop('disabled', true);
            $('#counseling_form a[onClick]').remove();
        }
    }
    //clear course takes the course to be remove 
    const clearCourse = (course) => {

        //retrieve the list of courses stored in sessionStorage
        let selectedCourses = JSON.parse(sessionStorage.getItem('selectedCourses'))

        //remove the li element that correspong to the course to be removed
        console.log("course to remove: ", course);
        if (typeof course == 'object') {
            course = course.id;
        }
        $('#' + course).remove();

        //uncheck the checkbox of te removed course
        const index = selectedCourses.indexOf(course);
        let checkbox = $(`input[type="checkbox"][value=${course}]`);
        if (index > -1) {
            //uncheck el checkbox de la lista
            checkbox.prop('checked', false);
            console.log("el checkbox unchecked: ", checkbox);
            selectedCourses.splice(index, 1);
        }

        //remove the course form the list in the sessionStorage
        selectedCourses = selectedCourses.filter(item => item !== course);
        sessionStorage.setItem('selectedCourses', JSON.stringify(selectedCourses));
    }



    $(document).ready(() => {
        const generales = ['MATE', 'INGL', 'CIBI', 'ESPA', 'FISI'];
        console.log("generales: ", generales);

        if (<?php if (isset($_SESSION['conducted_counseling']))
                    echo $_SESSION['conducted_counseling'];
                  else {
                    echo '0';
                  } ?> == 1) {
            var courseList = <?php echo $_SESSION['selectedCourses'] ?>;
            console.log("courses db: ", courseList);
        } else {
            var courseList = JSON.parse(sessionStorage.getItem('selectedCourses'));
            console.log("courses session storage: ", courseList);
        }

        // unset($_SESSION['conducted_counseling']);

        // unset($_SESSION['counseling_button']);

        //retrieve the list of courses in session storage 
        if (courseList.length > 0) {
            //por cada checkbox seleccionado
            courseList.forEach((selectedCourse) => {
                //si la clase no existe en el array de clases seleccionadas la anade al array y al sidebar

                console.log("each selected course: ", selectedCourse);
                const courseCode = selectedCourse;
                console.log('general: ', courseCode);



                let category = '';
                if (courseCode.startsWith("CCOM")) {
                    category = $('#concentracion');
                } else if (generales.some(substr => courseCode.startsWith(substr))) {
                    category = $('#generales');
                } else if (courseCode.startsWith("HUMA")) {
                    category = $('#humanidades');
                } else if (courseCode.startsWith("CISO")) {
                    category = $('#cienciasSociales');
                } else {
                    category = $('#otras');
                }
                let html = `<li id="${courseCode}">
                            <h3 style="font-size: 12px;" class="justify-between -mx-4 mb-2 flex items-center  py-3 px-7 font uppercase dark:bg-dark dark:bg-opacity-[0.08]" style="text-size: 14px;">
                            ${courseCode}
                            <a onclick="clearCourse(${courseCode})"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M11.5956 22.0001H12.4044C15.1871 22.0001 16.5785 22.0001 17.4831 21.1142C18.3878 20.2283 18.4803 18.7751 18.6654 15.8686L18.9321 11.6807C19.0326 10.1037 19.0828 9.31524 18.6289 8.81558C18.1751 8.31592 17.4087 8.31592 15.876 8.31592H8.12405C6.59127 8.31592 5.82488 8.31592 5.37105 8.81558C4.91722 9.31524 4.96744 10.1037 5.06788 11.6807L5.33459 15.8686C5.5197 18.7751 5.61225 20.2283 6.51689 21.1142C7.42153 22.0001 8.81289 22.0001 11.5956 22.0001Z" fill="currentColor" />
                                <path d="M3 6.38597C3 5.90152 3.34538 5.50879 3.77143 5.50879L6.43567 5.50832C6.96502 5.49306 7.43202 5.11033 7.61214 4.54412C7.61688 4.52923 7.62232 4.51087 7.64185 4.44424L7.75665 4.05256C7.8269 3.81241 7.8881 3.60318 7.97375 3.41617C8.31209 2.67736 8.93808 2.16432 9.66147 2.03297C9.84457 1.99972 10.0385 1.99986 10.2611 2.00002H13.7391C13.9617 1.99986 14.1556 1.99972 14.3387 2.03297C15.0621 2.16432 15.6881 2.67736 16.0264 3.41617C16.1121 3.60318 16.1733 3.81241 16.2435 4.05256L16.3583 4.44424C16.3778 4.51087 16.3833 4.52923 16.388 4.54412C16.5682 5.11033 17.1278 5.49353 17.6571 5.50879H20.2286C20.6546 5.50879 21 5.90152 21 6.38597C21 6.87043 20.6546 7.26316 20.2286 7.26316H3.77143C3.34538 7.26316 3 6.87043 3 6.38597Z" fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.42543 11.4815C9.83759 11.4381 10.2051 11.7547 10.2463 12.1885L10.7463 17.4517C10.7875 17.8855 10.4868 18.2724 10.0747 18.3158C9.66253 18.3592 9.29499 18.0426 9.25378 17.6088L8.75378 12.3456C8.71256 11.9118 9.01327 11.5249 9.42543 11.4815Z" fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5747 11.4815C14.9868 11.5249 15.2875 11.9118 15.2463 12.3456L14.7463 17.6088C14.7051 18.0426 14.3376 18.3592 13.9254 18.3158C13.5133 18.2724 13.2126 17.8855 13.2538 17.4517L13.7538 12.1885C13.795 11.7547 14.1625 11.4381 14.5747 11.4815Z" fill="currentColor" />
                            </svg></a>
                            </h3>
                            <input type="hidden" name="selectedCoursesList[]" value="${courseCode}">
                            </li>`;
                category.append(html);

                $(`input[type="checkbox"][value=${selectedCourse}]`).prop("checked", true);


            });
        }




        // //if the Confirmar Consejeria buttton is disabled, disable the checkbox input and the remove course option
        if ($('#counseling_button').prop('disabled')) {
            $('input[type="checkbox"]').prop('disabled', true);
        }
        counselingButton();
        checkForConfirmDialog();
    });
</script>