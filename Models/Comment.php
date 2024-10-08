<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = ['id', 'comment', 'not_id', 'task_id', 'creator_id', 'assignee_id', 'created_at'];
    public $timestamps = false;
}