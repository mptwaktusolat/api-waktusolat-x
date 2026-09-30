@extends('layouts.app')

@section('content')
    <section class="border-b border-base-300 bg-base-200/50 px-6 py-14">
        <div class="mx-auto max-w-7xl">
            <h1 class="mb-4 text-5xl font-light tracking-tight text-base-content md:text-6xl">Submit your app</h1>
            <p class="max-w-3xl text-base text-base-content/70 md:text-lg">
                Using the Waktu Solat API in your app? Share it for the app showcase section on our homepage.
                It is not an advertisement or sponsorship, we will not add backlinks to your app.
            </p>
        </div>
    </section>

    <section class="px-6 py-12 md:py-16">
        <div class="mx-auto w-full max-w-4xl">
            <div class="grid grid-cols-3 overflow-hidden">
                <div class="bg-tile-blue px-4 py-2"></div>
                <div class="bg-tile-green px-4 py-2"></div>
                <div class="bg-tile-orange px-4 py-2"></div>
            </div>

            @php
                abort_unless(config('services.formspark.form_id'), 500, 'FORMSPARK_FORM_ID is not configured.');
            @endphp
            <form action="https://submit-form.com/{{ config('services.formspark.form_id') }}" method="POST"
                class="card border border-base-300 bg-base-100">
                <div class="card-body space-y-6 p-6 md:p-8">
                    <input type="hidden" name="_submission_trap" style="display:none" value="">
                    <input type="hidden" name="submission_type" value="app_showcase">
                    <input type="hidden" name="_redirect" value="{{ route('submit-your-app.success') }}">
                    <input type="hidden" name="_append" value="false">

                    <div class="space-y-2 border-b border-base-300 pb-6">
                        <h2 class="text-2xl font-light text-base-content">App details</h2>
                        <p class="text-sm text-base-content/70">Send us a link to your logo so we can review your app for
                            the showcase.</p>
                    </div>

                    <fieldset class="space-y-3">
                        <label for="app-name" class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                            App name
                        </label>
                        <input id="app-name" name="app_name" type="text" required autocomplete="organization"
                            class="input input-bordered w-full border-base-300 bg-base-100 focus:border-tile-blue">
                    </fieldset>

                    <fieldset class="space-y-3">
                        <label for="email" class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                            Email
                        </label>
                        <input id="email" name="email" type="email" required autocomplete="email"
                            class="input input-bordered w-full border-base-300 bg-base-100 focus:border-tile-blue">
                    </fieldset>

                    <fieldset class="space-y-3">
                        <label for="logo-url" class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                            App logo link
                        </label>
                        <p class="text-xs text-base-content/70">Public URL. It can be link to image, Google Drive, Imgur
                            etc.</p>
                        <input id="logo-url" name="logo_url" type="url" required
                            class="input input-bordered w-full border-base-300 bg-base-100 focus:border-tile-blue">
                    </fieldset>

                    <button type="submit" class="btn w-full border-0 bg-tile-green text-white hover:brightness-110">
                        Submit your app
                    </button>
                </div>
            </form>
        </div>
    </section>

    <x-footer />
@endsection
