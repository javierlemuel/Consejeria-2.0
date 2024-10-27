<?php
// Generate pagination by using the same link but with added p value
function generate_pagination($currPg = 1, $maxPg = 3)
{
    $linkValues = '';


    foreach ($_GET as $key => $value) {
        if ($key != 'p') {
            $linkValues = $linkValues . htmlspecialchars($key) . '=' . htmlspecialchars($value) . '&';
        }
    }

    echo '<div class="pagination my-5">
    <ul class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto">';

    echo '<li><a href="?' . $linkValues;

    echo 'p=' . ($currPg > 1 ? $currPg - 1 : 1) . '">
        <button class="flex justify-center font-semibold px-3.5 py-2 rounded transition bg-white-light text-dark hover:text-white hover-bg-primary dark-text-white-light dark-bg-[#191e3a] dark-hover-bg-primary">
            <svg xmlns="http://w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
        </button>
        </a>
        </li>';

    for ($i = 1; $i <= $maxPg; $i++) {
        $btnColor = ($i == $currPg) ? 'bg-primary text-white' : 'bg-white-light text-dark hover-text-white hover-bg-primary dark-text-white-light dark-bg-[#191e3a] dark-hover-bg-primary';
        echo '<li><a href="?' . $linkValues;
        // Preserve existing GET parameters except 'p'
        echo 'p=' . $i . '"><button class="flex justify-center font-semibold px-3.5 py-2 rounded
         transition ' . $btnColor . '">' . $i . '</button></a></li>';
    }

    echo '<li><a href="?' . $linkValues;

    echo 'p=' . ($currPg < $maxPg ? $currPg + 1 : $maxPg) . '">
        <button class="flex justify-center font-semibold px-3.5 py-2 rounded transition bg-white-light text-dark hover:text-white hover-bg-primary dark-text-white-light dark-bg-[#191e3a] dark-hover-bg-primary">
            <svg xmlns="http://w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </button>
        </a>
        </li>';

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
