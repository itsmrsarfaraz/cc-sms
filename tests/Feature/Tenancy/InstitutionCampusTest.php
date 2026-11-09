<?php

namespace Tests\Feature\Tenancy;

use App\Enums\CampusStatus;
use App\Enums\InstitutionStatus;
use App\Models\Campus;
use App\Models\Institution;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionCampusTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_institution_has_many_campuses(): void
    {
        $institution = Institution::factory()->create();
        Campus::factory()->count(2)->for($institution)->create();

        $this->assertCount(2, $institution->campuses);
        $this->assertTrue($institution->campuses->first()->institution->is($institution));
    }

    public function test_status_columns_are_cast_to_enums(): void
    {
        $institution = Institution::factory()->trial()->create();
        $campus = Campus::factory()->for($institution)->create();

        $this->assertSame(InstitutionStatus::Trial, $institution->fresh()->status);
        $this->assertSame(CampusStatus::Active, $campus->fresh()->status);
    }

    public function test_institution_slugs_must_be_unique(): void
    {
        Institution::factory()->create(['slug' => 'city-grammar']);

        $this->expectException(QueryException::class);

        Institution::factory()->create(['slug' => 'city-grammar']);
    }

    public function test_campus_codes_are_unique_within_an_institution_only(): void
    {
        $first = Institution::factory()->create();
        $second = Institution::factory()->create();

        Campus::factory()->for($first)->create(['code' => 'MAIN']);
        // The same code in a different institution is allowed.
        Campus::factory()->for($second)->create(['code' => 'MAIN']);

        $this->assertSame(2, Campus::where('code', 'MAIN')->count());

        $this->expectException(QueryException::class);

        // The same code inside the same institution is rejected.
        Campus::factory()->for($first)->create(['code' => 'MAIN']);
    }

    public function test_a_campus_cannot_reference_a_missing_institution(): void
    {
        $this->expectException(QueryException::class);

        Campus::factory()->create(['institution_id' => 999999]);
    }

    public function test_an_institution_with_campuses_cannot_be_deleted(): void
    {
        $institution = Institution::factory()->create();
        Campus::factory()->for($institution)->create();

        $this->expectException(QueryException::class);

        $institution->delete();
    }
}