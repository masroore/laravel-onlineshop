<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{

    protected $table = "products";
    protected $fillable = [
        'category_id',
        'discount_id',
        'tags',
        'keywords',
        'price',
        'name',
        'market_price',
        'status',
        'desciption',
        'use_icon',
        'use_icon_period_from',
        'use_icon_period_to',
        'images',
        'visible',
        'use_slideshow',
        'number_view',
        'number_like',
        'number_bookmark',
        'number_share',
        'number_item'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * Query scope
     */
    public function scopeActive($query)
    {
        return $query->where('visible', '=', 'Y');
    }

    public function scopeNewProductCreate($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * End query scope
     */
    public function setCreatedByAttribute($email)
    {
        $this->attributes['created_by'] = Auth::user()->email;
    }

    public function setUpdatedByAttribute($email)
    {
        $this->attributes['updated_by'] = Auth::user()->email;
    }

    public function getImages($size = null)
    {
        if($this->attributes['images'])
        {
            $images = $this->attributes['images'];
            $id = $this->attributes['id'];
            $path = "images/products/" . $id;

            $images = explode("|", $images);
            $images = array_map(function ($image) use ($path)
            {
                return "$path/$image";
            }, $images);
            return array_filter($images);
        }
        return array();
    }

    public function getImage()
    {
        $images = $this->getImages();
        if(!empty($images))
        {
            return reset($images);
        }
        return 'http://vignette3.wikia.nocookie.net/galaxylife/images/7/7c/Noimage.png/revision/latest?cb=20120622041841';
    }

    function getSortDesciprtion($input, $length = 15, $ellipses = true,
            $strip_html = true)
    {
        //strip tags, if desired
        if ($strip_html)
        {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length)
        {
            return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        if ($last_space !== false)
        {
            $trimmed_text = substr($input, 0, $last_space);
        }
        else
        {
            $trimmed_text = substr($input, 0, $length);
        }
        //add ellipses (...)
        if ($ellipses)
        {
            $trimmed_text .= '...';
        }

        return $trimmed_text;
    }

    public function setNewNameProductCopy()
    {
        $name = $this->attributes['name'] . " copy ";
        $this->setAttribute('name', $name);
    }

    public function updateNumberView()
    {
        $currentView = (int) $this->attributes['number_view'] + 1;
        $this->setAttribute('number_view', $currentView);
    }

}
