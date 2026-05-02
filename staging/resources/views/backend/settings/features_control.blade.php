@extends('backend.layouts.app')

@section('content')
    <h4 class="text-center text-muted">{{ translate('System') }}</h4>
    <div class="row">
        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('HTTPS Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'FORCE_HTTPS')"
                            @if (env('FORCE_HTTPS') == 'On') checked @endif>
                        <span class="slider round"></span>
                    </label>
                     <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, the website will run over a secure HTTPS connection') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Maintenance Mode Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'maintenance_mode')" <?php if (get_setting('maintenance_mode') == 1) {
                            echo 'checked';
                        } ?>>
                        <span class="slider round"></span>
                    </label>
                     <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, the website will be placed in maintenance mode and visitors will see a maintenance page') }}.
                    </div>
                </div>
            </div>
        </div>

    </div>

    <h4 class="text-center text-muted mt-4">{{ translate('Business Related') }}</h4>
    <div class="row">
        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Wallet System Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'wallet_system')"
                            @if (get_setting('wallet_system') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, users can add money to their wallet and use it for placing orders') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Pickup Point Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'pickup_point')"
                            @if (get_setting('pickup_point') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message">
                        {{ translate("After activating this option, customers can select local pickup points during checkout") }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Club Point Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'club_point')"
                            @if (get_setting('club_point') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, customers will earn reward points and can convert them into wallet balance') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Product Comparison Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'product_comparison')" <?php if (get_setting('product_comparison') == 1) {
                            echo 'checked';
                        } ?>>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, customers can compare multiple products side-by-side') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Affiliate System Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'affiliate_system')" <?php if (get_setting('affiliate_system') == 1) {
                            echo 'checked';
                        } ?>>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, users can earn commission by referring new customers') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Delivery Boy Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'delivery_boy')" <?php if (get_setting('delivery_boy') == 1) {
                            echo 'checked';
                        } ?>>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, delivery personnel can register and manage their assigned orders') }}.
                    </div>
                </div>
            </div>
        </div>



        @if ((float) get_setting('current_version') > 1.6)
            <div class="col-6 col-md-4">
                <div class="card shadow-lg rounded-3  border-1">
                    <div class="card-header">
                        <h3 class="mb-0 h6 ">{{ translate('Offline Payment Activation') }}</h3>
                    </div>
                    <div class="card-body p-4 text-center">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" onchange="updateSettings(this, 'offline_payment')"
                                @if (get_setting('offline_payment') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                        <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, customers can use offline payment methods') }}.
                    </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Support Chat Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'support_chat')"
                            @if (get_setting('support_chat') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, customers can contact support through live chat') }}.
                    </div>
                </div>
            </div>
        </div>










        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Sticky Header Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'sticky_header')"
                            @if (get_setting('sticky_header') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                    <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, the website header will remain visible while scrolling') }}.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Express Delivery Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'express_delivery_option')"
                            @if (get_setting('express_delivery_option') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                     <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, customers can choose express delivery during checkout') }}.
                    </div>
                </div>
            </div>
        </div>

         <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Guest Checkout Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'guest_checkout_activation')"
                            @if (get_setting('guest_checkout_activation') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                     <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, customers can place orders without creating an account') }}.
                    </div>
                </div>
            </div>
        </div>

         <div class="col-6 col-md-4">
            <div class="card shadow-lg rounded-3  border-1">
                <div class="card-header">
                    <h3 class="mb-0 h6 ">{{ translate('Track Order for Guest User Activation') }}</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="checkbox" onchange="updateSettings(this, 'track_order_guest_user')"
                            @if (get_setting('track_order_guest_user') == 1) checked @endif>
                        <span class="slider round"></span>
                    </label>
                     <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, guest users will be able to track their orders') }}.
                    </div>
                </div>
            </div>
        </div>

        

    </div>
    @if (addon_is_activated('multi_vendor'))
        <h4 class="text-center text-muted mt-4">{{ translate('Vendor Settings') }}</h4>
    @endif
    <div class="row">
        @if (addon_is_activated('multi_vendor'))
            <div class="col-6 col-md-4">
                <div class="card shadow-lg rounded-3  border-1">
                    <div class="card-header">
                        <h3 class="mb-0 h6 ">{{ translate('Admin Approval On Seller Product') }}</h3>
                    </div>
                    <div class="card-body p-4 text-center">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" onchange="updateSettings(this, 'product_approve_by_admin')"
                                <?php if (get_setting('product_approve_by_admin') == 1) {
                                    echo 'checked';
                                } ?>>
                            <span class="slider round"></span>
                        </label>
                        <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, admin approval is needed for seller products') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="card shadow-lg rounded-3  border-1">
                    <div class="card-header">
                        <h3 class="mb-0 h6 ">
                            {{ translate('Seller Product Manage By Admin') }}</h3>
                    </div>
                    <div class="card-body p-4 text-center">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" onchange="updateSettings(this, 'product_manage_by_admin')"
                                <?php if (get_setting('product_manage_by_admin') == 1) {
                                    echo 'checked';
                                } ?>>
                            <span class="slider round"></span>
                        </label>
                        <div class="alert"
                        style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        {{ translate('After activating this option, admin can manage seller products') }}.
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card shadow-lg rounded-3  border-1">
                    <div class="card-header">
                        <h3 class="mb-0 h6 ">{{ translate('Seller Order Manage By Admin') }}</h3>
                    </div>
                    <div class="card-body p-4 text-center">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" onchange="updateSettings(this, 'order_manage_by_admin')"
                                <?php if (get_setting('order_manage_by_admin') == 1) {
                                    echo 'checked';
                                } ?>>
                            <span class="slider round"></span>
                        </label>
                        <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, COD for seller products will be managed by admin') }}.
                    </div>
                    </div>
                </div>
            </div>
        @endif

        @if ((float) get_setting('current_version') > 1.3 && addon_is_activated('multi_vendor'))
            <div class="col-6 col-md-4">
                <div class="card shadow-lg rounded-3  border-1">
                    <div class="card-header">
                        <h3 class="mb-0 h6 ">{{ translate('Conversation System Activation') }}</h3>
                    </div>
                    <div class="card-body p-4 text-center">
                        <label class="aiz-switch aiz-switch-success mb-0">
                            <input type="checkbox" onchange="updateSettings(this, 'conversation_system')"
                                @if (get_setting('conversation_system') == 1) checked @endif>
                            <span class="slider round"></span>
                        </label>
                        <div class="alert features-alert-message"> 
                            {{ translate('After activating this option, users can communicate with sellers or the admin through the platform') }}.
                    </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function updateSettings(el, type) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post('{{ route('settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == '1') {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection


@section('script')
    <script type="text/javascript">
        function updateSettings(el, type) {
            if ($(el).is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
            $.post('{{ route('settings.update.activation') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == '1') {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
