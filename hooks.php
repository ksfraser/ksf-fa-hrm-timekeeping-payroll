<?php
declare(strict_types=1);
require_once __DIR__ . '/ComposerDependencies.php';
\Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Utils\ComposerDependencies::ensure(__DIR__);
$autoload_path = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($autoload_path)) { require_once $autoload_path; }
require_once __DIR__ . '/includes/common.inc';
require_once __DIR__ . '/includes/events.inc';
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
define('SS_ksf_FA_HRM_TimeTrackingPayrollIntegration', 201 << 8);
if (!defined('SA_PAYROLL_VIEW')) {
    define('SA_PAYROLL_VIEW', SS_ksf_FA_HRM_TimeTrackingPayrollIntegration | 1);
    define('SA_PAYROLL_MANAGE', SS_ksf_FA_HRM_TimeTrackingPayrollIntegration | 2);
    define('SA_PAYROLL_ADMIN', SS_ksf_FA_HRM_TimeTrackingPayrollIntegration | 3);
}
class hooks_ksf_FA_HRM_TimeTrackingPayrollIntegration extends hooks {
    const RESPONDERS = array('RELATED_TO_FIELDS', 'PAYROLL_RESOLVE', 'PAYROLL_FIELDS', 'PAYROLL_LIST');
    var $module_name = 'ksf_FA_HRM_TimeTrackingPayrollIntegration';
    var $version = '1.0.0';
    function install_tabs($app) { }
    function install_options($app) { }
    function install_access() {
        $security_sections[SS_ksf_FA_HRM_TimeTrackingPayrollIntegration] = _("Payroll");
        $security_areas['SA_PAYROLL_VIEW'] = array(SA_PAYROLL_VIEW, _("View payroll runs"));
        $security_areas['SA_PAYROLL_MANAGE'] = array(SA_PAYROLL_MANAGE, _("Manage payroll runs"));
        $security_areas['SA_PAYROLL_ADMIN'] = array(SA_PAYROLL_ADMIN, _("Administer payroll"));
        return array($security_areas, $security_sections);
    }
    function activate_extension($company, $check_only = true) {
        if ($check_only) { return true; }
        \Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Utils\ComposerDependencies::ensure(__DIR__);
        $this->update_databases($company, array(
            '0_hrm_timesheet_payroll_integration.sql' => array('hrm_timesheet_payroll_integration'),
        ), false);
        return true;
    }
    private function ensure_composer_dependencies() {
        \Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Utils\ComposerDependencies::ensure(__DIR__);
    }
    public function getModuleConstants(&$data, $opts = null) {
        $data['constants'] = array(
            'SS_ksf_FA_HRM_TimeTrackingPayrollIntegration' => SS_ksf_FA_HRM_TimeTrackingPayrollIntegration,
            'SA_PAYROLL_VIEW' => SA_PAYROLL_VIEW,
            'SA_PAYROLL_MANAGE' => SA_PAYROLL_MANAGE,
            'SA_PAYROLL_ADMIN' => SA_PAYROLL_ADMIN,
        );
        return true;
    }
    public function getModuleCapabilities(&$data, $opts = null) {
        $data['capabilities'] = self::RESPONDERS;
        return true;
    }
    public function hasCapability(&$data, $opts) {
        $capability = is_array($opts) && isset($opts['capability']) ? $opts['capability'] : null;
        if ($capability === null && isset($data['capability'])) { $capability = $data['capability']; }
        $data['has_capability'] = in_array($capability, self::RESPONDERS, true);
        return $data['has_capability'];
    }
    public function respondToCapabilityRequest(&$data, $opts = null) {
        $request = is_array($opts) && isset($opts['request']) ? $opts['request'] : (isset($data['request']) ? $data['request'] : 'capabilities');
        $data['request'] = $request;
        $data['module'] = $this->module_name;
        if (strpos($request, 'has:') === 0) { return $this->hasCapability($data, array('capability' => substr($request, 4))); }
        switch ($request) {
            case 'capabilities': return $this->getModuleCapabilities($data, $opts);
            case 'constants': return $this->getModuleConstants($data, $opts);
            default: $data['error'] = 'Unknown request type: ' . $request; return null;
        }
    }
    public function RELATED_TO_FIELDS(&$data, $opts = null) {
        if (!isset($data['module']) || !is_string($data['module'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[module]'; return null; }
        if (!isset($data['type']) || !is_string($data['type'])) { $data['error'] = 'RELATED_TO_FIELDS requires data[type]'; return null; }
        if ($data['type'] !== 'hrm_timesheet_payroll_integration') { return null; }
        if (!isset($data['fields']) || !is_array($data['fields'])) { $data['fields'] = array(); }
        $data['fields'] = array_merge($data['fields'], array(
            'integration_id' => _('Integration ID'),
            'person_id' => _('Employee'),
            'period_start' => _('Period Start'),
            'period_end' => _('Period End'),
            'gross_pay' => _('Gross Pay'),
            'net_pay' => _('Net Pay'),
            'state' => _('Status'),
        ));
        return null;
    }
    public function PAYROLL_RESOLVE(&$data, $opts = null) {
        if (!isset($data['integration_id']) || !is_numeric($data['integration_id'])) { $data['error'] = 'PAYROLL_RESOLVE requires data[integration_id]'; return null; }
        if (!isset($data['dto']) || !$data['dto'] instanceof RelatedRecordDto) { $data['error'] = 'PAYROLL_RESOLVE requires data[dto] as RelatedRecordDto'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Service\PayrollIntegrationService();
        $data['result'] = $service->resolveById((int) $data['integration_id'], $data['dto']);
        return null;
    }
    public function PAYROLL_FIELDS(&$data, $opts = null) {
        $data['fields'] = array(
            'integration_id' => _('Integration ID'),
            'person_id' => _('Employee'),
            'period_start' => _('Period Start'),
            'period_end' => _('Period End'),
            'gross_pay' => _('Gross Pay'),
            'net_pay' => _('Net Pay'),
            'state' => _('Status'),
        );
        return null;
    }
    public function PAYROLL_LIST(&$data, $opts = null) {
        if (!isset($data['person_id']) || !is_numeric($data['person_id'])) { $data['error'] = 'PAYROLL_LIST requires data[person_id]'; return null; }
        $service = new \Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Service\PayrollIntegrationService();
        $data['result'] = $service->listForPerson((int) $data['person_id']);
        return null;
    }
}
