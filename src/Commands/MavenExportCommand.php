<?php

namespace Sukohi\Maven\Commands;

use Illuminate\Console\Command;
use Sukohi\Maven\Faq;

class MavenExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maven:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export FAQ data';

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

        foreach ($tables as $table) {

            $values = \DB::table($table)->get();

            if(count($values)) {

                $json = json_encode($values);
                \Storage::put('maven/'. $table .'.json', $json);
                $this->info('"'.$table .'" exported!');

            }

        }

    }
}
