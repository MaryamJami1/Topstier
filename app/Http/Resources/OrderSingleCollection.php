<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderProductCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSingleCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   

        $guest_user = $this->user === null;
        if($guest_user){
            $user_info = json_decode($this->shipping_address, true);
             $user = [
                'name' => $user_info['name'] ?? '',
                'email' => $user_info['email'] ?? '',
                'phone' => $user_info['phone_number'] ?? '',
                'avatar' => ""
             ];
        }else{
             $user = [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'avatar' => api_asset($this->user->avatar),
             ];
        }
        
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user' => $user,
            'shipping_address' => json_decode($this->shipping_address),
            'billing_address' => json_decode($this->billing_address),
            'grand_total' => (double) $this->grand_total,
            'orders' => OrderResource::collection($this->orders),
            'date' => $this->created_at->toFormattedDateString()
        ];
    }
    
    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}