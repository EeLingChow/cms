<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends ApiModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    protected $logLastUser = true;

    public function __construct()
    {
        parent::__construct();

        $this->routename = 'floors';
        $this->rules = [
            'name' => 'required|max:255'
        ];
    }

    public function shops()
    {
        return $this->belongsToMany('App\Models\Shop', 'category_assignment');
    }

    public function getChoices()
    {
        $parsed = [];

        $categories = $this->all();

        foreach ($categories as $c) {
            $parsed[$c->id] = $c->name;
        }

        return $parsed;
    }
}
