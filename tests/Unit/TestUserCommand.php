<?php

namespace Tests\Unit;

use App\Classes\PhoenixPanel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TestUserCommand extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @dataProvider invalidPteroIdDataProvider
     *
     * @param  array  $apiResponse
     * @param  int  $expectedExitCode
     * @return void
     */
    public function testMakeUserCommand(array $apiResponse, int $expectedExitCode): void
    {
        $phoenixpanel = $this->getMockBuilder(PhoenixPanel::class)->getMock();
        $phoenixpanel->expects(self::once())->method('getUser')->willReturn($apiResponse);

        $this->app->instance(PhoenixPanel::class, $phoenixpanel);

        $this->artisan('make:user')
            ->expectsQuestion('Please specify your PhoenixPanel ID.', 0)
            ->expectsQuestion('Please specify your password.', 'password')
            ->assertExitCode($expectedExitCode);
    }

    public function invalidPteroIdDataProvider(): array
    {
        return [
            'Good Response' => [
                'apiResponse' => [
                    'id' => 12345,
                    'first_name' => 'Test',
                    'email' => 'test@test.test',
                ],
                'expectedExitCode' => 1,
            ],
            'Bad Response' => [
                'apiResponse' => [],
                'expectedExitCode' => 0,
            ],
        ];
    }
}
