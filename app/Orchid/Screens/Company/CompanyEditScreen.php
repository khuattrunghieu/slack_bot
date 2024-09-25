<?php

namespace App\Orchid\Screens\Company;

use App\Models\Company;
use App\Orchid\Layouts\Company\CompanyEditLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;


class CompanyEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public $company;
    public function query(Company $company): iterable
    {
        return [
            'company' => $company,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Company Edit';
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
                ->canSee($this->company->exists),
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
                CompanyEditLayout::class,
            ])
                ->title('Company')
                ->description('Edit company'),
        ];
    }

    public function save(Request $request, Company $company)
    {
        // dd( $company->forceFill($request->get('company')));
        $company->forceFill($request->get('company'))->save();
        Toast::info(__('Company was saved'));
        return redirect()->route('platform.systems.companies');
    }

    public function remove(Company $company)
    {
        $company->delete();
        Toast::info(__('Company was removed'));
        return redirect()->route('platform.systems.companies');
    }
}
