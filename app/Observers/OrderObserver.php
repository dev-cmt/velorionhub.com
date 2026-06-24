<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Fields that should NOT be individually tracked in the changes diff
     * (timestamps, or fields tracked separately via dedicated columns)
     */
    protected array $ignoredFields = [
        'updated_at',
        'created_at',
    ];

    /**
     * Human-readable order status labels.
     */
    protected array $statusLabels = [
        0  => 'Pending',
        1  => 'Confirmed',
        2  => 'Hold',
        3  => 'Cancelled',
        4  => 'Stockout',
        5  => 'Packaged',
        6  => 'Courier Entry',
        7  => 'On Delivery',
        8  => 'Delivered',
        9  => 'Partial Delivered',
        10 => 'Exchange',
        11 => 'Return',
        12 => 'Return Received',
    ];

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        OrderHistory::withoutTimestamps(fn () =>
            OrderHistory::create([
                'order_id'   => $order->id,
                'user_id'    => Auth::id() ?? null,
                'action'     => 'created',
                'old_status' => null,
                'new_status' => $order->status,
                'changes'    => null,
                'reason'     => request()->input('notes')
                             ?: request()->input('remarks')
                             ?: 'Order placed/created',
            ])
        );
    }

    /**
     * Handle the Order "updating" event (fires BEFORE the record is saved).
     */
    public function updating(Order $order): void
    {
        $dirty = $order->getDirty();

        if (empty($dirty)) {
            return;
        }

        $oldStatus = $order->getOriginal('status');
        $newStatus = array_key_exists('status', $dirty) ? (int) $dirty['status'] : (int) $oldStatus;

        $action = ($oldStatus != $newStatus) ? 'status_changed' : 'updated';

        // Build changes diff — exclude ignored fields and status (tracked separately)
        $fieldsToExclude = array_merge($this->ignoredFields, ['status']);
        $changes = [];
        foreach ($dirty as $key => $newValue) {
            if (in_array($key, $fieldsToExclude)) {
                continue;
            }
            $changes[$key] = [
                'old' => $order->getOriginal($key),
                'new' => $newValue,
            ];
        }

        // If only status changed and nothing else meaningful — skip extra changes payload
        // If nothing changed at all (only ignored fields were dirty) — bail out
        $allDirtyMeaningful = array_diff_key($dirty, array_flip($this->ignoredFields));
        if (empty($allDirtyMeaningful)) {
            return;
        }

        // Determine the reason
        $reason = $this->detectReason($oldStatus, $newStatus);

        OrderHistory::withoutTimestamps(fn () =>
            OrderHistory::create([
                'order_id'   => $order->id,
                'user_id'    => Auth::id() ?? null,
                'action'     => $action,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changes'    => !empty($changes) ? $changes : null,
                'reason'     => $reason,
            ])
        );
    }

    /**
     * Detect the human-readable reason for this update.
     */
    protected function detectReason($oldStatus, $newStatus): string
    {
        $req = request();

        // Route-based detection (most specific first)
        if ($req->routeIs('orders.bulk-status')) {
            return 'Bulk status update by ' . (Auth::user()->name ?? 'System');
        }

        if ($req->routeIs('orders.single-status-ajax')) {
            return 'Status updated inline from order list';
        }

        if ($req->routeIs('orders.bulk-assign')) {
            return 'Bulk assignment updated by ' . (Auth::user()->name ?? 'System');
        }

        if ($req->routeIs('courier.send.row') || $req->routeIs('orders.courier-export')) {
            return 'Order dispatched to courier';
        }

        if ($req->routeIs('return.receive.send.row')) {
            return 'Return Received module processed';
        }

        if ($req->is('*webhook*') || $req->is('*callback*') || $req->routeIs('*webhook*')) {
            $reason = 'System Webhook Callback Update';
            if ($req->has('status')) {
                $reason .= ': ' . $req->input('status');
            }
            return $reason;
        }

        // Try explicit reason from request body
        $explicit = $req->input('edit_reason')
                 ?: $req->input('remarks')
                 ?: $req->input('notes');

        if ($explicit) {
            return $explicit;
        }

        // Auto-generate from status change
        if ($oldStatus != $newStatus) {
            $oldLabel = $this->statusLabels[$oldStatus] ?? "Status {$oldStatus}";
            $newLabel = $this->statusLabels[$newStatus] ?? "Status {$newStatus}";
            return "Status changed from '{$oldLabel}' to '{$newLabel}'";
        }

        return 'Order details updated';

    }
}


