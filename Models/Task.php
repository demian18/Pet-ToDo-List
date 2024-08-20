<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['id', 'title', 'body', 'status_id', 'creator_id', 'assignee_id'];
    public $timestamps = false;

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Stat::class, 'status_id');
    }
}