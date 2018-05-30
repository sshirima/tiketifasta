<section class="features-icons">
    <div class="row">
        <div  class="col-md-4"></div>
        <div class="col-md-4">
            <h3 class="text-center login-title">{{__('admin_page_accounts.field_form_title')}}</h3>
            <div style="padding-right: 10px" class="login-form">
                <form class="form-signin" method="post" action="{{route('admin.admin_accounts.store')}}">
                    <br>
                    <input type="text" name="{{\App\Models\Admin::COLUMN_FIRST_NAME}}" id="{{ \App\Models\Admin::COLUMN_FIRST_NAME }}" placeholder="{{__('page_auth_register.placeholder_first_name')}}"
                           class="form-control" autofocus value="{{ old(\App\Models\Admin::COLUMN_FIRST_NAME) }}" required>
                    <br>
                    <input type="text" name="{{\App\Models\Admin::COLUMN_LAST_NAME }}" id="{{ \App\Models\Admin::COLUMN_LAST_NAME }}" placeholder="{{__('page_auth_register.placeholder_last_name')}}"
                           class="form-control" value="{{ old(\App\Models\Admin::COLUMN_LAST_NAME) }}" required>
                    <br>
                    <input type="email" name="{{\App\Models\Admin::COLUMN_EMAIL }}" id="{{ \App\Models\Admin::COLUMN_EMAIL }}" placeholder="{{__('page_auth_register.placeholder_email')}}"
                           class="form-control" value="{{ old(\App\Models\Admin::COLUMN_EMAIL) }}" required><br>
                    <input type="text" name="{{\App\Models\Admin::COLUMN_PHONE_NUMBER }}" id="{{ \App\Models\Admin::COLUMN_PHONE_NUMBER }}" placeholder="{{__('page_auth_register.placeholder_phone_number')}}"
                           class="form-control" value="{{ old(\App\Models\Admin::COLUMN_PHONE_NUMBER) }}" required>
                    <br>
                    <input type="password" name="password" id="password" placeholder="{{__('page_auth_register.placeholder_password')}}"
                           class="form-control" required><br>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="{{__('page_auth_register.placeholder_confirm_password')}}" class="form-control" required><br>
                    <button type="submit" class="btn btn-success btn-block">{{__('admin_page_accounts.field_button_store')}}</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>
    </div>
</section>