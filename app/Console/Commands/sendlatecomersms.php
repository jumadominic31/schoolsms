<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Student;

class sendlatecomersms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendlatecomersms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sends sms every weekend when a student has come late';

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
        $students = Student::count();
        dd($students);
        // echo "ati what... \n";
    }
}
