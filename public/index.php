<?php
require_once '../Init.php';

try {
    Init::boot("../src/*");

    $application = new TasksApplication();
    $application->start();
} catch (AccessException $e) {
    http_response_code(403);
    echo $e->getMessage();
} catch (\Throwable $e) {
    http_response_code(500);
    echo '500 Internal Server Error';
}
