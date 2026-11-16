# ksf_FA_HRM_TimeTrackingPayrollIntegration — Requirements Traceability Matrix

| Req ID | Requirement | Source | Implemented In | Verified By |
|--------|-------------|--------|----------------|-------------|
| H5 | Timesheet→payroll integration | HR-CRM_gaps.md H5 | PayrollIntegrationService | unit tests |
| FR-PY-01 | Create run | H5 | PayrollIntegrationRepository::insertRun | PayrollIntegrationDtoTest |
| FR-PY-02 | List runs | H5 | PayrollIntegrationService::listAll | (tab controller) |
| FR-PY-03 | Resolve run by id | H5 | PayrollIntegrationService::resolveById | (capability hook) |
| FR-PY-04 | Transition lifecycle | H5 | PayrollIntegrationService::updateState | (state transitions) |
| FR-PY-05 | List for employee | H5 | PayrollIntegrationRepository::listForPerson | (service) |
| FR-PY-06 | RELATED_TO_FIELDS | H5 | hooks.php RELATED_TO_FIELDS | (capability) |
| NF-PY-01 | Native db_* calls | AGENTS_ARCH | repository | code review |
| NF-PY-02 | PHP 7.3 compatible | AGENTS_ARCH | all src | code review |
| NF-PY-03 | Unit tests | AGENTS_ARCH | tests/Unit | phpunit |
