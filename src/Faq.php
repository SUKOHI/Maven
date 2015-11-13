<?php

namespace Sukohi\Maven;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
	use SoftDeletes;

	public $guarded = ['id'];

	// Accessor

	public function getAnswerAttribute($value) {

		return nl2br($value);

	}
	public function getRawAnswerAttribute($value) {

		return $this->attributes['answer'];

	}

	public function getDraftFlagIconAttribute() {

		return ($this->attributes['draft_flag']) ? '<i class="glyphicon glyphicon-ok-circle text-success"></i>' : '';

	}

	public function getTagsAttribute($value) {

		if(empty($value)) {

			return [];

		}

		return json_decode($value, true);

	}

	public function getSortNumberAttribute() {

		return $this->attributes['sort'] + 1;

	}

	// Mutator

	public function setTagsAttribute($values) {

		if(!is_array($values)) {

			$values = [];

		}

		$this->attributes['tags'] = json_encode($values, JSON_UNESCAPED_UNICODE );

	}

	// Others

	public static function maxSortNumber() {

		return Faq::max('sort');

	}

	public static function sortSelectValues() {

		$values = [];
		$max_sort_number = Faq::count();

		for($i = 1 ; $i <= $max_sort_number ; $i++) {

			$values[$i] = '#'. $i;

		}

		$values[$i] = '#'. $i;

		return $values;

	}

	public static function tagValues() {

		$values = [];
		$faqs = Faq::select('tags')->get();

		foreach ($faqs as $index => $faq) {

			$tags = $faq->tags;

			foreach ($tags as $index => $tag) {

				if(!in_array($tag, $values)) {

					$values[] = $tag;

				}

			}

		}
		sort($values);

		return $values;

	}
}
