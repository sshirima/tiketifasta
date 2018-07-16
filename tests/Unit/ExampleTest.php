<?php

namespace Tests\Unit;

use App\Jobs\Schedules\AssignSchedule;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testingAssignScheduleRoute()
    {
        $response = $this->call('GET', 'merchant/bus/1/schedules/assign',['user' => 1]);
    }
}
