<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\RelatorioRequest;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\Unit\Http\Requests\Traits\ValidatorTrait;

class RelatorioRequestUnitTest extends TestCase
{
    use ValidatorTrait;

    private RelatorioRequest $request;
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
        $this->request = new RelatorioRequest(
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
        $end_date = $this->fake->date();
        $this->data = collect([
            'start_date' => $this->fake->date(max: $end_date),
            'end_date' => $end_date
        ]);
    }

    public function testSuccess()
    {
        $this->assertSuccessValidator(method: 'get', query: $this->data->toArray());
    }

    public function testFieldRequired()
    {
        $data = [
            '',
            null,
            '   '
        ];
        foreach ($data as $value) {
            $fields = ['start_date', 'end_date'];
            foreach ($fields as $field) {
                $newData = $this->data->merge([
                    $field => $value
                ])->toArray();
                $this->assertInvalidationFieldRule($newData, 'required', method: 'get');
            }
        }
    }

    public function testFieldDateFormat()
    {
        $newData = $this->data->merge([
            'start_date' => $this->fake->date('d-m-Y', $this->data->get('end_date'))
        ])->toArray();
        $this->assertInvalidationFieldRule(
            $newData,
            'date_format',
            ['format' => 'Y-m-d']
        );

        $newData = $this->data->merge([
            'end_date' => $this->fake->dateTimeBetween(
                $this->data->get('start_date')
            )->format('d-m-Y')
        ])->toArray();
        $this->assertInvalidationFieldRule(
            $newData,
            'date_format',
            ['format' => 'Y-m-d']
        );
    }

}
