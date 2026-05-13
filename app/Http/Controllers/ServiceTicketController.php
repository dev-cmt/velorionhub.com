<?php

namespace App\Http\Controllers;

use App\Models\ServiceTicket;
use App\Models\User;
use App\Models\Product;
use App\Models\ServiceTicketProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceTicketController extends Controller
{
    public function index()
    {
        $tickets = ServiceTicket::with(['assignedEmployee', 'products'])
            ->latest()
            ->paginate(20);
        $employees = User::where('status', true)->get();
        $products = Product::active()->get();
        
        return view('backend.service-tickets.index', compact('tickets', 'employees', 'products'));
    }

    public function create()
    {
        return redirect()->route('service-tickets.index')->with('open_create_modal', true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $serviceTicket = ServiceTicket::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'customer_notes' => $validated['customer_notes'],
                'status' => 'requested',
                'requested_by' => Auth::user()->id,
            ]);

            DB::commit();
            
            return redirect()->route('service-tickets.index')
                ->with('success', 'Service ticket created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create service ticket: ' . $e->getMessage());
        }
    }

    public function show(ServiceTicket $serviceTicket, Request $request)
    {
        $serviceTicket->load(['assignedEmployee', 'products.product', 'createdBy', 'approvedBy']);
        
        if ($request->ajax()) {
            return response()->json($serviceTicket);
        }

        $employees = User::where('status', true)->get();
        $products = Product::active()->get();
        
        return view('backend.service-tickets.show', compact('serviceTicket', 'employees', 'products'));
    }

    public function edit(ServiceTicket $serviceTicket)
    {
        if ($serviceTicket->status !== 'requested') {
            return redirect()->route('service-tickets.show', $serviceTicket)
                ->with('error', 'Cannot edit request in current status.');
        }
        
        return view('backend.service-tickets.edit', compact('serviceTicket'));
    }

    public function update(Request $request, ServiceTicket $serviceTicket)
    {
        if ($serviceTicket->status !== 'requested') {
            return back()->with('error', 'Cannot edit request in current status.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_notes' => 'nullable|string',
        ]);

        $serviceTicket->update($validated);
        
        return redirect()->route('service-tickets.index')
            ->with('success', 'Service ticket updated successfully.');
    }

    public function destroy(ServiceTicket $serviceTicket)
    {
        if (!in_array($serviceTicket->status, ['requested', 'rejected'])) {
            return back()->with('error', 'Cannot delete request in current status.');
        }
        
        $serviceTicket->delete();
        
        return redirect()->route('service-tickets.index')
            ->with('success', 'Service ticket deleted successfully.');
    }

    // ======================================
    // INSPECTION ASSIGNMENT METHODS
    // ======================================
    
    public function assignInspectionForm(ServiceTicket $serviceTicket)
    {
        if ($serviceTicket->status !== 'requested') {
            return redirect()->route('service-tickets.show', $serviceTicket)
                ->with('error', 'Request already assigned or processed.');
        }
        
        $employees = User::where('status', true)->get();
        
        return view('backend.service-tickets.assign-inspection', compact('serviceTicket', 'employees'));
    }

    public function assignInspection(Request $request, ServiceTicket $serviceTicket)
    {
        if ($serviceTicket->status !== 'requested') {
            return back()->with('error', 'Request is already assigned or in a different status.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'visit_time' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:1000',
            'estimated_hours' => 'nullable|numeric|min:0.5|max:8',
            'required_tools' => 'nullable|array',
            'required_tools.*' => 'string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // Prepare assignment data
            $assignmentData = [
                'assigned_to' => $validated['employee_id'],
                'visit_date' => $validated['visit_date'],
                'priority' => $validated['priority'] ?? 'medium',
                'visit_time' => $validated['visit_time'] ?? null,
                'estimated_hours' => $validated['estimated_hours'] ?? null,
                'required_tools' => $validated['required_tools'] ?? null,
                'status' => 'assigned',
                'assignment_notes' => $validated['notes'] ?? null,
            ];

            // Update service ticket
            $serviceTicket->update($assignmentData);

            DB::commit();
            
            return redirect()->route('service-tickets.index')->with('success', 'Inspection assigned successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign inspection. Please try again.')->withInput();
        }
    }

    // ======================================
    // INSPECTION REPORT METHODS
    // ======================================
    
    /**
     * Show inspection report form
     */
    public function inspectionReportForm(ServiceTicket $serviceTicket)
    {
        if (!in_array($serviceTicket->status, ['assigned', 'inspected'])) {
            return redirect()->route('service-tickets.show', $serviceTicket)
                ->with('error', 'Cannot add inspection report in current status.');
        }
        
        $products = Product::active()->get();
        
        return view('backend.service-tickets.inspection-report', compact('serviceTicket', 'products'));
    }

    public function saveInspectionReport(Request $request, ServiceTicket $serviceTicket)
    {
        if (!in_array($serviceTicket->status, ['assigned', 'inspected'])) {
            return back()->with('error', 'Cannot add inspection report in current status.');
        }

        $validated = $request->validate([
            'technician_notes' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Clear existing products
            ServiceTicketProduct::where('service_ticket_id', $serviceTicket->id)->delete();
            
            // Add new products
            foreach ($validated['products'] as $productData) {
                ServiceTicketProduct::create([
                    'service_ticket_id' => $serviceTicket->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'notes' => $productData['notes'] ?? null,
                ]);
            }
            
            // Update service ticket
            $serviceTicket->update([
                'technician_notes' => $validated['technician_notes'],
                'status' => 'inspected',
            ]);

            DB::commit();
            
            return redirect()->route('service-tickets.index')
                ->with('success', 'Inspection report saved successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save inspection report: ' . $e->getMessage());
        }
    }

    // ======================================
    // APPROVAL METHODS
    // ======================================
    
    public function approvalForm(ServiceTicket $serviceTicket)
    {
        if ($serviceTicket->status !== 'inspected') {
            return redirect()->route('service-tickets.show', $serviceTicket)
                ->with('error', 'Cannot approve/reject in current status.');
        }
        
        $serviceTicket->load('products.product');
        
        return view('backend.service-tickets.approval', compact('serviceTicket'));
    }

    public function processApproval(Request $request, ServiceTicket $serviceTicket)
    {
        if ($serviceTicket->status !== 'inspected') {
            return back()->with('error', 'Cannot approve/reject in current status.');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string',
        ]);

        $status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        
        $serviceTicket->update([
            'status' => $status,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'approved_by' => Auth::user()->id,
            'approved_at' => now(),
        ]);

        return redirect()->route('service-tickets.index')
            ->with('success', "Service ticket {$validated['action']}d successfully.");
    }

    // ======================================
    // STATUS UPDATE METHOD
    // ======================================
    
    public function updateStatus(Request $request, ServiceTicket $serviceTicket)
    {
        $validated = $request->validate([
            'status' => 'required|in:assigned,inspected,approved,completed,cancelled',
        ]);

        $allowedTransitions = [
            'assigned' => ['inspected', 'cancelled'],
            'inspected' => ['approved', 'rejected'],
            'approved' => ['completed', 'cancelled'],
        ];

        if (isset($allowedTransitions[$serviceTicket->status]) && 
            !in_array($validated['status'], $allowedTransitions[$serviceTicket->status])) {
            return back()->with('error', 'Invalid status transition.');
        }

        $serviceTicket->update(['status' => $validated['status']]);
        
        return back()->with('success', 'Status updated successfully.');
    }
}