<?php

namespace Modules\Subscriptions\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Subscriptions\Models\Subscription;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CheckSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'checkSubscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $activeUser = User::where('is_subscribe', 1)->with('subscriptionPackage')->get();
        foreach ($activeUser as $key => $user) {
            $package = Subscription::where('user_id', $user->id)->where('status', config('constant.SUBSCRIPTION_STATUS.ACTIVE'))->first();
            $expired_date = date('Y-m-d', strtotime(optional($user->subscriptionPackage)->end_date));
            $today_date = date('Y-m-d');

            if (strtotime($expired_date) < strtotime($today_date)) {
                
                $user->is_subscribe = 0;
                $user->save();
                $package->status = config('constant.SUBSCRIPTION_STATUS.INACTIVE');
                $package->save();
            }
            
        }

        exit;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
