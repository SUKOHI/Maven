<?php

namespace Sukohi\Maven;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MavenFaq extends Model
{
	use SoftDeletes;

	public $guarded = ['id'];

    // Relationships

    public function unique_key() {

        return $this->hasOne('Sukohi\Maven\MavenUniqueKey', 'id', 'unique_key_id');

    }

    public function tags() {

        return $this->hasMany('Sukohi\Maven\MavenTag', 'faq_id', 'id');

    }

	// Accessor

	public function getQuestionAttribute($value) {

		return nl2br($value);

	}
	public function getRawQuestionAttribute($value) {

		return $this->attributes['question'];

	}

	public function getAnswerAttribute($value) {

		return nl2br($value);

	}
	public function getRawAnswerAttribute($value) {

		return $this->attributes['answer'];

	}

	public function getDraftFlagIconAttribute() {

		return ($this->attributes['draft_flag']) ? '<i class="glyphicon glyphicon-ok-circle text-success"></i>' : '';

	}

	public function getSortNumberAttribute() {

		return $this->attributes['sort'] + 1;

	}

	public function getTagStringAttribute() {

	    $tags = $this->tags->lists('tag');
        return $tags->implode(',');

    }

	// Others

	public static function maxSortNumber() {

		return Faq::max('sort');

	}

	public static function sortSelectValues() {

		$values = [];
		$max_sort_number = Faq::count();

		for($i = 1 ; $i <= $max_sort_number ; $i++) {

			$values[$i] = '# '. $i;

		}

		$values[$i] = '# '. $i;

		return $values;

	}
}
