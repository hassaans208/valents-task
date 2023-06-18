<?php

namespace App\Helpers;

class Helpers
{

    public static function attachImage($model, $image, $collection)
    {
        $model->addMedia($image)->toMediaCollection($collection);
        $model->save();
    }
    public static function deletePrevImage($model, $collection)
    {
        $model->getMedia($collection)->map(function ($item) {
            $item->delete();
        });
    }
}
