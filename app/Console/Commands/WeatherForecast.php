<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\WeatherUpdate;
use Illuminate\Notifications\Notifiable;

class WeatherForecast extends Command
{
  use Notifiable;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:forecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weather forecast to the user';

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
      \Notification::send('forecast', new WeatherUpdate());
    }
}
