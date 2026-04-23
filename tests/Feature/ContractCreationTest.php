<?php

namespace Tests\Feature;

use App\Models\AvailabilityBlock;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\States\ContractStatus;
use App\Models\States\PaymentStatus;
use App\Models\States\PricingType;
use App\Models\States\ViewingRequestStatus;
use App\Models\ViewingRequest;
use App\Nova\Actions\CreateContractFromBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Nova\Fields\ActionFields;
use Tests\TestCase;

class ContractCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_auto_populates_landlord_id_from_property(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);

        $contract = Contract::create([
            'property_id'  => $property->id,
            'renter_name'  => 'John Doe',
            'start_date'   => now()->addDays(1),
            'end_date'     => now()->addMonths(3),
            'pricing_type' => PricingType::MONTHLY->value,
            'monthly_rent' => 3000,
            'total_value'  => 9000,
            'currency'     => 'USD',
        ]);

        $this->assertEquals($landlord->id, $contract->fresh()->landlord_id);
    }

    public function test_contract_creation_does_not_override_explicit_landlord_id(): void
    {
        $landlord1 = Landlord::factory()->create();
        $landlord2 = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord1->id]);

        $contract = Contract::create([
            'property_id'  => $property->id,
            'landlord_id'  => $landlord2->id,
            'renter_name'  => 'Jane Doe',
            'start_date'   => now()->addDays(1),
            'end_date'     => now()->addMonths(3),
            'pricing_type' => PricingType::MONTHLY->value,
            'monthly_rent' => 3000,
            'total_value'  => 9000,
            'currency'     => 'USD',
        ]);

        $this->assertEquals($landlord2->id, $contract->fresh()->landlord_id);
    }

    public function test_contract_creates_availability_block_with_numeric_reference(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);

        $contract = Contract::create([
            'property_id'  => $property->id,
            'renter_name'  => 'John Doe',
            'start_date'   => now()->addDays(1),
            'end_date'     => now()->addMonths(3),
            'pricing_type' => PricingType::MONTHLY->value,
            'monthly_rent' => 3000,
            'total_value'  => 9000,
            'currency'     => 'USD',
        ]);

        $block = AvailabilityBlock::where('property_id', $property->id)->first();

        $this->assertNotNull($block);
        $this->assertEquals((string) $contract->id, $block->contract_reference);
    }

    public function test_availability_blocks_relationship_returns_correct_blocks(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);

        $contract = Contract::create([
            'property_id'  => $property->id,
            'renter_name'  => 'John Doe',
            'start_date'   => now()->addDays(1),
            'end_date'     => now()->addMonths(3),
            'pricing_type' => PricingType::MONTHLY->value,
            'monthly_rent' => 3000,
            'total_value'  => 9000,
            'currency'     => 'USD',
        ]);

        $this->assertCount(1, $contract->availabilityBlocks);
    }

    public function test_free_availability_deletes_associated_blocks(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);

        $contract = Contract::create([
            'property_id'  => $property->id,
            'renter_name'  => 'John Doe',
            'start_date'   => now()->addDays(1),
            'end_date'     => now()->addMonths(3),
            'pricing_type' => PricingType::MONTHLY->value,
            'monthly_rent' => 3000,
            'total_value'  => 9000,
            'currency'     => 'USD',
        ]);

        $this->assertCount(1, $contract->availabilityBlocks);

        $contract->freeAvailability();

        $this->assertCount(0, $contract->fresh()->availabilityBlocks);
    }

    public function test_create_contract_from_booking_action_creates_contract(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create([
            'landlord_id'  => $landlord->id,
            'pricing_type' => PricingType::MONTHLY->value,
            'monthly_rent' => 5000,
            'currency'     => 'SAR',
        ]);

        $viewingRequest = ViewingRequest::factory()->create([
            'property_id'    => $property->id,
            'renter_name'    => 'Ahmed Al-Rashid',
            'renter_email'   => 'ahmed@example.com',
            'renter_phone'   => '+966501234567',
            'preferred_date' => now()->addWeek()->toDateString(),
            'status'         => ViewingRequestStatus::CONTACTED->value,
        ]);

        $fields = new ActionFields(collect([
            'renter_name'      => $viewingRequest->renter_name,
            'renter_email'     => $viewingRequest->renter_email,
            'renter_phone'     => $viewingRequest->renter_phone,
            'renter_company'   => null,
            'start_date'       => now()->addWeek()->toDateString(),
            'end_date'         => now()->addMonths(12)->toDateString(),
            'pricing_type'     => PricingType::MONTHLY->value,
            'currency'         => 'SAR',
            'monthly_rent'     => 5000,
            'weekly_rent'      => null,
            'yearly_rent'      => null,
            'daily_rent'       => null,
            'security_deposit' => 10000,
            'total_value'      => 60000,
            'notes_internal'   => null,
        ]), collect());

        $action = new CreateContractFromBooking;
        $action->handle($fields, collect([$viewingRequest]));

        $contract = Contract::latest()->first();

        $this->assertNotNull($contract);
        $this->assertEquals($property->id, $contract->property_id);
        $this->assertEquals($landlord->id, $contract->landlord_id);
        $this->assertEquals('Ahmed Al-Rashid', $contract->renter_name);
        $this->assertEquals('ahmed@example.com', $contract->renter_email);
        $this->assertEquals('+966501234567', $contract->renter_phone);
        $this->assertEquals('SAR', $contract->currency);
        $this->assertEquals(ContractStatus::ACTIVE, $contract->contract_status);
        $this->assertEquals(PaymentStatus::NOT_COLLECTED, $contract->payment_status);
    }

    public function test_create_contract_from_booking_action_closes_viewing_request(): void
    {
        $landlord = Landlord::factory()->create();
        $property = Property::factory()->create(['landlord_id' => $landlord->id]);
        $viewingRequest = ViewingRequest::factory()->create([
            'property_id'    => $property->id,
            'status'         => ViewingRequestStatus::CONTACTED->value,
            'preferred_date' => null,
        ]);

        $fields = new ActionFields(collect([
            'renter_name'      => $viewingRequest->renter_name,
            'renter_email'     => $viewingRequest->renter_email,
            'renter_phone'     => $viewingRequest->renter_phone,
            'renter_company'   => null,
            'start_date'       => now()->addDays(1)->toDateString(),
            'end_date'         => now()->addMonths(6)->toDateString(),
            'pricing_type'     => PricingType::MONTHLY->value,
            'currency'         => 'USD',
            'monthly_rent'     => 3000,
            'weekly_rent'      => null,
            'yearly_rent'      => null,
            'daily_rent'       => null,
            'security_deposit' => null,
            'total_value'      => 18000,
            'notes_internal'   => null,
        ]), collect());

        $action = new CreateContractFromBooking;
        $action->handle($fields, collect([$viewingRequest]));

        $this->assertEquals(
            ViewingRequestStatus::CLOSED,
            $viewingRequest->fresh()->status
        );
    }
}
