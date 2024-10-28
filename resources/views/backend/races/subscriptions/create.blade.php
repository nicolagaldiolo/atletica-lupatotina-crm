@extends('backend.layouts.app')

@php
    $entity = __('Iscrizione atleti')
@endphp

@section('title') {{ $entity }} @endsection

@section('breadcrumbs')
    <x-backend-breadcrumb-item type="active">{{ $entity }}</x-backend-breadcrumb-item>
@endsection

@section('content')

    @if(!$races->count())
        <div class="card text-center">
            <div class="card-body p-5">
                <i class="fa-solid fa-flag-checkered fa-5x opacity-50"></i>
                <h5 class="mt-4">{{ __('Nessuna iscrizione possibile') }}</h5>
            </div>
        </div>
    @else
        <div class="card">
            {{ html()->form('POST', route("races.subscription.store"))->class('form')->open() }}
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <div class="float-end">
                                @can('subscribe', App\Models\AthleteFee::class)
                                    <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="card-title">{{ __('Gara') }}</h6>
                            
                            <select id="fee_selector" name="fee_id" class="form-control {{ $errors->has('fee_id') ? 'is-invalid' : '' }}">
                                <option value="0">{{ __('Seleziona') }}</option>
                                @foreach ($races as $race)
                                    <optgroup label="{{ $race->name }}">
                                        @foreach ($race->fees as $fee)
                                            <option data-race="{{ $race->id }}" data-fee="{{ $fee->id }}" value="{{ $fee->id }}" @if ($fee->id == old('fee_id')) selected @endif>{{ $race->name }} - {{ $fee->name }} (@money($fee->amount))</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div id="athletes-list" class="mt-4"></div>

                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            <div class="float-end">
                                <div class="form-group">
                                    @can('subscribe', App\Models\AthleteFee::class)
                                        <x-backend.buttons.save small="true" >{{__('Salva')}}</x-backend.buttons.save>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{ html()->form()->close() }}
        </div>
    @endif

@endsection

@push ('after-styles')


@endpush

@push ('after-scripts')

<script type="text/javascript">
    $(document).ready(function() {
        $('#fee_selector').on('change', function(event) {
            
            var race_id = event.target.options[event.target.selectedIndex].dataset.race;
            var fee_id = event.target.options[event.target.selectedIndex].dataset.fee;
            
            let endpoint_url = '{{ url("") }}/races/' + race_id + '/fees/' + fee_id + '/athletesSubscribeable';
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: endpoint_url,
            }).done(function(data) {
                //console.log(data);
                $('#athletes-list').html(data);
                /*if (data.type == 'success') {
                    iziToast.success({
                        message: data.message
                    });
                } else {
                    iziToast.error({
                        message: data.message
                    });
                }
                */
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.log("fail");
                /*iziToast.error({
                    message: jqXHR.responseJSON ? jqXHR.responseJSON.message : textStatus
                });
                */
            }).always(function() {
                console.log("always");
                //is_done = true;
                //$button.attr('disabled', false);
                //Tools.unblockUI();
                //drawDocumentStatus();
                //dataTable.draw(false);
            });
        });

        $(document).on('change', '.athlete_subscription_switch', function(){
            var input_form = $(this).closest('ul').find('.athlete_subscription_form');
            if($(this).is(':checked')){
                input_form.removeClass('d-none');
            }else{
                input_form.addClass('d-none');
            }
            input_form.find('*').prop('disabled', !($(this).is(':checked')));
            
        });

    });
</script>

@endpush
