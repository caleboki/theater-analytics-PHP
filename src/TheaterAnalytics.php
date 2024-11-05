<?php

class TheaterAnalytics {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function validateDate(string $dateStr): string {
        $formats = ['Y-m-d', 'm/d/Y', 'd-m-Y'];
        
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateStr);
            if ($date && $date->format($format) === $dateStr) {
                return $date->format('Y-m-d');
            }
        }
        
        throw new InvalidArgumentException('Invalid date format. Please use YYYY-MM-DD or MM/DD/YYYY');
    }

    public function getTopPerformingTheater(string $dateStr): array {
        try {
            $date = $this->validateDate($dateStr);

            $query = "
                SELECT 
                    t.theater_id,
                    t.name as theater_name,
                    t.location,
                    SUM(ds.total_sales) as total_daily_sales,
                    SUM(ds.tickets_sold) as total_tickets_sold
                FROM theaters t
                JOIN daily_sales ds ON t.theater_id = ds.theater_id
                WHERE ds.sale_date = :date
                GROUP BY t.theater_id, t.name, t.location
                ORDER BY total_daily_sales DESC
                LIMIT 1
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute(['date' => $date]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                throw new RuntimeException('No sales data found for the specified date');
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}