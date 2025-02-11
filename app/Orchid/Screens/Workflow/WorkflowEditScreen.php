<?php

namespace App\Orchid\Screens\Workflow;

use App\Events\WorkflowSave;
use App\Models\Workflow;
use App\Orchid\Layouts\Workflow\WorkflowEditLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $workflow;
    public function query(Workflow $workflow): iterable
    {
        return [
            'workflow' => $workflow,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->workflow->exists ? 'Edit Workflow' : 'Create Workflow';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->method('remove')
                ->canSee($this->workflow->exists),
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
            Layout::block([
                WorkflowEditLayout::class,
            ])
                ->title('Workflow')
                ->description('Edit Workflow'),
        ];
    }
    public function save(Request $request, Workflow $workflow)
    {
        DB::beginTransaction();
        try {
            $new = is_null($workflow->id);
            $data = $request->get('workflow');
            if ($new) {
                $workflow->forceFill($data)->save();
                event(new WorkflowSave($workflow));
            } else {
                if($workflow->channel == $data['channel']) {
                    $workflow->forceFill($data)->save();
                } else {
                    $workflow->forceFill($data)->save();
                    event(new WorkflowSave($workflow));
                }
            }
            DB::commit();
            Toast::info(__('Workflow was saved'));

        } catch (\Exception $e) {
            DB::rollBack();
            logger('Có lỗi xảy ra: ' . $e->getMessage());
            Toast::error(__('Channel đã tồn tại. Vui lòng nhập lại Channel khác'));
            return back()->withInput();
        }
        return redirect()->route('platform.systems.workflows');
    }

    public function remove(Workflow $workflow)
    {
        $workflow->delete();
        Toast::info(__('Workflow was removed'));
        return redirect()->route('platform.systems.workflows');
    }
}
