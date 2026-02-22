<?php
class Booking extends Model
{
    protected string $table = 'bookings';

    public function generateReference(): string
    {
        do {
            $ref = 'NL-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while ($this->first("reference = ?", [$ref]));
        return $ref;
    }

    public function findByReference(string $reference): ?object
    {
        return $this->db->fetch(
            "SELECT b.*, t.name as tour_name, t.slug as tour_slug, t.image as tour_image
             FROM bookings b JOIN tours t ON b.tour_id = t.id WHERE b.reference = ?", [$reference]
        );
    }

    public function userBookings(int $userId): array
    {
        return $this->db->fetchAll(
            "SELECT b.*, t.name as tour_name, t.slug as tour_slug, t.image as tour_image
             FROM bookings b JOIN tours t ON b.tour_id = t.id 
             WHERE b.user_id = ? ORDER BY b.created_at DESC", [$userId]
        );
    }

    public function recentBookings(int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT b.*, t.name as tour_name, u.first_name, u.last_name
             FROM bookings b JOIN tours t ON b.tour_id = t.id 
             LEFT JOIN users u ON b.user_id = u.id 
             ORDER BY b.created_at DESC LIMIT {$limit}"
        );
    }

    public function totalRevenue(string $period = 'all'): float
    {
        $where = "payment_status = 'paid'";
        if ($period === 'month') $where .= " AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())";
        if ($period === 'year') $where .= " AND YEAR(created_at) = YEAR(NOW())";
        return (float) $this->db->fetchColumn("SELECT COALESCE(SUM(total),0) FROM bookings WHERE {$where}");
    }

    public function stats(): array
    {
        return [
            'total' => $this->count(),
            'pending' => $this->count("status = 'pending'"),
            'confirmed' => $this->count("status = 'confirmed'"),
            'completed' => $this->count("status = 'completed'"),
            'revenue_month' => $this->totalRevenue('month'),
            'revenue_total' => $this->totalRevenue('all'),
        ];
    }
}
