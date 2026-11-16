<?php
declare(strict_types=1);

if (!function_exists('hook_invoke_all')) {
    return;
}

function payroll_run_state_changed($runId, $fromState, $toState, $meta = array()) {
    hook_invoke_all('PAYROLL_STATE_CHANGED', array(
        'run_id' => $runId,
        'from_state' => $fromState,
        'to_state' => $toState,
        'meta' => $meta,
    ));
}
