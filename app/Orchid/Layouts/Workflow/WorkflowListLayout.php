<?php

namespace App\Orchid\Layouts\Workflow;

use App\Models\Workflow;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
class WorkflowListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'workflows';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('ID'))
                ->sort()
                ->cantHide()
                ->render(fn(Workflow $workflow) => $workflow->id),
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->render(fn(Workflow $workflow) => $workflow->name),
            TD::make('channel', __('Channel'))
                ->sort()
                ->cantHide()
                ->render(fn(Workflow $workflow) => $workflow->channel),
            TD::make('company_id', __('Company'))
                ->sort()
                ->cantHide()
                ->render(fn(Workflow $workflow) => $workflow->companyName->company_name),
            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),
            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                ->align(TD::ALIGN_RIGHT)
                ->sort(),
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn(Workflow $workflow) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.systems.workflows.edit', $workflow->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Are you sure delete?'))
                            ->method('remove', [
                                'id' => $workflow->id,
                            ]),
                    ])),
        ];
    }
}
