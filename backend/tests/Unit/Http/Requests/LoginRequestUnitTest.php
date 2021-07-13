<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\LoginRequest;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Rfc4122\VariantTrait;
use Tests\TestCase;
use Tests\Unit\Http\Requests\Traits\ValidatorTrait;

class LoginRequestUnitTest extends TestCase
{
    use ValidatorTrait;

    private LoginRequest $request;
    private Collection $data;
    protected Generator $fake;


    protected function instanceRequest(
        array $data = [],
        string $method = 'post',
        array $query = []
    ): void {

        $newData = count($data) || (count(array_keys($this->data->toArray())) === 1 && !count($data))
            ? $data
            : $this->data->toArray();
        $this->request = new LoginRequest(
            $query,
            $newData
        );
        $this->request->setMethod($method);
        $this->request->setContainer(app())
            ->setRedirector(app(Redirector::class))
            ->validateResolved();
    }

    protected function setUp(): void
    {
        parent::setUp();
        \DB::disconnect();
        $this->fake = Factory::create(
            \Config::get('app.faker_locale')
        );
        $this->data = collect([
            'email' => $this->fake->safeEmail(),
            'password' => $this->fake->password(maxLength: 8)
        ]);
    }

    public function testSuccess()
    {
        $this->assertSuccessValidator();
    }

    public function testRequiredFields()
    {
        $fields = [
            'email',
            'password'
        ];
        foreach ($fields as $field) {
            $data = [
                '',
                null,
                '   '
            ];
            foreach ($data as $value) {
                $newData = $this->data->merge([
                    $field => $value
                ])->toArray();
                $this->assertInvalidationFieldRule($newData, 'required');
            }
        }
    }

    public function testInvalidEmail()
    {
        $data = [
            'email',
            12342,
            'asd@asd123$#.com'
        ];
        foreach ($data as $value) {
            $newData = $this->data->merge([
                'email' => $value
            ])->toArray();
            $this->assertInvalidationFieldRule($newData, 'email');
        }
    }

    public function testInvalidPassword()
    {
        $data = [
            '123456789',
            '12342'
        ];
        foreach ($data as $value) {
            $newData = $this->data->merge([
                'password' => $value
            ])->toArray();
            $this->assertInvalidationFieldRule($newData, 'between.string',['min' => 6, 'max' => 8]);
        }
    }
}
