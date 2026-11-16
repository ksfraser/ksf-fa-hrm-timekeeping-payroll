<?php

require_once __DIR__ . '/bootstrap.php';

$path_to_root = '../..';

$frontController = new \Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\App\PayrollIntegrationFrontController(
    new \Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\App\PayrollIntegrationAppShell(),
    'SA_PAYROLL_VIEW',
    $path_to_root
);

$page_security = $frontController->getSecurityArea();
include_once($path_to_root . "/includes/session.inc");
add_access_extensions();

include_once($path_to_root . "/includes/ui.inc");

$frontController->run();
