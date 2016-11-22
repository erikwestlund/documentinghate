<?php

namespace App;

use App\Notifications;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{

    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $notification_levels = [
        'every_submission',
        'twice_daily',
        'daily',
        'weekly',
        'never'
    ];

    /**
     * Get users subscribed to be notifeid about new incidents.
     * 
     * @return Collection
     */
    public static function subcribedToNewIncidents()
    {
        return parent::where('moderation_notification_frequency', 'every_submission')
            ->get();        
    }    

    /**
     * Scope a query to only include users who can moderate.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModerators($query)
    {
        return $query->whereHas('roles', function($query) {
            $query->where('name', 'superman')
                ->orWhere('name', 'administrator')
                ->orWhere('name', 'moderator');
        });
    }

    /**
     * Scope a query to only include users who can moderate.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministrators($query)
    {
        return $query->whereHas('roles', function($query) {
            $query->where('name', 'superman')
                ->orWhere('name', 'administrator');
        });
    }

    /**
     * Scope a query to include those who get notified of all new incidents
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceivesNotificationEverySubmission($query)
    {
        return $query->where('moderation_notification_frequency', 'every_submission');
    }


    /**
     * Scope a query to include those getting daily notifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNeverReceivesNotifications($query)
    {
        return $query->where('moderation_notification_frequency', 'never');
    }

    /**
     * Scope a query to include those getting daily notifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceivesDailyNotifications($query)
    {
        return $query->where('moderation_notification_frequency', 'daily');
    }

    /**
     * Scope a query to include those getting daily notifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceivesTwiceDailyNotifications($query)
    {
        return $query->where('moderation_notification_frequency', 'twice_daily');
    }

    /**
     * Scope a query to include those getting daily notifications
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceivesWeeklyNotifications($query)
    {
        return $query->where('moderation_notification_frequency', 'weekly');
    }

}
