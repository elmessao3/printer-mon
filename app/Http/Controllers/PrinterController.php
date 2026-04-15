<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Services\PrinterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class PrinterController extends Controller
{
    /**
     * Display a listing of printers
     */
    public function index()
    {
        return Inertia::render('Printers/Index', [
            'printers' => Printer::all()
        ]);
    }

    /**
     * Fetch toner and drum status from printer
     */
   public function fetchPrinterStatus($ip, PrinterService $service)
{
    $data = $service->fetch($ip);

    return response()->json($data);
}

    /**
     * Show create printer form
     */
    public function create()
    {
        return Inertia::render('Printers/Create');
    }

    /**
     * Store new printer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ipv4',
            'serial_number' => 'nullable|string|max:100|unique:printers,serial_number',
            'model' => 'nullable|string|max:255',
            'site' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'snmp_community' => 'required|string|max:255',
            'supplier_email' => 'nullable|email|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request
                ->file('image')
                ->store('printers', 'public');
        }

        $validated['status'] = 'unknown';

        Printer::create($validated);

        return Redirect::route('printers.index')
            ->with('success', 'Printer added successfully.');
    }

    /**
     * Show single printer
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