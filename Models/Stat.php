<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $table = 'stat';
    protected $fillable = ['id', 'task_id', 'assignee_id', 'status_id', 'timestamp'];
    public $timestamps = false;
}