<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\DTO;
final class PayrollIntegrationDTO {
    public $integration_id;
    public $person_id;
    public $person_name;
    public $period_start;
    public $period_end;
    public $gross_pay;
    public $net_pay;
    public $state;
    public $pay_date;
    public $created_at;
    public $updated_at;
    public function __construct(
        int $integration_id,
        int $person_id = 0,
        string $person_name = '',
        string $period_start = '',
        string $period_end = '',
        string $gross_pay = '0.00',
        string $net_pay = '0.00',
        string $state = 'draft',
        string $pay_date = '',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->integration_id = $integration_id;
        $this->person_id = $person_id;
        $this->person_name = $person_name;
        $this->period_start = $period_start;
        $this->period_end = $period_end;
        $this->gross_pay = $gross_pay;
        $this->net_pay = $net_pay;
        $this->state = $state;
        $this->pay_date = $pay_date;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function getDisplayLabel(): string {
        if ($this->person_name !== '' && $this->period_start !== '') {
            return sprintf(_('%s payroll %s to %s'), $this->person_name, $this->period_start, $this->period_end);
        }
        return _('Payroll Integration #') . (string) $this->integration_id;
    }
    public function toArray(): array {
        return array(
            'integration_id' => $this->integration_id,
            'person_id' => $this->person_id,
            'person_name' => $this->person_name,
            'period_start' => $this->period_start,
            'period_end' => $this->period_end,
            'gross_pay' => $this->gross_pay,
            'net_pay' => $this->net_pay,
            'state' => $this->state,
            'pay_date' => $this->pay_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        );
    }
    public static function fromArray(array $data): self {
        return new self(
            (int) ($data['integration_id'] ?? 0),
            (int) ($data['person_id'] ?? 0),
            (string) ($data['person_name'] ?? ''),
            (string) ($data['period_start'] ?? ''),
            (string) ($data['period_end'] ?? ''),
            (string) ($data['gross_pay'] ?? '0.00'),
            (string) ($data['net_pay'] ?? '0.00'),
            (string) ($data['state'] ?? 'draft'),
            (string) ($data['pay_date'] ?? ''),
            (string) ($data['created_at'] ?? ''),
            (string) ($data['updated_at'] ?? '')
        );
    }
}
