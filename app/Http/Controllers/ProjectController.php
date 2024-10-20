<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Employer;
use App\Models\Estimate;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    function storeEstimate(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'reference' => 'required|string',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
            'tax' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }

        $estimate = new Estimate();
        $estimate->project_id = $request->project_id;
        $estimate->reference = $request->reference;
        $estimate->quantity = $request->quantity;
        $estimate->total_price = $request->total_price;
        $estimate->tax = $request->tax;
        $estimate->type = 'estimate';
        $estimate->number = rand(10000, 99999);
        $estimate->save();

        return redirect()->route('estimate.payment')->with('success', 'Project added successfully.');

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

    public function createInvoice()
    {
        $projects = Project::whereHas('estimate', function ($query) {
            $query->where('type', 'estimate');
        })->whereDoesntHave('estimate', function ($query) {
            $query->where('type', 'invoice');
        })->with('client')->get();

        return view('project.create_invoice', compact('projects'));
    }

    function storeInvoice(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'transaction_id' => 'required|string',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            toastr()->error($errorMessage);
            return back()->withErrors($validator->errors())->withInput();
        }


        $estimate = Estimate::find($request->estimate_id);
        $estimate->payment_method = $request->payment_method;
        $estimate->transaction_id = $request->transaction_id;
        $estimate->type = 'invoice';
        $estimate->note = $request->note;
        $estimate->update();

        return redirect()->back()->with('success', 'Invoice created successfully');

    }

    public function getProjectDetails($id)
    {
        $project = Project::findOrFail($id);
        return response()->json([
            'name' => $project->name,
            'description' => $project->description
        ]);
    }

    public function showInvoice($id)
    {
        $estimate = Estimate::findOrFail($id);
        $projects = Project::all(); // You might want to adjust this based on your needs

        return view('project.create_invoice', compact('estimate', 'projects'));
    }
}