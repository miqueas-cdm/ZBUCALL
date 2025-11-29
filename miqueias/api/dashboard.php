<?php
/**
 * Dashboard API
 * Provides dashboard statistics and quick links
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$employeeId = getCurrentEmployeeId();

// Get unread notifications count
$notificationsCount = dbGetRow(
    "SELECT COUNT(*) as count FROM notifications WHERE employee_id = ? AND is_read = FALSE",
    [$employeeId]
)['count'] ?? 0;

// Get pending requests count
$pendingRequestsCount = dbGetRow(
    "SELECT COUNT(*) as count FROM requests WHERE employee_id = ? AND status = 'pending'",
    [$employeeId]
)['count'] ?? 0;

// Get active benefits count
$benefitsCount = dbGetRow(
    "SELECT COUNT(*) as count FROM benefits WHERE employee_id = ? AND status = 'active'",
    [$employeeId]
)['count'] ?? 0;

// Get recent notifications (last 5)
$recentNotifications = dbGetAll(
    "SELECT * FROM notifications WHERE employee_id = ? ORDER BY created_at DESC LIMIT 5",
    [$employeeId]
);

// Get important communications (last 3)
$importantCommunications = dbGetAll(
    "SELECT * FROM communications 
     WHERE status = 'published' 
     AND (priority = 'high' OR priority = 'urgent')
     AND (published_at IS NULL OR published_at <= NOW())
     AND (expires_at IS NULL OR expires_at > NOW())
     ORDER BY priority DESC, published_at DESC 
     LIMIT 3"
);

// Get current month payslip status
$currentMonth = date('n');
$currentYear = date('Y');
$currentPayslip = dbGetRow(
    "SELECT * FROM payslips WHERE employee_id = ? AND reference_month = ? AND reference_year = ?",
    [$employeeId, $currentMonth, $currentYear]
);

// Get this month's work summary
$workSummary = dbGetRow(
    "SELECT 
        COUNT(*) as days_worked,
        SUM(total_hours) as total_hours,
        SUM(overtime_hours) as overtime_hours,
        SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
     FROM time_records 
     WHERE employee_id = ? 
     AND MONTH(record_date) = ? 
     AND YEAR(record_date) = ?",
    [$employeeId, $currentMonth, $currentYear]
);

echo json_encode([
    'success' => true,
    'data' => [
        'stats' => [
            'notifications' => $notificationsCount,
            'pending_requests' => $pendingRequestsCount,
            'active_benefits' => $benefitsCount,
            'payslip_available' => $currentPayslip ? true : false
        ],
        'recent_notifications' => $recentNotifications,
        'important_communications' => $importantCommunications,
        'work_summary' => $workSummary ?? [
            'days_worked' => 0,
            'total_hours' => 0,
            'overtime_hours' => 0,
            'late_count' => 0,
            'absent_count' => 0
        ]
    ]
]);
