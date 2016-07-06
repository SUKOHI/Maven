<?php namespace Sukohi\Maven;

class Maven {

	private $_tags, $_locales = [];

	public function tag($tag) {

		if(!is_array($tag)) {

			$tag = [$tag];

		}

		$this->_tags = array_unique($tag);
		return $this;

	}

	public function locale($locale) {

		if(!is_array($locale)) {

			$locale = [$locale];

		}

		$this->_locales = array_unique($locale);
		return $this;

	}

	public function get($limit = 30) {

		$faqs = Faq::where('draft_flag', false)
					->orderBy('sort', 'ASC');

		if(count($this->_tags) > 0) {

			$faqs->where(function($query){

				foreach ($this->_tags as $tag) {

					$query->orWhere('tags', 'LIKE', '%"'. $tag .'"%');

				}

			});

		}

		if(count($this->_locales) > 0) {

			$faqs->where(function($query){

				foreach ($this->_locales as $locale) {

					$query->orWhere('locale', $locale);

				}

			});

		}

		return $faqs->paginate($limit);

	}

	public function view($limit = 30) {

		$message = '';

		if(\Request::has('remove_id')) {

			$faq = Faq::find(\Request::get('remove_id'));
			$faq->delete();
			$message = 'Complete!';

			$faqs = Faq::orderBy('id', 'ASC')->get();
			\Cahen::align($faqs, 'sort');

		} else if(\Request::has('_token')) {

			if(\Request::has(['question', 'answer'])) {

				$faq = Faq::firstOrNew(['id' => \Request::get('id')]);
				$faq->question = \Request::get('question');
				$faq->answer = \Request::get('answer');
				$faq->tags = explode(',', \Request::get('tags'));
				$faq->locale = \Request::get('locale');
				$faq->draft_flag = \Request::has('draft_flag');

				if(empty($faq->unique_key)) {

					$faq->unique_key = md5(uniqid(rand(),1));

				}

				$faq->save();
				\Cahen::move($faq)->to('sort', \Request::get('sort'));

				$message = 'Complete!';
				\Request::merge([
					'question' => '',
					'answer' => '',
					'tags' => '',
					'sort' => '',
					'draft_flag' => '',
					'id' => '',
				]);

			} else {

				$message = '[Error] Question and Answer are required.';

			}

		} else if(\Request::has('id')) {

			$faq = Faq::find(\Request::get('id'));

			\Request::merge([
				'question' => $faq->question,
				'answer' => $faq->raw_answer,
				'tags' => implode(',', $faq->tags),
				'sort' => $faq->sort_number,
				'locale' => $faq->locale,
				'draft_flag' => $faq->draft_flag
			]);

		}

		$query = Faq::orderBy('sort', 'ASC');

		if(\Request::has('search_locale')) {

			$query->where('locale', \Request::get('search_locale'));

		}

		if(\Request::has('search_key')) {

			$query->where('tags', 'LIKE', '%'. \Request::get('search_key') .'%');

		}

		$faqs = $query->paginate($limit);
		$locales = Faq::distinct('locale')->lists('locale');
		$sort_values = Faq::sortSelectValues();
		$tag_values = Faq::tagValues();

		return view('maven::manage', [
				'faqs' => $faqs,
				'sort_values' => $sort_values,
				'tag_values' => $tag_values,
				'message' => $message,
				'locales' => $locales
		])->render();

	}

}