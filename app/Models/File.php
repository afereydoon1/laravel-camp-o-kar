<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class File extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'description', 'file', 'price', 'membership_id', 'image'
    ];

    protected $hidden = ['file'];

    protected $appends = ['membership_name', 'selectedTags', 'image_src', 'price_in_toman'];

    protected $columns = [
        'n' => 'name',
        'd' => 'description',
        'p' => 'price',
        'm' => 'membership_id',
        'ca' => 'created_at',
    ];
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function confirmed_comments()
    {
        return $this->hasMany(Comment::class)
            ->whereIsConfirmed(true)
            ->whereNull('comment_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('payment_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')
            ->withPivot('is_liked');
    }


    public function scopeSortByUrl(Builder $builder)
    {
        $sortBy = $this->columns[request()->sortby] ?? 'id';
        $sortdir = request()->sortdir === 'desc' ? 'desc' : 'asc';

        return $builder->orderBy($sortBy, $sortdir);
    }


    public function scopeSearchByUrl(Builder $builder)
    {
        if (request()->search) {
            $builder->where('name', 'LIKE', '%' . request()->search . '%')
                ->orWhere('description', 'LIKE', '%' . request()->search . '%');
        }
        return $builder;
    }

    public function syncCategories(array $categories)
    {
        $this->categories()->sync(
            array_filter(collect($categories)->pluck('key')->toArray())
        );
    }

    public function getUrlPathAttribute()
    {
        return url("/file/show/{$this->slug}");
    }

    public function getStoragePathAttribute()
    {
        return storage_path("app\\files\\{$this->file}");
    }

    public function getMembershipNameAttribute()
    {
        if ($this->membership_id) {
            return $this->membership->name;
        }
        return 'اشتراک ویژه ندارد';
    }

    public function getPriceInTomanAttribute()
    {
        if ($this->price) {
            return $this->price . ',000 تومان';
        }
        return 'قیمت ندارد';
    }

    public function getTomanPriceAttribute()
    {
        if ($this->price) {
            return (int) ($this->price . '000');
        }
        return null;
    }

    public function getFileSrcAttribute()
    {
        return 'files/' . $this->file;
    }

    public function getImageSrcAttribute()
    {
        return 'images/' . $this->image;
    }

    public function getSelectedTagsAttribute()
    {
        return $this->categories->map(function ($item, $key) {
            return ['key' => $item->id, 'value' => $item->name];
        });
    }

    public function getRelatedFilesAttribute(): Collection
    {
        return $this->where('id', '!=', $this->id)->whereHas('categories', function ($query) {
            return $query->whereIn('categories.id', $this->categories->pluck('id')->toArray());
        })->inRandomOrder()->limit(3)->get();
    }

    public function getIsUserLikedAttribute(): bool
    {
        $file_like = $this->likes()
            ->whereUserId(request()->user()->id)
            ->first();
        return $file_like ? $file_like->pivot->is_liked : false;
    }
}
