<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource
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
                'title' => $this->title, 
                'description' => $this->description, 
                'keywords' => $this->keywords, 
                'file' => new FileResource($this->file), 
                'file_id' => $this->file->id ?? null, 
                'created_by' => new UserResource($this->createdBy), 
                'created_by_user_id' => $this->createdBy->id ?? null, 
                'created_at' => $this->created_at->toDateTimeString(), 
            ];
       
    }
}
