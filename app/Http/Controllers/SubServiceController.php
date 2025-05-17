<?php
namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Subservice;
use Illuminate\Http\Request;

class SubserviceController extends Controller
{
    public function create()
    {
        $services = Service::all(); // for dropdown
        return view('subservices.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
        ]);

        Subservice::create($validated);

        return redirect()->route('subservices.create')->with('success', 'Subservice created successfully.');
    }
}