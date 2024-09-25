<?php

namespace App\Orchid\Screens\Workflow;

use App\Models\Workflow;
use App\Orchid\Layouts\Workflow\WorkflowListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class WorkflowListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'workflows' => Workflow::orderBy('id', 'desc')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Workflow List';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->href(route('platform.systems.workflows.create')),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            WorkflowListLayout::class,
        ];
    }
    public function remove(Request $request,Workflow $workflow)
    {
        $workflow->findOrFail($request->input('id'))->delete();
        Toast::info(__('Workflow was removed'));
        return redirect()->route('platform.systems.workflows');
    }
}
