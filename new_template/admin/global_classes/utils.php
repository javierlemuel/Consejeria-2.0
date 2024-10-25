<?php
// Generate pagination by using the same link but with added p value
function generate_pagination($currPg = 1, $maxPg = 3)
{
    echo '<div class="pagination my-5">
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

// Check if string is a course code, e.g: CCOM3001
function isValidCode($cd)
{
    return preg_match("/^[A-Z]{4}[0-9]{4}$/", $cd);
}

// Check if it's a grade
function isValidGrade($grd)
{
    return preg_match("/^(A|B|C|D|F|IC|W|W\*|P|NP)$/", $grd);
}

// Removes anything that isn't a letter or number
function sanitizeSearch($srch)
{
    return preg_replace("/\[^a-zA-Z0-9\\s\]/", "", $srch);
}
