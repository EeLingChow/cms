<?php

namespace App\Models;

use App\Models\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use DB;

class Shop extends ApiModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'shop';
    protected $with = ['categories'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'floor_id',
        'name'
    ];

    protected $logLastUser = true;

    public function __construct()
    {
        parent::__construct();

        $this->routename = 'shops';
        $this->rules = [
            'floor_id' => 'required|numeric',
            'name' => 'required|max:255'
        ];
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_assignment', 'shop_id', 'category_id');
    }

    public function afterSave(Request $request)
    {
        if ($this->id) {
            DB::table('category_assignment')
                ->where('shop_id', $this->id)
                ->delete();

            $data = [];
            $postdata = $request->all();

            if (isset($postdata['categories']) && !empty($postdata['categories'])) {
                foreach ($postdata['categories'] as $cid) {
                    $data[] = [
                        'shop_id' => $this->id,
                        'category_id' => $cid,
                    ];
                }
            }

            if (!empty($data)) {
                DB::table('category_assignment')->insert($data);
            }
        }
    }
}
