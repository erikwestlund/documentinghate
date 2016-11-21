<?php

namespace App\Console\Commands;

use Notification;

use App\User;
use App\Notifications\UnmoderatedIncidentsNotification;

use Illuminate\Console\Command;

class UnmoderatedIncidentNotifier extends Command
{
    protected $frequencies;
    protected $group;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:moderators
                            {group? : twice_a_day|daily|weekly|never|all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify moderators of unmoderated incidents.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->frequencies = (new User)->notification_levels;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->group = $this->argument('group');

        // if group provided, and its not all, or its not in our frequency list, abort
        if($this->group && (! in_array($this->group, $this->frequencies) && $this->group != 'all')) {
            $this->error('Invalid group provided.');
            return false;
        }

        // if no group provided, use all
        if(! $this->group) {
            $this->group = 'all';
        }

        $users_to_notify = $this->getUsersToNotify($this->group);

        dd($users_to_notify);

        Notification::send($users_to_notify, new UnmoderatedIncidentsNotification);
    }


    protected function getUsersToNotify($group)
    {
        $users = User::moderators();

        if($group == 'twice_a_day') {
            $users = $users->receivesTwiceDailyNotifications();
        } else if($group == 'daily') {
            $users = $users->receivesDailyNotifications();
        }

        return User::where('moderation_notification_frequency', $group)
            ->get();
    }
}
