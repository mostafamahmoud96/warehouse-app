<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStockTransfeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fromWarehouseId'         => 'required|exists:warehouses,id',
            'toWarehouseId'           => 'required|exists:warehouses,id',
            'items'                   => 'required|array',
            'items.*.inventoryItemId' => 'required|distinct|exists:inventory_items,id',
            'items.*.quantity'        => 'required|integer|min:1',
        ];
    }
}
