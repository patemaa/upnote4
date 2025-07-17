<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'body',
        'is_archived',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    #[Scope]
    protected function search(Builder $query, $value) : void
    {
        $query
            ->where('title', 'like', '%' . $value . '%')
            ->orWhere('body', 'like', '%' . $value . '%');
    }
}