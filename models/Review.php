<?php
class Review extends Model { protected string $table = 'reviews'; 
    public function approved(int $tourId): array { return $this->where("tour_id = ? AND is_approved = 1", [$tourId], 'created_at DESC'); }
    public function featured(int $limit = 4): array { return $this->db->fetchAll("SELECT r.*, t.name as tour_name, t.slug as tour_slug FROM reviews r JOIN tours t ON r.tour_id = t.id WHERE r.is_approved = 1 AND r.is_featured = 1 ORDER BY r.created_at DESC LIMIT {$limit}"); }
    public function pending(): array { return $this->db->fetchAll("SELECT r.*, t.name as tour_name FROM reviews r JOIN tours t ON r.tour_id = t.id WHERE r.is_approved = 0 ORDER BY r.created_at DESC"); }
}
