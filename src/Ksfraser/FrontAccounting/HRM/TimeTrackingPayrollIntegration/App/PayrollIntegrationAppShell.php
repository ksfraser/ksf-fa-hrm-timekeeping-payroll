<?php

declare(strict_types=1);

namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\App;

use ksfraser\FrontAccounting\Common\App\AbstractAppShell;
use ksfraser\FrontAccounting\Common\Menu\FAModuleMenu;
use Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Controller\PayrollIntegrationTabController;

class PayrollIntegrationAppShell extends AbstractAppShell
{
    public function __construct()
    {
        parent::__construct('payroll', 'index.php', 'view', 'payroll_integration');

        $this->add_module(_("Payroll"));

        $menu = new FAModuleMenu(
            'modules/ksf_FA_HRM_TimeTrackingPayrollIntegration/index.php',
            'view',
            ''
        );

        $menu->addItem('payroll_integration', _("Payroll Integration"), MENU_INQUIRY)
             ->registerWithApp($this, 'SA_PAYROLL_VIEW');

        $this->add_extensions();
    }
}
