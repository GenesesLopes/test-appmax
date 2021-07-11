<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\IEstoque;
use App\Repositories\Contracts\IProduto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class EstoqueRequest extends FormRequest
{

    public function __construct(
        public IProduto $iProduto,
        public IEstoque $iEstoque,
        array $query = [],
        array $request = []
    ) {
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
            'quantidade' => 'required|integer|gt:0'
        ];
    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {

            if (!$validator->errors()->count()) {
                if ($this->iProduto->find($this->produto_id) == null) {
                    $validator->errors()->add('produto_id', 'Produto não encontrado');
                } else if ($this->isMethod('PUT')) { // Validação para baixa de produtos
                    if ($this->iEstoque->countQuantidade($this->produto_id) < $this->quantidade) {
                        $validator->errors()->add('quantidade', 'Quantidade a ser removida deve ser igual ou superior à quantidade em estoque');
                    }
                }
            }
        });
    }
}
