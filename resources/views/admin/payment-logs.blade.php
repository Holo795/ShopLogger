@extends('admin.layouts.admin')

@section('title', 'Logs des paiements')

@section('content')
    <!-- Formulaire de filtrage -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ trans("shoplogger::messages.payment-logs.filter.title") }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('shoplogger.admin.payment-logs') }}" method="get" class="row">
                <div class="col-md-3 mb-3">
                    <label for="filter" class="form-label">{{ trans("shoplogger::messages.filter.label") }}</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>{{ trans("shoplogger::messages.filter.today") }}</option>
                        <option value="last_7_days" {{ $filter === 'last_7_days' ? 'selected' : '' }}>{{ trans("shoplogger::messages.filter.seven-days") }}</option>
                        <option value="this_month" {{ $filter === 'this_month' ? 'selected' : '' }}>{{ trans("shoplogger::messages.filter.this-month") }}</option>
                        <option value="custom_period" {{ $filter === 'custom_period' ? 'selected' : '' }}>{{ trans("shoplogger::messages.filter.personal-range") }}</option>
                    </select>
                </div>

                <!-- Période personnalisée -->
                <div class="col-md-3 mb-3" id="customPeriodFields" style="{{ $filter !== 'custom_period' ? 'display: none;' : '' }}">
                    <label for="start_date" class="form-label">{{ trans("shoplogger::messages.filter.start-date") }}</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">

                    <label for="end_date" class="form-label mt-2">{{ trans("shoplogger::messages.filter.end-date") }}</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">

                    <button type="submit" class="btn btn-primary mt-2">{{ trans("shoplogger::messages.filter.submit") }}</button>
                </div>

                <!-- Catégorie -->
                <div class="col-md-3 mb-3">
                    <label for="category_id" class="form-label">{{ trans("shoplogger::messages.payment-logs.category.name") }}</label>
                    <select name="category_id" id="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ trans("shoplogger::messages.payment-logs.category.all") }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Article -->
                <div class="col-md-3 mb-3">
                    <label for="article" class="form-label">{{ trans("shoplogger::messages.article.name") }}</label>
                    <input type="text" name="article" id="article" class="form-control" value="{{ $article }}" placeholder="{{ trans("shoplogger::messages.article.placeholder") }}">
                </div>

                <!-- Réinitialisation des filtres -->
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <a href="{{ route('shoplogger.admin.payment-logs') }}" class="btn btn-secondary">{{ trans("shoplogger::messages.filter.reset") }}</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table des paiements -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>{{ trans("shoplogger::messages.table.user") }}</th>
                <th>{{ trans("shoplogger::messages.table.articles") }}</th>
                <th>{{ trans("shoplogger::messages.table.total") }}</th>
                <th>{{ trans("shoplogger::messages.table.type") }}</th>
                <th>{{ trans("shoplogger::messages.table.status") }}</th>
                <th>{{ trans("shoplogger::messages.table.date") }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->user->name }}</td>
                    <td>
                        @foreach ($payment->items as $item)
                            <a href="{{ route('shoplogger.admin.product-details', $item->buyable_id) }}">{{ $item->name }}</a>
                            {{ $loop->last ? '' : ', ' }}
                        @endforeach
                    </td>
                    <td>{{ $payment->formatPrice() }}</td>
                    <td>{{ $payment->getTypeName() }}</td>
                    <td>
                            <span class="badge badge-{{ $payment->statusColor() }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                    </td>
                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        // Afficher/masquer les champs de la période personnalisée
        document.getElementById('filter').addEventListener('change', function() {
            var customPeriodFields = document.getElementById('customPeriodFields');
            customPeriodFields.style.display = this.value === 'custom_period' ? 'block' : 'none';
        });
    </script>
@endsection
