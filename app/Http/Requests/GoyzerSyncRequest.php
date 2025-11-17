<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoyzerSyncRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'sometimes|in:sales,rentals,all,updated',
            'bedrooms' => 'sometimes|integer|min:0|max:10',
            'start_price_range' => 'sometimes|numeric|min:0',
            'end_price_range' => 'sometimes|numeric|min:0|gte:start_price_range',
            'category_id' => 'sometimes|integer',
            'state_pk' => 'sometimes|string',
            'community_pk' => 'sometimes|string',
            'unit_pk' => 'sometimes|integer',
            'property_pk' => 'sometimes|string',
            'property_type' => 'sometimes|string',
        ];
    }
}