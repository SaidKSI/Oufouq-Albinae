@extends('layouts.app')
@section('title', 'setting')
@section('content')
<x-Breadcrumb title="setting" />
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#about" data-bs-toggle="tab" aria-expanded="false"
                            class="nav-link rounded-start rounded-0 active">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#capital" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Capital
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#backup" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                            Backup
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="about">
                        <form class="needs-validation" action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $setting->name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $setting->email }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone 1</label>
                                        <input type="text" class="form-control" name="phone1" value="{{ $setting->phone1 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Phone 2</label>
                                        <input type="text" class="form-control" name="phone2" value="{{ $setting->phone2 }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Fax</label>
                                        <input type="text" class="form-control" name="fax" value="{{ $setting->fax }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address" value="{{ $setting->address }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" value="{{ $setting->city }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" class="form-control" name="state" value="{{ $setting->state }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Zip</label>
                                        <input type="text" class="form-control" name="zip" value="{{ $setting->zip }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control" name="country" value="{{ $setting->country }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">IF</label>
                                        <input type="text" class="form-control" name="if" value="{{ $setting->if }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">ICE</label>
                                        <input type="text" class="form-control" name="ice" value="{{ $setting->ice }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">RC</label>
                                        <input type="text" class="form-control" name="rc" value="{{ $setting->rc }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">CNSS</label>
                                        <input type="text" class="form-control" name="cnss" value="{{ $setting->cnss }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Patente</label>
                                        <input type="text" class="form-control" name="patente" value="{{ $setting->patente }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Capital</label>
                                        <input type="number" step="0.01" class="form-control" name="capital" value="{{ $setting->capital }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Website</label>
                                        <input type="url" class="form-control" name="website" value="{{ $setting->website }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Footer Text</label>
                                        <textarea class="form-control" name="footer_text" rows="2">{{ $setting->footer_text }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" name="bank_name" value="{{ $setting->bank_name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bank Account</label>
                                        <input type="text" class="form-control" name="bank_account" value="{{ $setting->bank_account }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bank RIB</label>
                                        <input type="text" class="form-control" name="bank_rib" value="{{ $setting->bank_rib }}">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                @if($setting->logo)
                                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="Company Logo" class="mt-2" style="max-height: 100px;">
                                @endif
                            </div>

                            <button class="btn btn-primary" type="submit">Update Settings</button>
                        </form>
                    </div>
                    <div class="tab-pane" id="capital">
                        <div class="container">
                            <h2 class="text-center">{{ $setting->capital }}</h2>
                            <h4 class="text-center">Capital Transactions <i class="ri-edit-2-fill pointer"
                                    data-bs-toggle="modal" data-bs-target="#changeModal"></i></h4>

                            <!-- Deposit Modal -->
                            <div class="modal fade" id="changeModal" tabindex="-1" aria-labelledby="changeModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="changeModalLabel">Deposit/Withdraw</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('capital.transactions.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="type" class="form-label">Type</label>
                                                    <select class="form-select" id="type" name="type" required>
                                                        <option value="" disabled selected>Select type</option>
                                                        <option value="deposit">Deposit</option>
                                                        <option value="withdrawal">Withdrawal</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="amount" class="form-label">Amount</label>
                                                    <input type="number" class="form-control" id="amount" name="amount"
                                                        step="0.01" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description"
                                                        rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->description }}</td>
                                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="backup">
                        <h2 class="text-center mb-4">Database Backup</h2>

                        <div class="row justify-content-center mb-4">
                            <div class="col-md-4">
                                <form action="{{ route('backup.create') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ri-download-cloud-line me-1"></i> Create New Backup
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Filename</th>
                                        <th>Size</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                    <tr>
                                        <td>{{ $backup->filename }}</td>
                                        <td>{{ $backup->size }}</td>
                                        <td>{{ $backup->created_at }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="{{ route('backup.download', $backup->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info me-2">
                                                        <i class="ri-download-line"></i> Download
                                                    </button>
                                                </form>
                                                <form action="{{ route('backup.restore', $backup->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning me-2"
                                                        onclick="return confirm('Are you sure you want to restore this backup? Current data will be replaced.')">
                                                        <i class="ri-refresh-line"></i> Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('backup.destroy', $backup->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this backup?')">
                                                        <i class="ri-delete-bin-line"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if there's a hash in the URL
        var hash = window.location.hash;
        if (hash) {
            var activeTab = document.querySelector('a[href="' + hash + '"]');
            if (activeTab) {
                var tab = new bootstrap.Tab(activeTab);
                tab.show();
            }
        }

        // Add event listener to each tab to update the URL hash
        var tabLinks = document.querySelectorAll('a[data-bs-toggle="tab"]');
        tabLinks.forEach(function (tabLink) {
            tabLink.addEventListener('shown.bs.tab', function (event) {
                history.pushState(null, null, event.target.getAttribute('href'));
            });
        });
    });
</script>
@endpush