<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Services\GoyzerService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

    protected function afterCreate(): void
    {
        $data = [
            'TitleID' => $this->record->title_id ?? '',
            'FirstName' => $this->record->first_name,
            'FamilyName' => $this->record->family_name,
            'MobileCountryCode' => $this->record->mobile_country_code ?? '',
            'MobileAreaCode' => $this->record->mobile_area_code ?? '',
            'MobilePhone' => $this->record->mobile_phone ?? '',
            'TelephoneCountryCode' => $this->record->telephone_country_code ?? '',
            'TelephoneAreaCode' => $this->record->telephone_area_code ?? '',
            'Telephone' => $this->record->telephone ?? '',
            'Email' => $this->record->email ?? '',
            'NationalityID' => $this->record->nationality_id ?? '',
            'CompanyID' => $this->record->company_id ?? '',
            'Remarks' => $this->record->remarks ?? '',
            'RequirementType' => $this->record->requirement_type ?? '',
            'ContactType' => $this->record->contact_type ?? '',
            'CountryID' => $this->record->country_id ?? '',
            'StateID' => $this->record->state_id ?? '',
            'CityID' => $this->record->city_id ?? '',
            'DistrictID' => $this->record->district_id ?? '',
            'CommunityID' => $this->record->community_id ?? '',
            'SubCommunityID' => $this->record->sub_community_id ?? '',
            'PropertyID' => $this->record->property_id ?? '',
            'UnitType' => $this->record->unit_type ?? '',
            'MethodOfContact' => $this->record->method_of_contact ?? '',
            'MediaType' => $this->record->media_type ?? '',
            'MediaName' => $this->record->media_name ?? '',
            'ReferredByID' => $this->record->referred_by_id ?? '',
            'ReferredToID' => $this->record->referred_to_id ?? '',
            'DeactivateNotification' => $this->record->deactivate_notification ?? '',
            'Bedroom' => $this->record->bedroom ?? '',
            'Budget' => $this->record->budget ?? '',
            'Budget2' => $this->record->budget2 ?? '',
            'RequirementCountryID' => $this->record->requirement_country_id ?? '',
            'ExistingClient' => $this->record->existing_client ?? '',
            'CompaignSource' => $this->record->compaign_source ?? '',
            'CompaignMedium' => $this->record->compaign_medium ?? '',
            'Company' => $this->record->company ?? '',
            'NumberOfEmployee' => $this->record->number_of_employee ?? '',
            'LeadStageId' => $this->record->lead_stage_id ?? '',
            'ActivityDate' => $this->record->activity_date ?? '',
            'ActivityTime' => $this->record->activity_time ?? '',
            'ActivityTypeId' => $this->record->activity_type_id ?? '',
            'ActivitySubject' => $this->record->activity_subject ?? '',
            'ActivityRemarks' => $this->record->activity_remarks ?? '',
        ];

        $response = app(GoyzerService::class)->insertContact($data);
        
        if ($response && isset($response['ContactInsertData'])) {
            $this->record->update(['goyzer_response' => json_encode($response)]);
            
            Notification::make()
                ->title('Contact synced to Goyzer')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Failed to sync to Goyzer')
                ->warning()
                ->send();
        }
    }
}
