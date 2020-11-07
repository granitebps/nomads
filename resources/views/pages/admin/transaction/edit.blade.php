@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Transaksi</h1>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('transaction.update', $item->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="transaction_status">Status</label>
                    <select name="transaction_status" id="transaction_status" class="form-control" required>
                        <option {{ $item->transaction_status === 'IN_CART' ? 'selected' : '' }} value="IN_CART">In Cart
                        </option>
                        <option {{ $item->transaction_status === 'PENDING' ? 'selected' : '' }} value="PENDING">Pending
                        </option>
                        <option {{ $item->transaction_status === 'SUCCESS' ? 'selected' : '' }} value="SUCCESS">Success
                        </option>
                        <option {{ $item->transaction_status === 'CANCEL' ? 'selected' : '' }} value="CANCEL">Cancel
                        </option>
                        <option {{ $item->transaction_status === 'FAILED' ? 'selected' : '' }} value="FAILED">Failed
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Ubah</button>
            </form>
        </div>
    </div>

</div>
@endsection