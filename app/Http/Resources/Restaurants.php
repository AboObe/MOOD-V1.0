<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Restaurants extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'description' => $this->description,
            'opening_hours' => $this->opening_hours,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image' => $this->image->name,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'snapchat' => $this->snapchat,
            'whatsapp' => $this->whatsapp,
            'youtube' => $this->youtube,
            'website' => $this->website,
            'qr' => $this->qr,
            'pin' => $this->pin,
            'is_featured' => $this->is_featured,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
