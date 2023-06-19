<?php

namespace App\Helpers;

class Helpers
{
    /**
     * Attach image with model
     *
     * @param Model $model is any model
     * @param Array | Collection | String $image is and image
     * @param String $collectin is the name of the collection so spatie could organize it accordingly image
     */

    public static function attachImage($model, $image, $collection)
    {
        // preserving original copies the file instead of moving it to tmp folder and handles an error
        $model->addMedia($image)->preservingOriginal()->toMediaCollection($collection);
        $model->save();
    }
    /**
     * Delete Prvious Image from $model
     *
     * @param Model $model is any model
     * @param String $collectin is the name of the collection so spatie could organize it accordingly image
     */
    public static function deletePrevImage($model, $collection)
    {

        $modelItem = $model->getMedia($collection);

        if(count($modelItem)){
            $modelItem[0]->delete();
        }
    }
}
