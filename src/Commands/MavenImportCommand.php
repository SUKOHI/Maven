<?php

namespace Sukohi\Maven\Commands;

use Illuminate\Console\Command;
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
		Faq::truncate();
		
		$json = \Storage::get('maven/faq.json');
		$items = json_decode($json, true);

		if(!empty($items)) {

			foreach ($items as $index => $item) {

				$faq = new Faq();
				$faq->question = $item['question'];
				$faq->answer = $item['answer'];
				$faq->sort = $item['sort'];
				$faq->tags = json_decode($item['tags'], true);
				$faq->locale = $item['locale'];
				$faq->unique_key = $item['unique_key'];
				$faq->draft_flag = $item['draft_flag'];
				$faq->created_at = $item['created_at'];
				$faq->updated_at = $item['updated_at'];
				$faq->save();

			}

		}

    }
}
