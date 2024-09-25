<?php

namespace App\Orchid\Screens\Company;

use App\Models\Company;
use App\Orchid\Layouts\Company\CompanyListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class CompanyListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'companies' => Company::orderBy('id', 'desc')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Copany List';
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
                ->href(route('platform.systems.companies.create')),
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
            CompanyListLayout::class,
        ];
    }
    public function remove(Request $request,Company $company)
    {
        $company->findOrFail($request->input('id'))->delete();
        Toast::info(__('Company was removed'));
        return redirect()->route('platform.systems.companies');
    }
}