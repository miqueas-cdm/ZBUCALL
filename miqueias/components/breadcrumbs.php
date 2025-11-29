<?php
/**
 * Breadcrumbs Component
 */

function renderBreadcrumbs($items) {
    if (empty($items)) return;
    
    echo '<nav class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-6" aria-label="Breadcrumb">';
    echo '<a href="dashboard.php" class="hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1">';
    echo '<i data-lucide="home" class="w-4 h-4"></i>';
    echo '</a>';
    
    foreach ($items as $index => $item) {
        echo '<i data-lucide="chevron-right" class="w-4 h-4"></i>';
        
        if ($index === count($items) - 1) {
            // Last item (current page)
            echo '<span class="text-gray-900 dark:text-white font-medium">';
            echo htmlspecialchars($item['label']);
            echo '</span>';
        } else {
            // Clickable items
            if (isset($item['url'])) {
                echo '<a href="' . htmlspecialchars($item['url']) . '" class="hover:text-blue-600 dark:hover:text-blue-400">';
                echo htmlspecialchars($item['label']);
                echo '</a>';
            } else {
                echo '<span>' . htmlspecialchars($item['label']) . '</span>';
            }
        }
    }
    
    echo '</nav>';
}
