<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Category $category) {
            foreach ($category->notes as $note) {
                $note->delete();
            }
        });

        static::forceDeleting(function (Category $category) {
            $category->notes()->withTrashed()->get()->each(function ($note) {
                $note->forceDelete();
            });
        });

        static::restoring(function (Category $category) {
            $category->notes()->withTrashed()->get()->each(function ($note) {
                $note->restore();
            });
        });
    }

    #[Scope]
    protected function search(Builder $query, $value) : void
    {
        $query->where('name', 'like', '%' . $value . '%');
    }
}