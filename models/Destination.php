<?php
class Destination extends Model
{
    protected string $table = 'destinations';

    public function featured(int $limit = 6): array
    {
        return $this->where("status = 'active' AND is_featured = 1", [], 'sort_order ASC');
    }

    public function byRegion(string $region): array
    {
        return $this->where("region = ? AND status = 'active'", [$region], 'sort_order ASC');
    }

    public function withTourCount(): array
    {
        return $this->db->fetchAll(
            "SELECT d.*, COUNT(t.id) as tour_count 
             FROM destinations d LEFT JOIN tours t ON d.id = t.destination_id AND t.status = 'active'
             WHERE d.status = 'active' GROUP BY d.id ORDER BY d.sort_order ASC"
        );
    }

    public function getRegions(): array
    {
        return ['north', 'south', 'east', 'west', 'tsingy', 'central'];
    }

    public function getRegionLabel(string $region): string
    {
        return match ($region) {
            'north' => 'Northern Madagascar',
            'south' => 'Southern Madagascar',
            'east' => 'Eastern Madagascar',
            'west' => 'Western Madagascar',
            'tsingy' => 'Tsingy Region',
            'central' => 'Central Highlands',
            default => ucfirst($region),
        };
    }
}
