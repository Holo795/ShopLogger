@extends('admin.layouts.admin')

@section('title', trans("shoplogger::messages.product-details.title") . ' : ' . $item->name)

@section('content')
    <!-- Informations sur l'article -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ trans("shoplogger::messages.article.info") }}</h5>
        </div>
        <div class="card-body">
            <p class="card-text">{{ trans("shoplogger::messages.article.total") }} : {{ $totalQuantity }} {{ trans("shoplogger::messages.article.unit") }}</p>
            <p class="card-text">{{ trans("shoplogger::messages.article.total-users") }} : {{ count($userStats) }}</p>
        </div>

    </div>
    <!-- Liste des utilisateurs -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ trans("shoplogger::messages.table.user") }}</th>
            <th>{{ trans("shoplogger::messages.table.quantity") }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($userStats as $stat)
            <td>
                <strong>{{ $stat['user']->name }} <a title="{{ trans("shoplogger::messages.table.tooltip") }}" href="{{ route('admin.users.edit', $stat['user']->id) }}"><i class="bi bi-link"></i></a></strong>
            </td>
            <td>
                {{ $stat['quantity'] }} {{ trans("shoplogger::messages.article.unit") }}
            </td>
        @endforeach
        </tbody>
    </table>

    <!-- Lien de retour -->
    <a href="{{ route('shoplogger.admin.product-stats') }}" class="btn btn-secondary mt-4">{{ trans("shoplogger::messages.article.go-back") }}</a>
@endsection
