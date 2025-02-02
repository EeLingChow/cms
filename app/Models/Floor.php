<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floor extends ApiModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'floor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level'
    ];

    protected $logLastUser = true;

    public function __construct()
    {
        parent::__construct();

        $this->routename = 'floors';
        $this->rules = [
            'level' => 'required'
        ];
    }

    public function getChoices()
    {
        $parsed = [];

        $levels = $this->all();

        foreach ($levels as $l) {
            $parsed[$l->id] = $l->level;
        }

        return $parsed;
    }
}
