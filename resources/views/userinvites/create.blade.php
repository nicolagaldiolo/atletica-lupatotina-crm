@extends('backend.layouts.app')
@section('content')
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">

        <div class=" w-full sm:max-w-md mt-24 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route("userinvites.store") }}">
                @csrf

                <!-- Email Address -->
                <div class="mt-4">
                    <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus/>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="ml-4">
                        {{ __('Invite') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection