<?php
declare(strict_types = 1);

namespace Tests\Unit\Http\Requests\Traits;

use Arr;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

trait ValidatorTrait
{

    protected abstract function requesClass(): string;

    protected function instanceRequest(
        array $data = [],
        string $method = 'post',
        array $query = []
        )
    {
        $requestClass = $this->requesClass();
        
        $newData = count($data) || (count(array_keys($this->data->toArray())) === 1 && !count($data))
            ? $data 
            : $this->data->toArray();
        $this->request = new $requestClass($query, $newData);
        $this->request->setMethod($method);
        $this->request->setContainer(app())
                ->setRedirector(app(Redirector::class))
                ->validateResolved();
    }

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
}