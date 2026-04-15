<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrinterController extends Controller
{
    /**
     * Display printers with search + status filter
     */
    public function index(Request $request)
{
    $query = Printer::query()
        ->with(['latestStatus']);

    /* ---------------- SEARCH ---------------- */
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('ip_address', 'like', "%{$search}%")
              ->orWhere('serial_number', 'like', "%{$search}%")
              ->orWhere('site', 'like', "%{$search}%");
        });
    }

    /* ---------------- STATUS FILTER (DB ONLY) ---------------- */
    if ($request->filled('status')) {
        $query->whereHas('latestStatus', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    /* ---------------- PAGINATION ---------------- */
    $printers = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();
    

    return Inertia::render('Printers/Index', [
        'printers' => $printers,
        'filters' => $request->only(['search', 'status']),
    ]);
}
    public function show(Printer $printer)
    {
        $printer->load([
            'statusLogs' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(50);
            },
            'latestStatus'
        ]);

        return Inertia::render('Printers/Show', [
            'printer' => $printer
        ]);
    }

    public function create()
    {
        return Inertia::render('Printers/Create');
    }

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

        $validated['status'] = 'unknown';

        Printer::create($validated);

        return redirect()->route('printers.index')
            ->with('success', 'Printer added successfully.');
    }

    public function update(Request $request, Printer $printer)
    {
        $printer->update($request->all());

        return redirect()->route('printers.show', $printer->id);
    }

    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect()->route('printers.index');
    }
}