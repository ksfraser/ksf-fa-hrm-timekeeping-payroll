<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Service;
use Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\DTO\PayrollIntegrationDTO;
use Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Repository\PayrollIntegrationRepository;
use ksfraser\RelatedTo\DTO\RelatedRecordDto;
final class PayrollIntegrationService {
    private $repo;
    public function __construct(?PayrollIntegrationRepository $repo = null) { $this->repo = $repo ?? new PayrollIntegrationRepository(); }
    public function resolveById(int $id, ?RelatedRecordDto $dto = null): array {
        $row = $this->repo->findById($id);
        if ($row === null) {
            return array('dto' => new RelatedRecordDto('hrm_timesheet_payroll_integration', '0', 'hrm_timesheet_payroll_integration', '0', null), 'fields' => $this->fieldMap(), 'labels' => $this->labelMap(), 'url' => '#', 'label' => _('Payroll Integration #') . (string) $id);
        }
        $r = PayrollIntegrationDTO::fromArray($row);
        $dto = $dto ?? new RelatedRecordDto('hrm_timesheet_payroll_integration', (string) $r->integration_id, 'hrm_timesheet_payroll_integration', (string) $r->integration_id, null);
        return array(
            'dto' => $dto,
            'fields' => array(
                'integration_id' => (string) $r->integration_id,
                'person_id' => $r->person_name,
                'period_start' => $r->period_start,
                'period_end' => $r->period_end,
                'gross_pay' => $r->gross_pay,
                'net_pay' => $r->net_pay,
                'state' => $r->state,
            ),
            'labels' => $this->labelMap(),
            'url' => '/modules/ksf_FA_HRM_TimeTrackingPayrollIntegration/pages/payroll_integration_form.php?action=edit&integration_id=' . $r->integration_id,
            'label' => $r->getDisplayLabel(),
        );
    }
    public function listForPerson(int $personId): array {
        $rows = $this->repo->listForPerson($personId);
        $results = array();
        foreach ($rows as $row) {
            $r = PayrollIntegrationDTO::fromArray($row);
            $results[] = array(
                'id' => $r->integration_id,
                'dto' => new RelatedRecordDto('hrm_timesheet_payroll_integration', (string) $r->integration_id, 'hrm_timesheet_payroll_integration', (string) $r->integration_id, null),
                'fields' => array(
                    'integration_id' => (string) $r->integration_id,
                    'person_id' => $r->person_name,
                    'period_start' => $r->period_start,
                    'period_end' => $r->period_end,
                    'gross_pay' => $r->gross_pay,
                    'net_pay' => $r->net_pay,
                    'state' => $r->state,
                ),
                'labels' => $this->labelMap(),
                'url' => '/modules/ksf_FA_HRM_TimeTrackingPayrollIntegration/pages/payroll_integration_form.php?action=edit&integration_id=' . $r->integration_id,
                'label' => $r->getDisplayLabel(),
            );
        }
        return $results;
    }
    public function listAll(): array {
        $rows = $this->repo->findAll();
        $results = array();
        foreach ($rows as $row) {
            $r = PayrollIntegrationDTO::fromArray($row);
            $results[] = array(
                'id' => $r->integration_id,
                'fields' => array(
                    'integration_id' => (string) $r->integration_id,
                    'person_name' => $r->person_name,
                    'period_start' => $r->period_start,
                    'period_end' => $r->period_end,
                    'gross_pay' => $r->gross_pay,
                    'net_pay' => $r->net_pay,
                    'state' => $r->state,
                    'url' => '/modules/ksf_FA_HRM_TimeTrackingPayrollIntegration/pages/payroll_integration_form.php?action=edit&integration_id=' . $r->integration_id,
                ),
            );
        }
        return $results;
    }
    public function insertRun(array $data): int {
        return $this->repo->insertRun($data);
    }
    public function updateState(int $integrationId, string $state, string $payDate = null): void {
        $this->repo->updateState($integrationId, $state, $payDate);
        payroll_run_state_changed((string) $integrationId, '', $state, array('pay_date' => $payDate));
    }
    public function fieldMap(): array { return $this->labelMap(); }
    public function labelMap(): array {
        return array(
            'integration_id' => _('Integration ID'),
            'person_id' => _('Employee'),
            'period_start' => _('Period Start'),
            'period_end' => _('Period End'),
            'gross_pay' => _('Gross Pay'),
            'net_pay' => _('Net Pay'),
            'state' => _('Status'),
        );
    }
    public function getStateTransitions(string $currentState): array {
        $transitions = array(
            'draft' => array('calculated' => _('Calculate')),
            'calculated' => array('approved' => _('Approve'), 'draft' => _('Reopen')),
            'approved' => array('paid' => _('Mark Paid')),
            'paid' => array('posted' => _('Post to GL')),
        );
        return isset($transitions[$currentState]) ? $transitions[$currentState] : array();
    }
    public function isAllowedTransition(string $fromState, string $toState): bool {
        $transitions = $this->getStateTransitions($fromState);
        return isset($transitions[$toState]);
    }
}
