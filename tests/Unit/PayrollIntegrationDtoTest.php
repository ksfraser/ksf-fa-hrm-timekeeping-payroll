<?php
declare(strict_types=1);
namespace Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\Tests\Unit;
use PHPUnit\Framework\TestCase;
use Ksfraser\FrontAccounting\HRM\TimeTrackingPayrollIntegration\DTO\PayrollIntegrationDTO;
class PayrollIntegrationDtoTest extends TestCase
{
    public function testDtoRoundTripsThroughArray(): void {
        $dto = new PayrollIntegrationDTO(5, 10, 'Sue Jones', '2026-10-01', '2026-10-31', '4000.00', '3100.00', 'calculated', '2026-11-05');
        $arr = $dto->toArray();
        $this->assertSame(5, $arr['integration_id']);
        $this->assertSame(10, $arr['person_id']);
        $this->assertSame('Sue Jones', $arr['person_name']);
        $this->assertSame('2026-10-01', $arr['period_start']);
        $this->assertSame('2026-10-31', $arr['period_end']);
        $this->assertSame('4000.00', $arr['gross_pay']);
        $this->assertSame('3100.00', $arr['net_pay']);
        $this->assertSame('calculated', $arr['state']);
        $dto2 = PayrollIntegrationDTO::fromArray($arr);
        $this->assertSame($dto->integration_id, $dto2->integration_id);
        $this->assertSame($dto->gross_pay, $dto2->gross_pay);
        $this->assertSame($dto->state, $dto2->state);
    }
    public function testDisplayLabelUsesPersonAndPeriodWhenSet(): void {
        $dto = new PayrollIntegrationDTO(1, 2, 'Sue Jones', '2026-10-01', '2026-10-31');
        $this->assertStringContainsString('Sue Jones', $dto->getDisplayLabel());
        $this->assertStringContainsString('2026-10-01', $dto->getDisplayLabel());
    }
    public function testDisplayLabelFallsBackToId(): void {
        $dto = new PayrollIntegrationDTO(9, 2, '', '', '', '0.00', '0.00', 'draft');
        $this->assertStringContainsString('Payroll Integration #9', $dto->getDisplayLabel());
    }
}
