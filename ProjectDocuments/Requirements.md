# ksf_FA_HRM_TimeTrackingPayrollIntegration — Requirements

## Purpose
Integrate timesheet data with payroll: timesheet records, payroll runs, gross→net calculations and GL posting.

## Business Rules (H5)
- A payroll integration run belongs to one employee for one period (start/end dates).
- Periods are unique per employee (one run per person per period).
- Gross pay and net pay are monetary values; state tracks the run lifecycle.
- State transitions: draft → calculated → approved → paid → posted.

## Functional Requirements
- FR-PY-01: Create a payroll integration run for an employee and period.
- FR-PY-02: List all payroll runs (summary table).
- FR-PY-03: Resolve a single run by id (related-record linking).
- FR-PY-04: Transition a run through its lifecycle with gross/net pay and pay date.
- FR-PY-05: List runs for a given employee.
- FR-PY-06: Expose payroll integration fields via RELATED_TO_FIELDS.

## Non-Functional Requirements
- NF-PY-01: All database access uses native FA db_* calls.
- NF-PY-02: PHP 7.3 compatible runtime.
- NF-PY-03: Unit tests cover DTO round-trip and label resolution.
