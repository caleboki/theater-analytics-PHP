<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/TheaterAnalytics.php';

function main() {
    $db = Database::initialize();
    $analytics = new TheaterAnalytics($db);

    while (true) {
        echo "\nEnter date (YYYY-MM-DD or MM/DD/YYYY) or 'q' to quit: ";
        $input = trim(fgets(STDIN));

        if (strtolower($input) === 'q') {
            Database::close();
            exit(0);
        }

        try {
            $result = $analytics->getTopPerformingTheater($input);
            
            echo "\nTop performing theater on {$input}\n";
            echo "Theater: {$result['theater_name']}\n";
            echo "Location: {$result['location']}\n";
            echo "Total Sales: $ " . number_format($result['total_daily_sales'], 2) . "\n";
            echo "Tickets Sold: {$result['total_tickets_sold']}\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}

main();