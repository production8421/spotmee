@section('page_header')
    <div class="row">
        <div class="col-sm-6">
            <h3>{{ __('Payment Management') }}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset(config('cuba.assets_path').'/svg/icon-sprite.svg') }}#stroke-home"></use>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.payment-management.user-payments.index') }}">{{ __('Payment Management') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ $breadcrumbActive }}</li>
            </ol>
        </div>
    </div>
@endsection
