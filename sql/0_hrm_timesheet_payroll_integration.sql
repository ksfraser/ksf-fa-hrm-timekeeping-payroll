-- ksf_FA_HRM_TimeTrackingPayrollIntegration: timesheet-to-payroll tables

CREATE TABLE IF NOT EXISTS `0_hrm_timesheet_payroll_integration` (
  `integration_id` INT(11) NOT NULL AUTO_INCREMENT,
  `person_id`      INT(11) NOT NULL COMMENT 'FK to crm_persons.id (employee)',
  `period_start`   DATE NOT NULL,
  `period_end`     DATE NOT NULL,
  `gross_pay`      DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `net_pay`        DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `state`          VARCHAR(24) NOT NULL DEFAULT 'draft' COMMENT 'draft|calculated|approved|paid|posted',
  `pay_date`       DATE DEFAULT NULL,
  `created_at`     DATETIME NOT NULL,
  `updated_at`     DATETIME NOT NULL,
  PRIMARY KEY (`integration_id`),
  UNIQUE KEY `uq_person_period` (`person_id`, `period_start`, `period_end`),
  KEY `idx_person` (`person_id`),
  KEY `idx_state` (`state`),
  KEY `idx_period` (`period_start`, `period_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
