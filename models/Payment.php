<?php
class Payment extends Model { protected string $table = 'payments'; }
class Ad extends Model { protected string $table = 'ads';
    public function active(string $placement = ''): array { $w = "is_active = 1 AND (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE())"; if ($placement) { $w .= " AND placement = ?"; return $this->where($w, [$placement], 'sort_order ASC'); } return $this->where($w, [], 'sort_order ASC'); }
}
class Setting extends Model { protected string $table = 'settings'; }
class TripRequest extends Model { protected string $table = 'trip_requests'; }
