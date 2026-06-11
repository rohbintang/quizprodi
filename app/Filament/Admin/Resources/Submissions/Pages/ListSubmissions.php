<?php

namespace App\Filament\Admin\Resources\Submissions\Pages;

use App\Filament\Admin\Resources\Submissions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListSubmissions extends ListRecords
{
    protected static string $resource = Submissions\SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action — submissions come from frontend quiz
        ];
    }
}
