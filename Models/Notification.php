<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['id', 'task_id', 'creator_id', 'assignee_id', 'comment', 'type', 'processed', 'status'];
    public $timestamps = false;
}