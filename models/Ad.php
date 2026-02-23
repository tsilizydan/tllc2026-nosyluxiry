<?php
/**
 * Ad — Banner ads & sponsored content
 */
class Ad extends Model
{
    protected string $table = 'ads';

    /**
     * Get active ads for a specific placement
     */
    public function forPlacement(string $placement, int $limit = 1): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE placement = ? AND is_active = 1
             AND (start_date IS NULL OR start_date <= CURDATE())
             AND (end_date IS NULL OR end_date >= CURDATE())
             ORDER BY sort_order ASC LIMIT {$limit}",
            [$placement]
        );
    }

    /**
     * Track an impression
     */
    public function trackImpression(int $id): void
    {
        $this->db->query("UPDATE {$this->table} SET impressions = impressions + 1 WHERE id = ?", [$id]);
    }

    /**
     * Track a click
     */
    public function trackClick(int $id): void
    {
        $this->db->query("UPDATE {$this->table} SET clicks = clicks + 1 WHERE id = ?", [$id]);
    }

    /**
     * Admin listing with stats
     */
    public function adminList(int $page = 1, int $perPage = 20): array
    {
        return $this->paginate($page, $perPage, '1', [], 'created_at DESC');
    }

    /**
     * Placement labels for display
     */
    public static function placementLabels(): array
    {
        return [
            'hero' => 'Hero Banner',
            'sidebar' => 'Sidebar',
            'footer' => 'Footer',
            'inline' => 'Inline Content',
            'popup' => 'Popup',
        ];
    }
}
