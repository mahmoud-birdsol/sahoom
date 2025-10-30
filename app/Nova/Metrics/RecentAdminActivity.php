<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Card;

class RecentAdminActivity extends Card
{
    /**
     * The width of the card (1/3, 1/2, 2/3, or full).
     *
     * @var string
     */
    public $width = 'full';

    /**
     * Get the component name for the card.
     *
     * @return string
     */
    public function component()
    {
        return 'recent-admin-activity';
    }

    /**
     * Get the displayable name of the card.
     *
     * @return string
     */
    public function name()
    {
        return 'Recent Admin Actions';
    }

    /**
     * Get the URI key for the card.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'recent-admin-activity';
    }

    /**
     * Prepare the card for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'activities' => $this->getRecentActivities(),
        ]);
    }

    /**
     * Get recent admin activities from the log file.
     *
     * @return array
     */
    protected function getRecentActivities(): array
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return [];
        }

        $activities = [];
        $lines = file($logFile);
        
        if ($lines === false) {
            return [];
        }

        // Read last 500 lines to find relevant entries
        $lines = array_slice($lines, -500);
        
        foreach (array_reverse($lines) as $line) {
            // Match log entries for key actions
            if (preg_match('/\[(.*?)\] .*?(Property|Contract|User|Landlord|ViewingRequest) (created|updated|deleted|approved|rejected|suspended|activated|deactivated|password_reset_sent)/', $line, $matches)) {
                
                // Extract JSON context if available
                if (preg_match('/\{.*\}/', $line, $jsonMatches)) {
                    $context = json_decode($jsonMatches[0], true);
                    
                    $activities[] = [
                        'timestamp' => $matches[1] ?? now()->toDateTimeString(),
                        'model' => $matches[2] ?? 'Unknown',
                        'action' => $matches[3] ?? 'unknown',
                        'user' => $context['performed_by_user_name'] ?? $context['user_name'] ?? 'System',
                        'details' => $this->formatDetails($matches[2], $context),
                    ];
                }
                
                // Stop after collecting 10 activities
                if (count($activities) >= 10) {
                    break;
                }
            }
        }
        
        return array_slice($activities, 0, 10);
    }

    /**
     * Format activity details based on model type.
     *
     * @param string $model
     * @param array $context
     * @return string
     */
    protected function formatDetails(string $model, array $context): string
    {
        return match ($model) {
            'Property' => ($context['property_title'] ?? 'Property') . ' (#' . ($context['property_id'] ?? 'N/A') . ')',
            'Contract' => 'Contract for ' . ($context['renter_name'] ?? 'Unknown'),
            'User' => ($context['user_name'] ?? 'User') . ' (' . ($context['user_email'] ?? '') . ')',
            'Landlord' => ($context['landlord_name'] ?? 'Landlord') . ' (#' . ($context['landlord_id'] ?? 'N/A') . ')',
            'ViewingRequest' => 'Request from ' . ($context['renter_name'] ?? 'Unknown'),
            default => 'Record #' . ($context['id'] ?? 'N/A'),
        };
    }
}
