<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\App;

use ksfraser\FrontAccounting\Common\App\AbstractFrontController;

class PayrollIntegrationFrontController extends AbstractFrontController
{
    public function __construct(
        PayrollIntegrationAppShell $appShell,
        string $fallbackSecurityArea,
        string $path_to_root
    ) {
        parent::__construct($appShell, $fallbackSecurityArea, $path_to_root);
    }
}
