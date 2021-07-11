<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\IProduto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EstoqueRequest extends FormRequest
{

    public function __construct(
        public IProduto $iProduto,
        array $query = [],
        array $request = []
    ){
        parent::__construct(query: $query, request: $request);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'produto_id' => 'required|integer',
            'quantidade' => 'required|integer'
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            if (!$validator->errors()->count()) {
                if ($this->quantidade === 0) {
                    $validator->errors()->add('quantidade', 'A quantidade deve ser diferente de 0');
                }
                 else if ($this->iProduto->find($this->produto_id) == null) {
                    $validator->errors()->add('produto_id', 'Produto não encontrado');
                }
            }
        });
    }
}
