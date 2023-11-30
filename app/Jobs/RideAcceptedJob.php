<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

// use App\Models\Ride;
use Illuminate\Support\Facades\Mail;
use App\Mail\RideAccepted;

class RideAcceptedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ride;
    /**
     * Create a new job instance.
     */
    public function __construct($ride)
    {
        $this -> ride = $ride;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this -> ride -> userInfo -> email)->send(new RideAccepted($this -> ride));
    }
}
