<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PrinterController extends Controller
{
    /**
     * Display a listing of the printers
     */
    public function index()
    {
        return Inertia::render('Printers/Index', [
            'printers' => Printer::all()
        ]);
    }

    /**
     * Show the form for creating a printer
     */
    public function create()
    {
        return Inertia::render('Printers/Create');
    }

    /**
     * Store a newly created printer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
    'name' => 'required|string|max:255',
    'ip_address' => 'required|ipv4',
    'serial_number' => 'nullable|string|max:100|unique:printers,serial_number',
    'model' => 'nullable|string|max:255',
    'site' => 'string|max:255',
    'location' => 'nullable|string|max:255',
    'snmp_community' => 'required|string|max:255',
    'supplier_email' => 'nullable|email|max:255',
    'image' => 'nullable|image|max:2048',
]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request
                ->file('image')
                ->store('printers', 'public');
        }

        // Default status
        $validated['status'] = 'unknown';

        Printer::create($validated);

        return Redirect::route('printers.index')
            ->with('success', 'Printer added successfully.');
    }

    /**
     * Display a specific printer
     */
    public function show(Printer $printer)
    {
        $printer->load([
            'statusLogs' => function ($query) {
                $query->orderBy('created_at', 'desc')->take(50);
            }
        ]);

        return Inertia::render('Printers/Show', [
            'printer' => $printer
        ]);
    }
}