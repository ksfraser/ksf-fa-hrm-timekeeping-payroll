<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Repository;
final class PayrollIntegrationRepository {
    private string $table = 'hrm_timesheet_payroll_integration';
    public function findAll(): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT p.*, c.name AS person_name FROM " . TB_PREF . $this->table . " p"
            . " LEFT JOIN " . TB_PREF . "crm_persons c ON p.person_id = c.id"
            . " ORDER BY p.created_at DESC, p.integration_id DESC";
        $result = db_query($sql, 'Could not load payroll integrations');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function findById(int $id): ?array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return null; }
        $sql = "SELECT p.*, c.name AS person_name FROM " . TB_PREF . $this->table . " p"
            . " LEFT JOIN " . TB_PREF . "crm_persons c ON p.person_id = c.id"
            . " WHERE p.integration_id = " . (int) $id;
        $result = db_query($sql, 'Could not load payroll integration');
        if ($result === false) { return null; }
        $row = db_fetch_assoc($result);
        return $row === false ? null : $row;
    }
    public function listForPerson(int $personId): array {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return array(); }
        $sql = "SELECT p.*, c.name AS person_name FROM " . TB_PREF . $this->table . " p"
            . " LEFT JOIN " . TB_PREF . "crm_persons c ON p.person_id = c.id"
            . " WHERE p.person_id = " . (int) $personId
            . " ORDER BY p.period_start DESC";
        $result = db_query($sql, 'Could not load payroll integrations by person');
        if ($result === false) { return array(); }
        $rows = array();
        while (($row = db_fetch_assoc($result)) !== false && $row !== null) { $rows[] = $row; }
        return $rows;
    }
    public function insertRun(array $data): int {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return 0; }
        $sql = "INSERT INTO " . TB_PREF . $this->table . " (
                person_id, period_start, period_end, gross_pay, net_pay, state, pay_date, created_at, updated_at)
            VALUES ("
            . $this->intVal($data['person_id'] ?? 0) . ", "
            . $this->escape($data['period_start'] ?? '') . ", "
            . $this->escape($data['period_end'] ?? '') . ", "
            . $this->floatVal($data['gross_pay'] ?? 0) . ", "
            . $this->floatVal($data['net_pay'] ?? 0) . ", "
            . $this->escape($data['state'] ?? 'draft') . ", "
            . $this->escape($data['pay_date'] ?? null) . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ", "
            . $this->escape(date('Y-m-d H:i:s')) . ")";
        $this->dbQuery($sql);
        return $this->dbInsertId();
    }
    public function updateState(int $integrationId, string $state, string $payDate = null): void {
        if (!function_exists('db_query') || !defined('TB_PREF')) { return; }
        $sets = ["state = " . $this->escape($state)];
        $sets[] = "pay_date = " . $this->escape($payDate);
        $sets[] = "updated_at = " . $this->escape(date('Y-m-d H:i:s'));
        $sql = "UPDATE " . TB_PREF . $this->table . " SET " . implode(', ', $sets)
            . " WHERE integration_id = " . $this->intVal($integrationId);
        $this->dbQuery($sql);
    }
    private function dbQuery($sql, $errorMsg = null) {
        return db_query($sql, $errorMsg);
    }
    private function dbInsertId() {
        return db_insert_id();
    }
    private function escape($value): string {
        if ($value === null) { return 'NULL'; }
        return function_exists('db_escape') ? db_escape($value) : addslashes((string) $value);
    }
    private function intVal($value): int {
        return (int) $value;
    }
    private function floatVal($value): float {
        return (float) $value;
    }
}
