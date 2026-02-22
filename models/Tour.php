<?php
class Tour extends Model
{
    protected string $table = 'tours';

    public function featured(int $limit = 6): array
    {
        return $this->db->fetchAll(
            "SELECT t.*, d.name as destination_name FROM tours t 
             LEFT JOIN destinations d ON t.destination_id = d.id 
             WHERE t.status = 'active' AND t.is_featured = 1 
             ORDER BY t.is_bestseller DESC, t.created_at DESC LIMIT {$limit}"
        );
    }

    public function withDestination(string $slug): ?object
    {
        return $this->db->fetch(
            "SELECT t.*, d.name as destination_name, d.slug as destination_slug, d.region
             FROM tours t LEFT JOIN destinations d ON t.destination_id = d.id 
             WHERE t.slug = ?", [$slug]
        );
    }

    public function getFiltered(array $filters = [], int $page = 1): array
    {
        $where = "t.status = 'active'";
        $params = [];

        if (!empty($filters['type'])) {
            $where .= " AND t.type = ?";
            $params[] = $filters['type'];
        }
        if (!empty($filters['destination'])) {
            $where .= " AND t.destination_id = ?";
            $params[] = $filters['destination'];
        }
        if (!empty($filters['min_price'])) {
            $where .= " AND t.price >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where .= " AND t.price <= ?";
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['duration'])) {
            $where .= " AND t.duration_days <= ?";
            $params[] = $filters['duration'];
        }

        $orderBy = match ($filters['sort'] ?? 'featured') {
            'price_asc' => 't.price ASC',
            'price_desc' => 't.price DESC',
            'duration' => 't.duration_days ASC',
            'rating' => 't.avg_rating DESC',
            'newest' => 't.created_at DESC',
            default => 't.is_featured DESC, t.is_bestseller DESC, t.created_at DESC',
        };

        $perPage = ITEMS_PER_PAGE;
        $total = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM tours t WHERE {$where}", $params
        );
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $items = $this->db->fetchAll(
            "SELECT t.*, d.name as destination_name 
             FROM tours t LEFT JOIN destinations d ON t.destination_id = d.id 
             WHERE {$where} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}", $params
        );

        return compact('items', 'total', 'page', 'totalPages');
    }

    public function getItinerary(int $tourId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM tour_itineraries WHERE tour_id = ? ORDER BY day_number", [$tourId]
        );
    }

    public function getReviews(int $tourId, bool $approvedOnly = true): array
    {
        $where = "tour_id = ?";
        if ($approvedOnly) $where .= " AND is_approved = 1";
        return $this->db->fetchAll(
            "SELECT * FROM reviews WHERE {$where} ORDER BY created_at DESC", [$tourId]
        );
    }

    public function getImages(int $tourId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM tour_images WHERE tour_id = ? ORDER BY sort_order", [$tourId]
        );
    }
}
