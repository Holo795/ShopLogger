@extends('admin.layouts.admin')

@section('title', trans("shoplogger::messages.product-stats.title"))

@section('content')
    <!-- Formulaire de filtrage -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ trans("shoplogger::messages.product-stats.filter.title") }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('shoplogger.admin.product-stats') }}" method="get" class="row">
                <!-- Filtrer par date -->
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

                <!-- Réinitialisation des filtres -->
                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <a href="{{ route('shoplogger.admin.product-stats') }}" class="btn btn-secondary">{{ trans("shoplogger::messages.filter.reset") }}</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Grille des articles -->
    <div class="row">
        @foreach($items as $item)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ trans("shoplogger::messages.article.total") }} : {{ $item->total_sold }} {{ trans("shoplogger::messages.article.unit") }}</p>
                        <a href="{{ route('shoplogger.admin.product-details', $item->buyable_id) }}" class="btn btn-primary">{{ trans("shoplogger::messages.article.see-buyers") }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $items->links() }}
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
