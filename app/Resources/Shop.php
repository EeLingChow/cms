<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Shop extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'categories' => [],
            'floor' => null,
            'meta' => [
                'created'   => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
                'updated'   => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
                'by' => $this->updated_by,
            ],
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
