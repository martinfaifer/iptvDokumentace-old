<?php

namespace App\Console\Commands;

use App\Http\Controllers\H264Controller;
use Illuminate\Console\Command;

class convert_dohledUrl_to_udpxy_in_Uh264 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:convert_dohledUrl_to_udpxy_in_Uh264';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Převedení dohledUrl z tabulky channels do udpxy v tabulce H264';

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
        H264Controller::get_dohledUrl_from_channels_add_to_udpxy_in_this();
    }
}
