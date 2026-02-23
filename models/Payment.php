<?php
/**
 * Payment — Transaction records
 */
class Payment extends Model
{
    protected string $table = 'payments';

    /**
     * Get payments for a specific booking
     */
    public function forBooking(int $bookingId): array
    {
        return $this->where("booking_id = ?", [$bookingId], 'created_at DESC');
    }

    /**
     * Get recent transactions with booking/tour info
     */
    public function recentTransactions(int $limit = 50): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, b.reference, b.guest_name, b.guest_email, t.name as tour_name
             FROM payments p
             JOIN bookings b ON p.booking_id = b.id
             JOIN tours t ON b.tour_id = t.id
             ORDER BY p.created_at DESC LIMIT {$limit}"
        );
    }

    /**
     * Paginated transactions with filters
     */
    public function paginatedTransactions(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $where = "1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $where .= " AND p.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['gateway'])) {
            $where .= " AND p.gateway = ?";
            $params[] = $filters['gateway'];
        }
        if (!empty($filters['date_from'])) {
            $where .= " AND p.created_at >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where .= " AND p.created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }

        $total = (int) $this->db->fetchColumn(
            "SELECT COUNT(*) FROM payments p WHERE {$where}", $params
        );
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $items = $this->db->fetchAll(
            "SELECT p.*, b.reference, b.guest_name, b.guest_email, t.name as tour_name
             FROM payments p
             JOIN bookings b ON p.booking_id = b.id
             JOIN tours t ON b.tour_id = t.id
             WHERE {$where}
             ORDER BY p.created_at DESC LIMIT {$perPage} OFFSET {$offset}", $params
        );

        return compact('items', 'total', 'page', 'totalPages');
    }

    /**
     * Revenue totals by gateway
     */
    public function totalByGateway(): array
    {
        return $this->db->fetchAll(
            "SELECT gateway, COUNT(*) as count, SUM(amount) as total
             FROM payments WHERE status = 'completed'
             GROUP BY gateway ORDER BY total DESC"
        );
    }

    /**
     * Create a payment record for a booking
     */
    public function createForBooking(int $bookingId, string $gateway, float $amount, string $currency = 'EUR'): int
    {
        return $this->create([
            'booking_id' => $bookingId,
            'gateway' => $gateway,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Mark payment as completed
     */
    public function markCompleted(int $id, ?string $transactionId = null): void
    {
        $data = [
            'status' => 'completed',
            'paid_at' => date('Y-m-d H:i:s'),
        ];
        if ($transactionId) {
            $data['transaction_id'] = $transactionId;
        }
        $this->update($id, $data);
    }

    /**
     * Mark payment as failed
     */
    public function markFailed(int $id): void
    {
        $this->update($id, ['status' => 'failed']);
    }

    /**
     * Process refund
     */
    public function refund(int $id): void
    {
        $this->update($id, ['status' => 'refunded']);
    }
}
