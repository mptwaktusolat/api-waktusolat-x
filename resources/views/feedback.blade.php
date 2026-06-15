@extends('layouts.app')

@section('content')
    {{-- Page header --}}
    <section class="border-b border-base-300 bg-base-200/50 px-6 py-14">
        <div class="mx-auto max-w-7xl">
            <h1 class="mb-4 text-5xl font-light tracking-tight text-base-content md:text-6xl">{{ __('feedback.title') }}</h1>
            <p class="max-w-3xl text-base text-base-content/70 md:text-lg">{{ __('feedback.subtitle') }}</p>
        </div>
    </section>

    <section class="px-6 py-12 md:py-16">
        <div class="mx-auto w-full max-w-4xl">
            <div>
                <div class="grid grid-cols-3 overflow-hidden">
                    <div class="bg-tile-blue px-4 py-2"></div>
                    <div class="bg-tile-green px-4 py-2"></div>
                    <div class="bg-tile-orange px-4 py-2"></div>
                </div>

                <form id="feedback-form" action="https://submit-form.com/{{ env('FORMSPARK_FORM_ID') }}" method="POST"
                    class="card border border-base-300 bg-base-100">
                    <div class="card-body space-y-8 p-6 md:p-8">
                        {{-- Honeypot for spam prevention --}}
                        <input type="hidden" name="_feedback_trap" style="display:none" value="">
                        <input type="hidden" name="language" id="language" value="{{ app()->getLocale() }}">

                        {{-- Formspark configuration https://documentation.formspark.io/customization/redirection#specifying-a-custom-redirect-url --}}
                        <input type="hidden" name="_redirect" value="{{ route('feedback.success') }}">
                        {{-- Do not append submission details to redirected page --}}
                        <input type="hidden" name="_append" value="false" />

                        <div class="space-y-2 border-b border-base-300 pb-6">

                            <h2 class="text-2xl font-light text-base-content">{{ __('feedback.quick_poll') ?? '' }}</h2>
                            <p class="text-sm text-base-content/70">{{ __('feedback.quick_poll_hint') ?? '' }}</p>
                        </div>

                        {{-- 1. Satisfaction --}}
                        <fieldset class="space-y-3">
                            <legend class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                                {{ __('feedback.satisfaction') }}
                            </legend>
                            <div class="grid gap-3 sm:grid-cols-2" id="satisfaction-options">
                                <input type="radio" id="satisfied-yes" name="satisfied" value="yes" required
                                    class="hidden satisfaction-radio" />
                                <label for="satisfied-yes"
                                    class="satisfaction-label flex cursor-pointer items-center gap-3 border border-base-300 bg-base-200 px-4 py-3 text-sm font-medium text-base-content transition-all hover:brightness-95"
                                    data-selected-classes="bg-tile-green text-white border-transparent">
                                    <x-ionicon-thumbs-up-outline class="h-5 w-5" />
                                    <span>{{ __('feedback.thumbs_up') }}</span>
                                </label>

                                <input type="radio" id="satisfied-no" name="satisfied" value="no" required
                                    class="hidden satisfaction-radio" />
                                <label for="satisfied-no"
                                    class="satisfaction-label flex cursor-pointer items-center gap-3 border border-base-300 bg-base-200 px-4 py-3 text-sm font-medium text-base-content transition-all hover:brightness-95"
                                    data-selected-classes="bg-tile-orange text-white border-transparent">
                                    <x-ionicon-thumbs-down-outline class="h-5 w-5" />
                                    <span>{{ __('feedback.thumbs_down') }}</span>
                                </label>
                            </div>
                        </fieldset>

                        {{-- 2. Asking documentation readability --}}
                        <fieldset class="space-y-3">
                            <legend class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                                {{ __('feedback.documentation') }}
                            </legend>
                            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3" id="doc-options">
                                @php
                                    $docOptions = [
                                        ['key' => 'doc_very_easy', 'value' => 'Very Easy'],
                                        ['key' => 'doc_easy', 'value' => 'Easy'],
                                        ['key' => 'doc_neutral', 'value' => 'Neutral'],
                                        ['key' => 'doc_difficult', 'value' => 'Difficult'],
                                        ['key' => 'doc_very_difficult', 'value' => 'Very Difficult'],
                                    ];
                                @endphp
                                @foreach ($docOptions as $index => $option)
                                    <input type="radio" id="doc-{{ $index }}" name="documentation_readability"
                                        value="{{ $option['value'] }}" class="hidden doc-radio" />
                                    <label for="doc-{{ $index }}"
                                        class="doc-label cursor-pointer border border-base-300 bg-base-200 px-4 py-2.5 text-sm font-medium text-base-content transition-all hover:brightness-95"
                                        data-selected-classes="bg-tile-blue text-white border-transparent">
                                        {{ __('feedback.' . $option['key']) }}
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <div class="space-y-2 border-b border-base-300 pb-6">
                            <h3 class="text-2xl font-light text-base-content">{{ __('feedback.details') ?? '' }}</h3>
                            <p class="text-sm text-base-content/70">{{ __('feedback.privacy_note') ?? '' }}</p>
                        </div>

                        {{-- 3. Comments --}}
                        <fieldset class="space-y-3">
                            <label for="comments"
                                class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                                {{ __('feedback.comments') }}
                            </label>
                            <textarea id="comments" name="comments" rows="4" placeholder="{{ __('feedback.comments_placeholder') }}"
                                class="textarea textarea-bordered w-full border-base-300 bg-base-100 focus:border-tile-blue mt-2"></textarea>
                        </fieldset>

                        {{-- 4. Email --}}
                        <fieldset class="space-y-3">
                            <label for="email"
                                class="text-sm font-semibold uppercase tracking-[0.08em] text-base-content">
                                {{ __('feedback.email') }}
                            </label>
                            <p class="text-xs text-base-content/70">
                                {{ __('feedback.email_hint') }}
                            </p>
                            <input type="email" id="email" name="email"
                                placeholder="{{ __('feedback.email_placeholder') }}"
                                class="input input-bordered w-full border-base-300 bg-base-100 focus:border-tile-blue" />
                        </fieldset>

                        {{-- Submit button --}}
                        <button type="submit" id="submit-btn"
                            class="btn w-full border-0 bg-tile-green text-white hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60">
                            <span>{{ __('feedback.submit') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function updateSelectableStyles(radioSelector, labelSelector) {
            const radios = document.querySelectorAll(radioSelector);
            const labels = document.querySelectorAll(labelSelector);

            labels.forEach(label => {
                label.classList.remove('bg-tile-blue', 'bg-tile-green', 'bg-tile-orange', 'text-white',
                    'border-transparent');
                label.classList.add('bg-base-200', 'text-base-content', 'border-base-300');
            });

            radios.forEach(radio => {
                if (!radio.checked) {
                    return;
                }
                const selectedLabel = document.querySelector(`label[for="${radio.id}"]`);
                if (!selectedLabel) {
                    return;
                }
                selectedLabel.classList.remove('bg-base-200', 'text-base-content', 'border-base-300');
                const selectedClasses = (selectedLabel.getAttribute('data-selected-classes') || '').split(' ')
                    .filter(Boolean);
                selectedLabel.classList.add(...selectedClasses);
            });
        }

        // Documentation readability + satisfaction tile selection
        document.querySelectorAll('.doc-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                updateSelectableStyles('.doc-radio', '.doc-label');
            });
        });

        document.querySelectorAll('.satisfaction-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                updateSelectableStyles('.satisfaction-radio', '.satisfaction-label');
            });
        });

        // Disable button when user clicks submit. To prevent redundant submissions.
        const feedbackForm = document.getElementById('feedback-form');
        const submitBtn = document.getElementById('submit-btn');
        if (feedbackForm && submitBtn) {
            feedbackForm.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.querySelector('span').textContent = '{{ __('feedback.sending') }}';
            });
        }

        updateSelectableStyles('.doc-radio', '.doc-label');
        updateSelectableStyles('.satisfaction-radio', '.satisfaction-label');
    </script>
@endsection
