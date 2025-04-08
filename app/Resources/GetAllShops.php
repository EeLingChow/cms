<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetAllShops extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $data = [
            'name' => $this->name,
            'floor' => null,
            'categories' => null
        ];

        if ($this->floor) {
            $data['floor'] = new Floor($this->floor);
        }

        if ($this->categories) {
            foreach ($this->categories as $c) {
                $d = new Category($c);
                $data['categories'][] = $d;
            }
        }

        return $data;
    }
}
