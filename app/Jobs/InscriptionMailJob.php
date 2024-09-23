<?php

namespace App\Jobs;

use App\Mail\AccountCreated;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; 

class InscriptionMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Processing ClientMailJob for user: ' . $this->user->email);
            
            Mail::to($this->user->email)->send(new AccountCreated($this->user->nom)); 
          
            Log::info('Email successfully sent to: ' . $this->user->email);

        } catch (\Exception $e) {
            Log::error('Failed to send email to ' . $this->user->email . ': ' . $e->getMessage());
        }
    }
}
