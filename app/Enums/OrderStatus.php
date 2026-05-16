<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case PAID = 'paid';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';

    public function label(): string
    {
        return match($this) {
            self::PENDING_PAYMENT => 'Pending Payment',
            self::PAID => 'Paid',
            self::FAILED => 'Payment Failed',
            self::CANCELLED => 'Cancelled',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
        };
    }

    /**
     * Check if this status can transition to the given status
     */
    public function canTransitionTo(OrderStatus $target): bool
    {
        return in_array($target, $this->allowedTransitions());
    }

    /**
     * Get list of allowed transitions from this status
     */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::PENDING_PAYMENT => [
                self::PAID,
                self::FAILED,
                self::CANCELLED,
            ],
            self::PAID => [
                self::PROCESSING,
                self::CANCELLED,
            ],
            self::PROCESSING => [
                self::SHIPPED,
                self::CANCELLED,
            ],
            self::SHIPPED => [
                self::DELIVERED,
            ],
            self::DELIVERED, self::FAILED, self::CANCELLED => [],
        };
    }

    /**
     * Check if this is a terminal status (no further transitions allowed)
     */
    public function isTerminal(): bool
    {
        return empty($this->allowedTransitions());
    }

    /**
     * Check if the order can be cancelled from this status
     */
    public function isCancellable(): bool
    {
        return $this->canTransitionTo(self::CANCELLED);
    }

    /**
     * Statuses that indicate the order has been successfully completed
     */
    public function isSuccessful(): bool
    {
        return in_array($this, [
            self::PAID,
            self::PROCESSING,
            self::SHIPPED,
            self::DELIVERED,
        ]);
    }

    /**
     * Get all statuses as array (useful for validation rules)
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}
