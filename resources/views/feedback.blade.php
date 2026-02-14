@extends('layouts.app')

@section('body')

    <body class="dark:bg-zinc-900 min-h-screen flex flex-col">

        <main class="flex items-start justify-center flex-1 flex-col relative overflow-hidden">
            {{-- Dark mode: Subtle glowing object --}}
            <div
                class="hidden dark:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-pink-500/20 rounded-full blur-[100px] opacity-50">
            </div>
            {{-- Light mode: Blue gradient from bottom to top --}}
            <div class="dark:hidden block absolute top-0 left-0 w-full h-full bg-gradient-to-t from-blue-50 to-white">
            </div>

            <div class="mx-auto flex w-full max-w-2xl flex-col gap-6 px-6 lg:px-8 pt-12 pb-24 z-10">
                {{-- Header --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <a href="/" class="text-blue-600 hover:underline dark:text-blue-400">
                            {{ __('feedback.breadcrumb_home') }}
                        </a>
                        /
                        <a href="/about" class="text-blue-600 hover:underline dark:text-blue-400">
                            {{ __('feedback.breadcrumb_about') }}
                        </a>
                        / {{ __('feedback.title') }}
                    </h1>
                    <div class="flex gap-2">
                        <button onclick="setLanguage('ms')" id="btn-ms"
                            class="rounded-md px-4 py-2 text-sm font-medium transition-colors bg-pink-500 text-white dark:bg-pink-600">
                            Bahasa Melayu
                        </button>
                        <button onclick="setLanguage('en')" id="btn-en"
                            class="rounded-md px-4 py-2 text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            English
                        </button>
                    </div>
                </div>

                <p class="text-gray-600 dark:text-gray-400" data-i18n="subtitle">
                    {{ __('feedback.subtitle') }}
                </p>

                {{-- Form --}}
                <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;"
                    onload="if(submitted) { document.getElementById('feedback-form').classList.add('hidden'); document.getElementById('success-message').classList.remove('hidden'); document.getElementById('submit-btn').disabled = false; }"></iframe>
                <form id="feedback-form" action="https://submit-form.com/{{ env('FORMSPARK_FORM_ID', 'Q4f6uL9QC') }}"
                    method="POST" target="hidden_iframe" class="space-y-8" onsubmit="handleSubmit()">

                    {{-- Honeypot for spam prevention --}}
                    <input type="hidden" name="_feedback_trap" style="display:none" value="">
                    <input type="hidden" name="language" id="language" value="ms">

                    {{-- 1. Satisfaction (thumbs up / thumbs down) --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white" data-i18n="satisfaction">
                            {{ __('feedback.satisfaction') }}
                        </label>
                        <div class="flex gap-3">
                            <input type="radio" id="satisfied-yes" name="satisfied" value="yes" required
                                class="peer/yes hidden" />
                            <label for="satisfied-yes"
                                class="flex items-center gap-2 cursor-pointer rounded-xl border-2 border-gray-200 dark:border-gray-700 px-5 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 transition-all hover:border-green-400 hover:bg-green-50 dark:hover:border-green-500 dark:hover:bg-green-900/20 peer-checked/yes:border-green-500 peer-checked/yes:bg-green-50 peer-checked/yes:text-green-700 dark:peer-checked/yes:border-green-400 dark:peer-checked/yes:bg-green-900/30 dark:peer-checked/yes:text-green-300">
                                <span class="text-xl">👍</span>
                                <span data-i18n="thumbs_up">{{ __('feedback.thumbs_up') }}</span>
                            </label>

                            <input type="radio" id="satisfied-no" name="satisfied" value="no" required
                                class="peer/no hidden" />
                            <label for="satisfied-no"
                                class="flex items-center gap-2 cursor-pointer rounded-xl border-2 border-gray-200 dark:border-gray-700 px-5 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 transition-all hover:border-red-400 hover:bg-red-50 dark:hover:border-red-500 dark:hover:bg-red-900/20 peer-checked/no:border-red-500 peer-checked/no:bg-red-50 peer-checked/no:text-red-700 dark:peer-checked/no:border-red-400 dark:peer-checked/no:bg-red-900/30 dark:peer-checked/no:text-red-300">
                                <span class="text-xl">👎</span>
                                <span data-i18n="thumbs_down">{{ __('feedback.thumbs_down') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- 2. Documentation readability --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white" data-i18n="documentation">
                            {{ __('feedback.documentation') }}
                        </label>
                        <div class="flex flex-wrap gap-2" id="doc-options">
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
                                    class="doc-label cursor-pointer rounded-xl border-2 border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 transition-all hover:border-pink-400 hover:bg-pink-50 dark:hover:border-pink-500 dark:hover:bg-pink-900/20"
                                    data-i18n="{{ $option['key'] }}">
                                    {{ __('feedback.' . $option['key']) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 4. Comments --}}
                    <div class="space-y-2">
                        <label for="comments" class="block text-sm font-semibold text-gray-900 dark:text-white"
                            data-i18n="comments">
                            {{ __('feedback.comments') }}
                        </label>
                        <textarea id="comments" name="comments" rows="4" data-i18n-placeholder="comments_placeholder"
                            placeholder="{{ __('feedback.comments_placeholder') }}"
                            class="w-full rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-pink-500 focus:outline-none focus:ring-0 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-pink-400 resize-y"></textarea>
                    </div>

                    {{-- 4. Email --}}
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white"
                            data-i18n="email">
                            {{ __('feedback.email') }}
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400" data-i18n="email_hint">
                            {{ __('feedback.email_hint') }}
                        </p>
                        <input type="email" id="email" name="email" data-i18n-placeholder="email_placeholder"
                            placeholder="{{ __('feedback.email_placeholder') }}"
                            class="w-full rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-colors focus:border-pink-500 focus:outline-none focus:ring-0 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-pink-400" />
                    </div>

                    {{-- Submit button --}}
                    <button type="submit" id="submit-btn"
                        class="w-full rounded-xl bg-pink-500 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-offset-gray-900 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submit-text" data-i18n="submit">{{ __('feedback.submit') }}</span>
                        <span id="submit-loader" class="hidden">
                            <svg class="inline-block animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span data-i18n="submitting">{{ __('feedback.submitting', [], 'ms') }}</span>
                        </span>
                    </button>
                </form>

                {{-- Success message (hidden by default) --}}
                <div id="success-message" class="hidden flex-1 flex items-center justify-center min-h-[60vh]">
                    <div
                        class="rounded-2xl border-2 border-green-200 bg-green-50 p-8 text-center dark:border-green-800 dark:bg-green-900/30">
                        <div
                            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/50">
                            <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-green-800 dark:text-green-300" data-i18n="success_title">
                            {{ __('feedback.success_title') }}
                        </h3>
                        <p class="mb-6 text-green-700 dark:text-green-400" data-i18n="success_message">
                            {{ __('feedback.success_message') }}
                        </p>
                        <button onclick="resetForm()"
                            class="rounded-xl bg-green-600 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600"
                            data-i18n="submit_another">
                            {{ __('feedback.submit_another') }}
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <script>
            let submitted = false;

            // Translation strings loaded from Laravel locale files
            const translations = {
                ms: @json(__('feedback', [], 'ms')),
                en: @json(__('feedback', [], 'en')),
            };

            let currentLanguage = 'ms';

            function handleSubmit() {
                submitted = true;
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const submitLoader = document.getElementById('submit-loader');

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                submitLoader.classList.remove('hidden');

                return true; // Allow form submission to proceed
            }

            function setLanguage(language) {
                currentLanguage = language;
                const t = translations[language];

                // Update all elements that have data-i18n attribute
                document.querySelectorAll('[data-i18n]').forEach(el => {
                    const key = el.getAttribute('data-i18n');
                    if (t[key]) {
                        el.textContent = t[key];
                    }
                });

                // Update all elements with data-i18n-placeholder
                document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                    const key = el.getAttribute('data-i18n-placeholder');
                    if (t[key]) {
                        el.placeholder = t[key];
                    }
                });

                // Update button styles
                const btnMs = document.getElementById('btn-ms');
                const btnEn = document.getElementById('btn-en');

                if (language === 'ms') {
                    btnMs.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-pink-500 text-white dark:bg-pink-600';
                    btnEn.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700';
                } else {
                    btnEn.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-pink-500 text-white dark:bg-pink-600';
                    btnMs.className =
                        'rounded-md px-4 py-2 text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700';
                }

                // Update hidden language field
                document.getElementById('language').value = language;
            }

            function resetForm() {
                submitted = false;
                document.getElementById('feedback-form').reset();
                document.getElementById('feedback-form').classList.remove('hidden');
                document.getElementById('success-message').classList.add('hidden');

                // Reset button state
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const submitLoader = document.getElementById('submit-loader');
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoader.classList.add('hidden');

                // Reset doc label styles
                document.querySelectorAll('.doc-label').forEach(label => {
                    label.classList.remove('border-pink-500', 'bg-pink-50', 'text-pink-700',
                        'dark:border-pink-400', 'dark:bg-pink-900/30', 'dark:text-pink-300');
                    label.classList.add('border-gray-200', 'dark:border-gray-700',
                        'text-gray-700', 'dark:text-gray-300');
                });
            }

            // Documentation readability radio selection
            document.querySelectorAll('.doc-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.doc-label').forEach(label => {
                        label.classList.remove('border-pink-500', 'bg-pink-50', 'text-pink-700',
                            'dark:border-pink-400', 'dark:bg-pink-900/30', 'dark:text-pink-300');
                        label.classList.add('border-gray-200', 'dark:border-gray-700',
                            'text-gray-700', 'dark:text-gray-300');
                    });
                    const selected = document.querySelector(`label[for="${this.id}"]`);
                    selected.classList.remove('border-gray-200', 'dark:border-gray-700',
                        'text-gray-700', 'dark:text-gray-300');
                    selected.classList.add('border-pink-500', 'bg-pink-50', 'text-pink-700',
                        'dark:border-pink-400', 'dark:bg-pink-900/30', 'dark:text-pink-300');
                });
            });

            // Sync language with about page via URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const langParam = urlParams.get('lang');
            if (langParam === 'en' || langParam === 'ms') {
                setLanguage(langParam);
            }
        </script>
    </body>
@endsection
