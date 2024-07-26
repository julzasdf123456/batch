<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScholarshipsRequest;
use App\Http\Requests\UpdateScholarshipsRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ScholarshipsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;

class ScholarshipsController extends AppBaseController
{
    /** @var ScholarshipsRepository $scholarshipsRepository*/
    private $scholarshipsRepository;

    public function __construct(ScholarshipsRepository $scholarshipsRepo)
    {
        $this->middleware('auth');
        $this->scholarshipsRepository = $scholarshipsRepo;
    }

    /**
     * Display a listing of the Scholarships.
     */
    public function index(Request $request)
    {
        $scholarships = $this->scholarshipsRepository->paginate(10);

        return view('scholarships.index')
            ->with('scholarships', $scholarships);
    }

    /**
     * Show the form for creating a new Scholarships.
     */
    public function create()
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'create scholarship'])) {
            return view('scholarships.create');
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Store a newly created Scholarships in storage.
     */
    public function store(CreateScholarshipsRequest $request)
    {
        $input = $request->all();

        $scholarships = $this->scholarshipsRepository->create($input);

        Flash::success('Scholarships saved successfully.');

        return redirect(route('scholarships.index'));
    }

    /**
     * Display the specified Scholarships.
     */
    public function show($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'view scholarship'])) {
            $scholarships = $this->scholarshipsRepository->find($id);

            if (empty($scholarships)) {
                Flash::error('Scholarships not found');

                return redirect(route('scholarships.index'));
            }

            return view('scholarships.show')->with('scholarships', $scholarships);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Show the form for editing the specified Scholarships.
     */
    public function edit($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'edit scholarship'])) {
            $scholarships = $this->scholarshipsRepository->find($id);

            if (empty($scholarships)) {
                Flash::error('Scholarships not found');

                return redirect(route('scholarships.index'));
            }

            return view('scholarships.edit')->with('scholarships', $scholarships);
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }

    /**
     * Update the specified Scholarships in storage.
     */
    public function update($id, UpdateScholarshipsRequest $request)
    {
        $scholarships = $this->scholarshipsRepository->find($id);

        if (empty($scholarships)) {
            Flash::error('Scholarships not found');

            return redirect(route('scholarships.index'));
        }

        $scholarships = $this->scholarshipsRepository->update($request->all(), $id);

        Flash::success('Scholarships updated successfully.');

        return redirect(route('scholarships.index'));
    }

    /**
     * Remove the specified Scholarships from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Auth::user()->hasAnyPermission(['god permission', 'delete scholarship'])) {
            $scholarships = $this->scholarshipsRepository->find($id);

            if (empty($scholarships)) {
                Flash::error('Scholarships not found');

                return redirect(route('scholarships.index'));
            }

            $this->scholarshipsRepository->delete($id);

            Flash::success('Scholarships deleted successfully.');

            return redirect(route('scholarships.index'));
        } else {
            return redirect(route('errorMessages.error-with-back', ['Not Allowed', 'You are not allowed to access this module.', 403]));
        }
    }
}
