@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        @include('layouts.left-menu')
        <div class="col-xs-11 col-sm-11 col-md-11 col-lg-10 col-xl-10 col-xxl-10">
            <div class="row pt-3">
                <div class="col ps-4">
                    <h1 class="display-6 mb-3"><i class="ms-auto bi bi-grid"></i> {{ __('Dashboard') }}</h1>
                    <p class="text-center mt-5" style="font-size: 40px; color: #0d6efd;">
                        Hello {{ Auth::user()->first_name }} {{ Auth::user()->last_name }},
                        <br>Welcome to SLC {{ Auth::user()->role }} Page
                    </p>
                    
                    <div class="row mt-4">
                        <div class="col-lg-6" style="width:100%">
                            <div class="card mb-3" style="width:100%">
                                <div class="card-header bg-transparent d-flex justify-content-between">
                                    <span><i class="bi bi-megaphone me-2"></i> Notices</span>
                                    {{ $notices->links() }}
                                </div>
                                <div class="card-body p-0 text-dark">
                                    <div>
                                        @isset($notices)
                                        <div class="accordion accordion-flush" id="noticeAccordion">
                                            @foreach ($notices->reverse() as $notice)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="flush-heading{{ $notice->id }}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $notice->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="flush-collapse{{ $notice->id }}">
                                                        Published at: {{ $notice->created_at }}
                                                    </button>
                                                </h2>
                                                <div id="flush-collapse{{ $notice->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="flush-heading{{ $notice->id }}" data-bs-parent="#noticeAccordion">
                                                    <div class="accordion-body overflow-auto">{!! Purify::clean($notice->notice) !!}</div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endisset
                                        @if(count($notices) < 1)
                                            <div class="p-3">No notices</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>
</div>
@endsection
