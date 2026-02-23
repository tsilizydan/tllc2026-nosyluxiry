<?php
/**
 * Partner — Hotels, Car Rentals, Currency Exchange
 */
class Partner extends Model
{
    protected string $table = 'partners';

    /**
     * Get featured partners
     */
    public function featured(int $limit = 6): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE status = 'active' AND is_featured = 1
             ORDER BY sort_order ASC, name ASC LIMIT {$limit}"
        );
    }

    /**
     * Get partners by type
     */
    public function byType(string $type): array
    {
        return $this->where("type = ? AND status = 'active'", [$type], 'sort_order ASC, name ASC');
    }

    /**
     * Get all active partners grouped by type
     */
    public function activeGrouped(): array
    {
        $all = $this->where("status = 'active'", [], 'type ASC, sort_order ASC, name ASC');
        $grouped = [];
        foreach ($all as $partner) {
            $grouped[$partner->type][] = $partner;
        }
        return $grouped;
    }

    /**
     * Get all partners for admin listing
     */
    public function adminList(int $page = 1, int $perPage = 20): array
    {
        return $this->paginate($page, $perPage, '1', [], 'type ASC, sort_order ASC');
    }

    /**
     * Type labels for display
     */
    public static function typeLabels(): array
    {
        return [
            'hotel' => 'Hotel / Lodge',
            'car_rental' => 'Car Rental',
            'currency_exchange' => 'Currency Exchange',
        ];
    }

    /**
     * Get label for a type
     */
    public static function typeLabel(string $type): string
    {
        return self::typeLabels()[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }
}
