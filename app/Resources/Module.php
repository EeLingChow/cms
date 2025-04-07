<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Module extends JsonResource
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
            'master_id' => $this->master_id,
            'name' => $this->name,
            'modulekey' => $this->modulekey,
            'sequence' => $this->sequence,
            'icon' => $this->icon,
            'route' => $this->route,
            'is_superadmin' => $this->is_superadmin,
            'is_master' => $this->is_master,
            'is_hidden' => $this->is_hidden,
            'master' => null,
            'meta' => [
                'created'   => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
                'updated'   => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
                'by' => $this->updated_by,
            ],
        ];

        if ($this->master) {
            $data['master'] = [
                'id' => $this->master->id,
                'name' => $this->master->name,
            ];
        }

        return $data;
    }
}
