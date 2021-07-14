<?php

namespace App\Http\Requests;

use App\Repositories\Contracts\IProduto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProdutoRequest extends FormRequest
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
            'sku' => [
                Rule::requiredIf($this->isMethod('POST') || $this->isMethod('PUT')), 
                'alpha_num'
            ],
            'nome' => [
                Rule::requiredIf($this->isMethod('POST') || $this->isMethod('PUT')),
                'string'
                ]
        ];
    }

    public function withValidator(Validator $validator) {
        $validator->after(function (Validator $validator){
            if(!$validator->errors()->count()){
                if($this->isMethod('post') || $this->isMethod('put')){
                    $produto = $this->iProduto->findBySku($this->sku);
                    if( $produto !== null){
                        if($this->isMethod('post') || $produto->id != $this->id)
                            $validator->errors()->add('sku', 'Sku já presente na aplicação');
                    }
                }
                else if($this->isMethod('delete')){
                    if($this->iProduto->find($this->id) == null){
                        $validator->errors()->add('id', 'Produto não encontrado');
                    }
                }
                
            }
        });
    }   
}
