<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $includes = $request->has('includes') ? explode(',', $request->get('includes')) : [];
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'is_superadmin' => $this->is_superadmin,
            'meta' => [
                'created'   => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
                'updated'   => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
                'by' => $this->updated_by,
            ],
        ];

        if (in_array('modules', $includes)) {
            $data['modules'] = [];

            foreach ($this->modules as $m) {
                $d = new Module($m);
                $d['permission_level'] = $m->pivot->permission;
                $data[] = $d;
            }
        }

        return $data;
    }
}
