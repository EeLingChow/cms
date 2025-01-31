<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends ApiModel
{
    use HasFactory;

    protected $table = 'audit_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
}
