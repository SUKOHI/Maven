<?php namespace Sukohi\Maven;

use Illuminate\Support\Facades\Storage;

class Maven {

	private $_tags, $_unique_keys = [];
    private $_draft_flag = false;
    public static $locale_path = 'maven/locale.txt';

	public function tag($tags) {

        $this->_tags = [];

        foreach ($tags as $locale => $tag) {

            if(!is_array($tag)) {

                $tag = [$tag];

            }

            $this->_tags[$locale] = $tag;

	    }

		return $this;

	}

	public function uniqueKey($unique_key) {

		if(!is_array($unique_key)) {

			$unique_key = [$unique_key];

		}

		$this->_unique_keys = array_unique($unique_key);
		return $this;

	}

	public function draft_flag($boolean) {

	    $this->_draft_flag = $boolean;

    }

	public function get($limit = 30) {

	    $query = MavenUniqueKey::with('faqs.tags')
            ->orderBy('sort', 'asc');

        if(!$this->_draft_flag) {

            $query->where('draft_flag', false);

        }

		if(count($this->_tags) > 0) {

		    $tag_query = MavenTag::join('maven_faqs', 'maven_tags.faq_id', '=', 'maven_faqs.id');

            foreach ($this->_tags as $locale => $tags) {

                foreach ($tags as $tag) {

                    $tag_query->orWhere(function($query) use($locale, $tag){

                        $query->where('maven_faqs.locale', $locale)
                            ->where('maven_tags.tag', $tag);

                    });

                }

		    }

            $unique_key_ids = $tag_query->pluck('maven_tags.unique_key_id');
            $query->whereIn('id', $unique_key_ids);

		}

		if(count($this->_unique_keys) > 0) {

            $query->where(function($query){

				foreach ($this->_unique_keys as $unique_key) {

					$query->orWhere('unique_key', $unique_key);

				}

			});

		}

		return $query->paginate($limit);

	}

	public function first() {

		$faqs = $this->get();

		if($faqs->count() > 0) {

			return $faqs[0];

		}

		return null;

	}

	public function getAllTags($draft_filter_flag = true) {

        $tags = [];
        $faqs = MavenFaq::with('unique_key')
            ->where('draft_flag', 0)
            ->get();

        foreach ($faqs as $faq) {

            if($draft_filter_flag && $faq->unique_key->draft_flag == 1) {

                continue;

            }

            $maven_tags = $faq->tags;

            foreach ($maven_tags as $maven_tag) {

                if(!isset($tags[$faq->locale]) ||
                    !in_array($maven_tag->tag, $tags[$faq->locale])) {

                    $tags[$faq->locale][] = $maven_tag->tag;

                }

            }

        }

        return collect($tags);

    }

	public static function route($default_locale = 'en') {

        \App::setLocale($default_locale);

        \Route::group(['prefix' => config('maven.uri')], function() {

            \Route::get('/', 'Maven\MavenController@index')->name('maven.index');
            \Route::get('/create', 'Maven\MavenController@create')->name('maven.create');
            \Route::post('/', 'Maven\MavenController@store')->name('maven.store');
            \Route::get('/{id}/edit', 'Maven\MavenController@edit')->name('maven.edit');
            \Route::put('/{id}', 'Maven\MavenController@update')->name('maven.update');
            \Route::delete('/{id}', 'Maven\MavenController@destroy')->name('maven.destroy');
            \Route::get('/{locale}', 'Maven\MavenController@locale')->name('maven.locale');

        });

	}

	public static function setLocale($locale) {

        \Storage::put(self::$locale_path, $locale);

    }

	public static function getLocale() {

	    $filename = self::$locale_path;

	    if(Storage::exists($filename)) {

	        return Storage::get($filename);

        }

        return \App::getLocale();

    }

    public static function getModel($model) {

        $class_name = 'Sukohi\Maven\\'. studly_case('maven_'. $model);

        if(!class_exists($class_name)) {

            throw new \Exception($class_name .' does not exist.');

        }

        return new $class_name;

    }

}