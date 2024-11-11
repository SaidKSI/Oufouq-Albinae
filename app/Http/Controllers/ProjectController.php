<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Employer;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Facture;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use NumberToWords\NumberToWords;
class ProjectController extends Controller
{
    function index()
    {
        $projects = Project::all();
        $projectCount = $projects->count();
        $client = Client::all();
        return view('project.index', ['projects' => $projects, 'projectCount' => $projectCount, 'clients' => $client]);
    }

    function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }
        $ref = 'PRJ-' . rand(1000, 9999);

        $project = new Project();
        $project->name = $request->name;
        $project->client_id = $request->client_id;
        $project->address = $request->address;
        $project->city = $request->city;
        $project->description = $request->description;
        $project->ref = $ref;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->save();

        return redirect()->back()->with('success', 'Project added successfully.');

    }

    function update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|string',
            'progress_percentage' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $project = Project::find($request->id);
        $project->name = $request->name;
        $project->client_id = $request->client_id;
        $project->address = $request->address;
        $project->city = $request->city;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->progress_percentage = $request->progress_percentage;
        $project->save();


        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted successfully.');
    }

    function show($id)
    {
        $project = Project::find($id);
        $expenses = Expense::select(
            'ref',
            DB::raw('MAX(id) as id'),
            DB::raw('MAX(name) as name'),
            DB::raw('MAX(total_amount) as total_amount'),
            DB::raw('MAX(type) as type'),
            DB::raw('MAX(amount) as amount'),
            DB::raw('MAX(description) as description'),
            DB::raw('MAX(start_date) as start_date'),
            DB::raw('MAX(repeat_interval) as repeat_interval')
        )
            ->groupBy('ref')
            ->where('project_id', $id)
            ->get();
        $projects = Project::all();
        $employers = Employer::all();
        $supplier = Supplier::all();
        return view('project.show', ['project' => $project, 'expenses' => $expenses, 'projects' => $projects, 'employers' => $employers, 'suppliers' => $supplier]);
    }


    function estimate()
    {
        $projects = Project::whereDoesntHave('estimates', function ($query) {
            $query->where('type', 'estimate');
        })->get();
        $clients = Client::all();
        $estimates = Estimate::all();
        return view('estimate.index', compact('projects', 'clients', 'estimates'));
    }
    public function numberToFrenchWords($number)
    {
        $number = (int) round(floatval($number)); // Convert to float, round the number, and then cast to int
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('fr'); // 'fr' for French

        return $numberTransformer->toWords($number);
    }
    public function estimateInvoice($id)
    {
        $estimate = Estimate::findOrFail($id);
        $company = CompanySetting::first();
        $totalWithoutTax = $estimate->total_price / (1 + $estimate->tax / 100);
        $taxAmount = $estimate->total_price - $totalWithoutTax;
        $total = ($estimate->total_price * $estimate->quantity) + $taxAmount;
        $total_in_alphabetic = $this->numberToFrenchWords($total);
        return view('project.invoice', [
            'estimate' => $estimate,
            'company' => $company,
            'total' => $total,
            'taxAmount' => $taxAmount,
            'totalWithoutTax' => $totalWithoutTax,
            'total_in_alphabetic' => $total_in_alphabetic

        ]);
    }
    function paymentEstimateInvoice($id)
    {
        $estimate = Estimate::findOrFail($id);
        $company = CompanySetting::first();
        $totalWithoutTax = $estimate->total_price / (1 + $estimate->tax / 100);
        $taxAmount = $estimate->total_price - $totalWithoutTax;
        $total = ($estimate->total_price * $estimate->quantity) + $taxAmount;

        $total_in_alphabetic = $this->numberToFrenchWords($total);
        return view('project.payment-invoice', [
            'estimate' => $estimate,
            'company' => $company,
            'total' => $total,
            'taxAmount' => $taxAmount,
            'totalWithoutTax' => $totalWithoutTax,
            'total_in_alphabetic' => $total_in_alphabetic
        ]);
    }
    function destroyEstimate($id)
    {
        $estimate = Estimate::find($id);
        $estimate->delete();
        return redirect()->back()->with('success', 'Estimate deleted successfully.');
    }
    protected function handleDocumentUpload($facture, $request)
    {
        if ($request->hasFile('doc')) {
            $files = $request->file('doc');
            // Ensure $files is always an array
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if ($file->isValid()) {
                    try {
                        // Store file in public storage
                        $path = $file->store('facture_documents', 'public');

                        // Create document record
                        $facture->documents()->create([
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                            'documentable_type' => Facture::class,
                            'documentable_id' => $facture->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Document upload failed: ' . $e->getMessage());
                        throw new \Exception('Failed to upload document: ' . $e->getMessage());
                    }
                }
            }
        }
    }

    public function storeEstimateFacture(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'estimate' => 'required|exists:estimates,id',
            'number' => 'required|unique:factures,number',
            'date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cheque,credit,cash,traita',
            'transaction_id' => 'nullable|string',
            'total_without_tax' => 'required|numeric|min:0',
            'tax' => 'required',
            'total_with_tax' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'doc.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            $estimate = Estimate::findOrFail($request->estimate);

            // Create facture
            $facture = $estimate->facture()->create([
                'number' => $request->number,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'total_without_tax' => $request->total_without_tax,
                'tax' => $request->tax,
                'total_with_tax' => $request->total_with_tax,
                'note' => $request->note,
            ]);

            // Handle document uploads
            $this->handleDocumentUpload($facture, $request);

            DB::commit();
            return redirect()->route('estimate.payment')->with('success', 'Facture created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create facture: ' . $e->getMessage())->withInput();
        }
    }

    public function getProjectDetails($id)
    {
        $project = Project::findOrFail($id);
        return response()->json([
            'name' => $project->name,
            'description' => $project->description
        ]);
    }

    public function showInvoice()
    {
        $estimates = Estimate::where('type', 'estimate')->get();
        return view('project.facture', compact('estimates'));
    }
}