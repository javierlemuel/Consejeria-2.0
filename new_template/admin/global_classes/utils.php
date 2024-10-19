<?php

function generate_pagination($currPg = 1, $maxPg = 3){
    echo '<div class="pagination">
    <ul class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto">';
    
    for ($i = 1; $i <= $maxPg; $i++) {
        $btnColor = ($i == $currPg) ? 'bg-primary text-white' : 'bg-white-light text-dark hover-text-white hover-bg-primary dark-text-white-light dark-bg-[#191e3a] dark-hover-bg-primary';
        echo "<li>";
        echo '<a href="?';
        // Preserve existing GET parameters except 'p'
        foreach ($_GET as $key => $value) {
            if ($key != 'p') {
                echo htmlspecialchars($key) . '=' . htmlspecialchars($value) . '&';
            }
        }
        echo 'p=' . $i . '">';

        echo '<button class="flex justify-center font-semibold px-3.5 py-2 rounded
         transition ' . $btnColor . '">';
        echo $i;
        echo '</button></a></li>';


    }   
    echo '</ul></div>'; 
}
