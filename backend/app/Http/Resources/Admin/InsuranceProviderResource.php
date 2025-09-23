<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsuranceProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo_url' => $this->logo_url,
            'pros' => $this->pros,
            'cons' => $this->cons,
            'price' => $this->price,
            'is_sponsored' => $this->is_sponsored,
            'status' => $this->status,
            'about' => $this->about,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
            'policies' => PolicyCategoryResource::collection($this->whenLoaded('policyCategories')),
            'states' => StatesResource::collection($this->whenLoaded('states')),
        ];
    }
}
