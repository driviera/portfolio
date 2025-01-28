<?php

// JSON response to /js/ajax.js request

$script = file('script.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (!empty($_GET['action']) && $_GET['action'] == 'next') {
    header('Content-Type: application/json');
    $active = $_GET['active'] ?? 0;
    if ($active < 0) $active = 0;
    $active = ($active >= count($script)) ? 0 : $active;
    $next = ($active >= count($script)) ? 0 : $active + 1;
    $output = ['data' => $script[$active], 'active' => $next];
    echo json_encode($output);
    exit;
}

exit;
