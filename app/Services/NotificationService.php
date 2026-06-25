<?php

namespace App\Services;

use Illuminate\Session\SessionManager;

class NotificationService
{
    protected SessionManager $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Add a success notification
     */
    public function success(string $message, string $title = 'Success'): void
    {
        $this->add('success', $message, $title);
    }

    /**
     * Add an error notification
     */
    public function error(string $message, string $title = 'Error'): void
    {
        $this->add('error', $message, $title);
    }

    /**
     * Add a warning notification
     */
    public function warning(string $message, string $title = 'Warning'): void
    {
        $this->add('warning', $message, $title);
    }

    /**
     * Add an info notification
     */
    public function info(string $message, string $title = 'Info'): void
    {
        $this->add('info', $message, $title);
    }

    /**
     * Add a generic notification
     */
    protected function add(string $type, string $message, string $title): void
    {
        $notifications = $this->session->get('notifications', []);

        $notifications[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ];

        $this->session->put('notifications', $notifications);
    }

    /**
     * Get all notifications and clear from session
     */
    public function getAndClear(): array
    {
        $notifications = $this->session->get('notifications', []);
        $this->session->forget('notifications');

        return $notifications;
    }

    /**
     * Get all notifications without clearing
     */
    public function get(): array
    {
        return $this->session->get('notifications', []);
    }

    /**
     * Clear all notifications
     */
    public function clear(): void
    {
        $this->session->forget('notifications');
    }

    /**
     * Check if there are any notifications
     */
    public function has(): bool
    {
        return count($this->get()) > 0;
    }

    /**
     * Batch add notifications
     */
    public function addMultiple(array $notifications): void
    {
        foreach ($notifications as $notification) {
            $this->add(
                $notification['type'] ?? 'info',
                $notification['message'] ?? '',
                $notification['title'] ?? ucfirst($notification['type'] ?? 'Info')
            );
        }
    }
}
