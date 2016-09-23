<?php

namespace Sukohi\Maven\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Sukohi\Maven\Faq;

class MavenImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maven:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import FAQ data';

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
        $tables = ['maven_faqs', 'maven_tags', 'maven_unique_keys'];
        $dt = Carbon::now();

        foreach ($tables as $table) {

            \DB::table($table)->truncate();
            $json = Storage::get('maven/'. $table .'.json');

            if(!empty($json)) {

                $json_data = json_decode($json, true);

                if(count($json_data) > 0) {

                    foreach ($json_data as $json_values) {

                        $json_values['created_at'] = $dt;
                        $json_values['updated_at'] = $dt;
                        \DB::table($table)->insert($json_values);

                    }

                }

                $this->info('"'.$table .'" imported!');

            }

        }

    }
}
