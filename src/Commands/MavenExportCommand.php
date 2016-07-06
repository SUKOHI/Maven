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
        $faqs = \DB::table('faqs')->select(
			'question',
			'answer',
			'sort',
			'tags',
			'locale',
			'unique_key',
			'draft_flag',
			'created_at',
			'updated_at'
		)->get();

		if(count($faqs) > 0) {

			$json = json_encode($faqs);
			\Storage::put('maven/faq.json', $json);
			$this->info('Done.');
			die();

		}

		$this->error('Data not found.');
    }
}
