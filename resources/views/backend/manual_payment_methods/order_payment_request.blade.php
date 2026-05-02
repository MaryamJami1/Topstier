@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Offline Order Payment Requests')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Order Code') }}</th>
                    <th>{{translate('Name')}}</th>
                    <th>{{translate('Amount')}}</th>
                    <th>{{translate('Method')}}</th>
                    <th>{{translate('TXN ID')}}</th>
                    <th>{{translate('Approval')}}</th>
                    <th>{{translate('Date')}}</th>
                    <th>{{translate('Receipt')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offline_payments as $key => $payment)
                    @php
                        $payment_info = json_decode($payment->manual_payment_data);
                      
                        $receipt = $payment_info?->receipt ?? $payment_info?->reciept ?? null;
                    @endphp
                    {{-- @if ($payment->user != null) --}}
                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td >
                                    @if (addon_is_activated('multi_vendor'))
                                        <div class="color-orange">{{ translate('Package') }} {{ $payment->code }} {{ translate('of') }}</div>
                                    @endif
                                    <div class="fw-600 color-orange">{{ $payment->combined_order->code ?? '' }}</div>
                            </td>
                            <td>{{ $payment->user->name ?? 'Guest' }}</td>
                            <td>{{ $payment->grand_total }}</td>
                            <td>{{ $payment_info?->payment_method }}</td>
                            <td>{{ $payment_info?->transactionId }}</td>
                           
                            <td>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input 
                                        @if(auth()->user()->can('approve_offline_order_payment') )
                                            onclick="update_approved('{{ route('orders.payments.approved', $payment->id )}}', event)"
                                        @endif
                                        type="checkbox" 
                                        @if($payment->payment_status == 'paid') checked   @endif 
                                        @cannot('approve_offline_order_payment') disabled @endcan >
                                        <span class="slider round"></span>
                                </label>
                            </td>

                            <td>{{ $payment->updated_at }}</td>
                             <td>
                                @if ($receipt)
                                    <button class="btn btn-primary btn-sm" type="button" onclick="open_recepit_modal('{{  my_asset($receipt) }}')" >Recepit</button>
                                @endif
                            </td>
                        </tr>
                    {{-- @endif --}}
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $offline_payments->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
<div id="payment-approval-modal" class="modal fade">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{translate('Offline Payment for Order Confirmation')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <i class="la la-4x la-warning text-warning mb-4"></i>
                <p class="fs-18 fw-600 mb-1">{{ translate('Are you sure to approve this?') }}</p>
            </div>
            <div class="modal-footer">
                <button  type="button" class="btn btn-link mt-2" data-dismiss="modal">{{translate('Cancel')}}</button>
                <a href="" id="approve-link" class="btn btn-primary mt-2">{{translate('Yes, Approve')}}</a>
            </div>
        </div>
    </div>
</div>


<div id="payment-receipt-modal" class="modal fade">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-zoom">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title h6">{{translate('Offline Payment for Order Receipt')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center ">
               <img id="receipt-image" class="mx-auto" src="" style="width: 400px; height: 300px;" alt="receipt image">
               <div id="no-receipt-message" style="display:none; font-weight:bold; font-size:16px; color:red;">
                    No receipt found
                </div>
            </div>
           
        </div>
    </div>
</div>
@endsection



@section('script')
    <script type="text/javascript">
        function update_approved(url, event){
            event.preventDefault();

            $("#payment-approval-modal").modal("show");
            $("#approve-link").attr("href", url);
        }
    </script>
    <script type="text/javascript">

        function open_recepit_modal(url){

            if(url && url.trim() !== "") {
                $("#receipt-image").attr("src", url).show();
                $("#no-receipt-message").hide();
            } else {
                $("#receipt-image").hide();
                $("#no-receipt-message").show();
            }

            $("#payment-receipt-modal").modal("show");
        }
    </script>
@endsection
