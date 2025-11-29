<?php
/**
 * Time Records API
 * Manages time clock records
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$employeeId = getCurrentEmployeeId();

$month = $_GET['month'] ?? date('n');
$year = $_GET['year'] ?? date('Y');

// Get time records for the specified month
$records = dbGetAll(
    "SELECT * FROM time_records 
     WHERE employee_id = ? 
     AND MONTH(record_date) = ? 
     AND YEAR(record_date) = ?
     ORDER BY record_date ASC",
    [$employeeId, $month, $year]
);

// Calculate summary
$summary = [
    'total_days' => count($records),
    'total_hours' => 0,
    'overtime_hours' => 0,
    'late_days' => 0,
    'absent_days' => 0,
    'normal_days' => 0
];

foreach ($records as $record) {
    $summary['total_hours'] += $record['total_hours'];
    $summary['overtime_hours'] += $record['overtime_hours'];
    
    switch ($record['status']) {
        case 'late':
            $summary['late_days']++;
            break;
        case 'absent':
            $summary['absent_days']++;
            break;
        case 'normal':
            $summary['normal_days']++;
            break;
    }
}

// Get available months/years
$availablePeriods = dbGetAll(
    "SELECT DISTINCT 
        MONTH(record_date) as month, 
        YEAR(record_date) as year
     FROM time_records 
     WHERE employee_id = ?
     ORDER BY year DESC, month DESC
     LIMIT 12",
    [$employeeId]
);

foreach ($availablePeriods as &$period) {
    $period['month_name'] = getMonthName($period['month']);
    $period['label'] = $period['month_name'] . '/' . $period['year'];
}

echo json_encode([
    'success' => true,
    'data' => [
        'records' => $records,
        'summary' => $summary,
        'current_month' => getMonthName($month),
        'current_year' => $year,
        'available_periods' => $availablePeriods
    ]
]);
