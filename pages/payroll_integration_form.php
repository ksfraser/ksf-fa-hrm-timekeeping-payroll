<?php
declare(strict_types=1);

use Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Controller\PayrollIntegrationTabController;

$path_to_root = '../..';

require_once($path_to_root . '/includes/session.inc');

if (!function_exists('page_security')) {
    define('page_security', 'SA_HRM_PAYROLL_MANAGE');
}

$controller = new PayrollIntegrationTabController(null, array());
$controller->renderForm();
