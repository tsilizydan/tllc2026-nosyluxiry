<?php
class Ad extends Model
{
    protected string $table = 'ads';

    public function active(string $placement = ''): array
    {
        $w = "is_active = 1 AND (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE())";
        if ($placement) {
            $w .= " AND placement = ?";
            return $this->where($w, [$placement], 'sort_order ASC');
        }
        return $this->where($w, [], 'sort_order ASC');
    }
}
