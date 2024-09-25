<?php

namespace App\Orchid\Layouts\Workflow;

use App\Models\Company;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;

class WorkflowEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('workflow.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),
            Input::make('workflow.channel')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Channel'))
                ->placeholder(__('channel')),
            Select::make('workflow.company_id')
                ->fromModel(Company::class, 'company_name')
                ->required()
                ->title(__('Company')),
        ];
    }
}
