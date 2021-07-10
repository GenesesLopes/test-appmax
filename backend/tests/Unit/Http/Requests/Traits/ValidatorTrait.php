<?php
declare(strict_types = 1);

namespace Tests\Unit\Http\Requests\Traits;

use Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

trait ValidatorTrait
{

    protected abstract function instanceRequest(
        array $data = [],
        string $method = 'post',
        array $query = []
    ): void;

    protected function assertSuccessValidator(
        string $method = 'post',
        array $data = [],
        array $query = []
    )
    {
        try {
            $this->instanceRequest(            
                data: $data,
                method: $method,
                query: $query
            );
        } catch (ValidationException $ex) {}
        $this->assertTrue(!isset($ex));
    }

    protected function assertInvalidationFieldRule(
        array $data,
        string $rule,
        array $ruleParams = [],
        string $method = 'post'
    ){
        
        try {
            $this->instanceRequest(method: $method, data: $data);
        } catch (ValidationException $ex) {
            $fields = array_keys($ex->errors());
            // dump($ex->errors());
            foreach ($fields as $field) {
                $fieldName = str_replace('_', ' ', $field);
                $fieldStringAttribute = "validation.attributes.{$field}";
                $langGet = Lang::get($fieldStringAttribute);
                if($langGet !== $fieldStringAttribute){
                    $fieldName = $langGet;
                }
                $this->assertTrue(
                    in_array(
                        Lang::get("validation.{$rule}",['attribute' => $fieldName] + $ruleParams),
                        Arr::get($ex->errors(), $field)
                    )
                );
            }
        }finally{
            $this->assertTrue(isset($ex));
        }
    }

    protected function assertCustomInvalidation(
        array $data,
        array $errorAndMensage = [],
        string $method = 'post',
        array $query = []
    )
    {
        try {
            $this->instanceRequest($data,$method,$query);

        } catch (ValidationException $th) {
            $fieldsError = $th->errors();
            foreach ($errorAndMensage as $field => $message) {
                $this->assertTrue(
                    in_array(
                        $message,
                        Arr::get($fieldsError,$field)
                    )
                );
            }
        }finally{
            $this->assertTrue(isset($th));
        }
        
    }
}