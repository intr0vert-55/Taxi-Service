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
use App\Mail\NewRide;

class NewRideJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $ride;
    /**
     * Create a new job instance.
     */
    public function __construct($ride)
    {
        $this -> $ride = $ride;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this -> ride -> driverInfo -> email)->send(new NewRide($this -> ride));
    }
}
