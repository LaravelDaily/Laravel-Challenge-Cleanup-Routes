<?php

namespace App\Jobs\StripeWebhooks;

use App\Models\User;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Spatie\WebhookClient\Models\WebhookCall;
use App\Notifications\SendOrderNotification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HandleChargeSucceeded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stripeUser = $this->webhookCall->payload['data']['object']['customer'];
        $user = User::where('stripe_id', $stripeUser)->first();

        if ($user) {
            $order = Order::where('user_id', $user->id)->whereNull('paid_at')->latest()->first();
            $order->update(['paid_at' => now()]);

            $user->notify(new SendOrderNotification($user, $order));
        }
    }
}
