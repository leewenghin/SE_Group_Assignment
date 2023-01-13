<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Validator;

class ImageOrVideo implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!$this->is_image($attribute, $value) && !$this->is_video($attribute, $value)) {
            $fail('File must be image or video.');
        }

        // if ($is_video) {
        //     $validator = Validator::make(
        //         ['video' => $value],
        //         ['video' => "max:102400"]
        //     );
        //     if ($validator->fails()) {
        //         $fail("Video must be less than 10 megabytes (MB).");
        //     }
        // }

        // if ($is_image) {
        //     $validator = Validator::make(
        //         ['image' => $value],
        //         ['image' => "max:2048"]
        //     );
        //     if ($validator->fails()) {
        //         $fail("Image must be less than 2 megabyte (MB).");
        //     }
        // }
    }

    public function is_image($attribute, $value)
    {
        return Validator::make([$attribute => $value],[$attribute => 'image'])->passes();
    }

    public function is_video($attribute, $value)
    {
        return Validator::make([$attribute => $value],[$attribute => 'mimetypes:video/*'])->passes();
    }
}
