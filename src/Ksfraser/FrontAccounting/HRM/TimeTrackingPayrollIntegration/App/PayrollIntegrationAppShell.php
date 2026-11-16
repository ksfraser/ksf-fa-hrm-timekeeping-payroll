<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\App\TabRegistration;

class PayrollIntegrationAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('payroll', 'index.php', 'view', 'payroll_integration');

        $this->registerTab(new TabRegistration(
            'payroll_integration',
            'Payroll Integration',
            'SA_PAYROLL_VIEW',
            null,
            10,
            0,
            null,
            null,
            array()
        ));
    }
}
