<?php
namespace App\Http\Requests;

use App\Util\StockTransactionTypeUtil;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
            'warehouseId'             => 'required|exists:warehouses,id',
            'items'                   => 'required|array',
            'items.*.inventoryItemId' => 'required|exists:inventory_items,id',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.transactionType' => 'required|in:' . implode(',', StockTransactionTypeUtil::getValues()),
        ];
    }
}
