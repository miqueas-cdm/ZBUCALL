<?php
/**
 * Benefits API
 * Manages employee benefits
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

requireAuth();

$employeeId = getCurrentEmployeeId();

// Get all active benefits
$benefits = dbGetAll(
    "SELECT * FROM benefits WHERE employee_id = ? ORDER BY benefit_type, benefit_name",
    [$employeeId]
);

// Group benefits by type
$groupedBenefits = [];
foreach ($benefits as $benefit) {
    $type = $benefit['benefit_type'];
    if (!isset($groupedBenefits[$type])) {
        $groupedBenefits[$type] = [];
    }
    $groupedBenefits[$type][] = $benefit;
}

// Benefit type information with icons
$benefitTypes = [
    'transport' => [
        'name' => 'Vale-Transporte',
        'icon' => 'bus',
        'color' => 'blue'
    ],
    'meal' => [
        'name' => 'Vale-Alimentação',
        'icon' => 'shopping-cart',
        'color' => 'green'
    ],
    'food' => [
        'name' => 'Vale-Refeição',
        'icon' => 'utensils',
        'color' => 'orange'
    ],
    'health' => [
        'name' => 'Plano de Saúde',
        'icon' => 'heart',
        'color' => 'red'
    ],
    'dental' => [
        'name' => 'Plano Odontológico',
        'icon' => 'smile',
        'color' => 'cyan'
    ],
    'life' => [
        'name' => 'Seguro de Vida',
        'icon' => 'shield',
        'color' => 'purple'
    ]
];

echo json_encode([
    'success' => true,
    'data' => [
        'benefits' => $benefits,
        'grouped_benefits' => $groupedBenefits,
        'benefit_types' => $benefitTypes,
        'total_count' => count($benefits),
        'total_value' => array_sum(array_column($benefits, 'value'))
    ]
]);
