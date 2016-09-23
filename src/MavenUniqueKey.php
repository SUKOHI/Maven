<?php

namespace Sukohi\Maven;

use Illuminate\Database\Eloquent\Model;
use Sukohi\Neatness\NeatnessTrait;
use Sukohi\Smoothness\SmoothnessTrait;

class MavenUniqueKey extends Model
{
    use SmoothnessTrait, NeatnessTrait;

    protected $smoothness = [
        'columns' => [
            'q' => 'scope::filterSearch',
            'locale' => 'scope::filterLocale',
            'tag' => 'scope::filterTag'
        ],
        'condition' => 'and'
    ];
    protected $neatness = [
        'default' => ['sort', 'asc'],
        'columns' => [
            'sort' => 'sort',
            'created' => 'created_at',
            'updated' => 'updated_at'
        ],
        'appends' => ['q', 'tag']
    ];

    // Relationship

    public function faqs() {

        return $this->hasMany('Sukohi\Maven\MavenFaq', 'unique_key_id', 'id');

    }

    public function faq() {

        return $this->hasOne('Sukohi\Maven\MavenFaq', 'unique_key_id', 'id')->where('locale', Maven::getLocale());

    }

    // Accessors

    public function getSortIdAttribute() {

        if(is_numeric($this->sort)) {

            return $this->sort + 1;

        }

        return -1;

    }

    // Scopes

    public function scopeFilterSearch($query, $value) {

        $unique_key_ids = MavenFaq::where(function($query) use($value){

            $query->where('question', 'LIKE', '%'. $value .'%')
                ->orWhere('answer', 'LIKE', '%'. $value .'%');

        })
        ->where('locale', Maven::getLocale())
        ->lists('unique_key_id');

        return $query->whereIn('id', $unique_key_ids);

    }

    public function scopeFilterTag($query, $value) {

        $unique_key_id = MavenTag::where('tag', 'LIKE', '%'. $value .'%')->lists('unique_key_id');
        return $query->whereIn('id', $unique_key_id);

    }

    // Retrieve

    public function getFaq($locale) {

        $faqs = $this->faqs;

        return $faqs->first(function($key, $faq) use($locale){

            return $faq->locale == $locale;

        });

    }

    public function getQuestion($locale) {

        return $this->getFaq($locale)->question;

    }

    public function getRawQuestion($locale) {

        return $this->getFaq($locale)->raw_question;

    }

    public function getAnswer($locale) {

        return $this->getFaq($locale)->answer;

    }

    public function getRawAnswer($locale) {

        return $this->getFaq($locale)->raw_answer;

    }

    public function getTags($locale) {

        $tags = [];
        $faq = $this->getFaq($locale);

        if($faq->tags->count() > 0) {

            foreach ($faq->tags as $tag) {

                $tags[] = $tag->tag;

            }

        }

        return collect($tags);

    }

    // Others

    public function hasLocale($locale) {

        return $this->getFaq($locale);

    }

    public static function selectOptions() {

        $options = ['' => trans('maven.choose_one')];
        $unique_keys = MavenUniqueKey::where('draft_flag', 0)->get();

        foreach ($unique_keys as $index => $unique_key) {

            $key = $index + 1;
            $options[$key] = $key;

        }

        $options_count = count($options);
        $options[$options_count] = $options_count;

        return $options;

    }
}
