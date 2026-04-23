@php
    /** @var \App\Models\ApplicationSetting $settings */
    $notifEmail = old('notification_email', $settings->notificationEmailTemplatesNormalized());
    $accordionId = 'notificationEmailTemplatesAccordion';
@endphp

<hr class="my-4">

<div class="mb-2">
    <h6 class="fw-semibold mb-1">{{ __('Notification emails') }}</h6>
    <p class="text-muted small mb-0">
        {{ __('Optional subject and HTML body for each notification. Leave the body empty to use the built-in layout for that message.') }}
    </p>
</div>

<div class="accordion mb-4" id="{{ $accordionId }}">
    @foreach (\App\Services\Mail\SiteEmailTemplateService::allTemplateKeys() as $key)
        @php
            $m = \App\Services\Mail\SiteEmailTemplateService::adminUiMeta($key);
            $slot = isset($notifEmail[$key]) && is_array($notifEmail[$key]) ? $notifEmail[$key] : [];
            $subj = isset($slot['subject']) ? (string) $slot['subject'] : '';
            $body = isset($slot['body_html']) ? (string) $slot['body_html'] : '';
            $headingId = 'headingNotifTpl'.preg_replace('/[^a-z0-9]+/i', '', $key);
            $collapseId = 'collapseNotifTpl'.preg_replace('/[^a-z0-9]+/i', '', $key);
        @endphp
        <div class="accordion-item">
            <h2 class="accordion-header" id="{{ $headingId }}">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}"
                    aria-expanded="false"
                    aria-controls="{{ $collapseId }}"
                >
                    {{ $m['title'] }}
                </button>
            </h2>
            <div
                id="{{ $collapseId }}"
                class="accordion-collapse collapse"
                aria-labelledby="{{ $headingId }}"
                data-bs-parent="#{{ $accordionId }}"
            >
                <div class="accordion-body">
                    <p class="text-muted small mb-2">{{ $m['when'] }}</p>
                    <div class="mb-3">
                        <label class="form-label" for="notification_email_{{ $key }}_subject">{{ __('Email subject (optional)') }}</label>
                        <input
                            type="text"
                            class="form-control @error('notification_email.'.$key.'.subject') is-invalid @enderror"
                            id="notification_email_{{ $key }}_subject"
                            name="notification_email[{{ $key }}][subject]"
                            value="{{ $subj }}"
                            maxlength="255"
                            placeholder="{{ __('Leave empty for default subject') }}"
                            autocomplete="off"
                        >
                        @error('notification_email.'.$key.'.subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="notification_email_{{ $key }}_body">{{ __('HTML body (optional)') }}</label>
                        <textarea
                            class="form-control js-notification-email-html @error('notification_email.'.$key.'.body_html') is-invalid @enderror"
                            id="notification_email_{{ $key }}_body"
                            name="notification_email[{{ $key }}][body_html]"
                            rows="10"
                            spellcheck="true"
                            placeholder="{{ __('Leave empty for default layout') }}"
                        >{{ $body }}</textarea>
                        @error('notification_email.'.$key.'.body_html')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="text-muted small mt-1 mb-0">{{ __('Use the toolbar for formatting; open "Source code" for raw HTML (useful for [[PLACEHOLDERS]]).') }}</p>
                    </div>
                    <p class="text-muted small mb-0">
                        {{ __('Placeholders (replaced when sent):') }}
                        <code class="d-block mt-1 small user-select-all">{{ $m['placeholders'] }}</code>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        (function () {
            var form = document.getElementById('admin-settings-form');
            if (form) {
                form.addEventListener('submit', function () {
                    if (typeof tinymce !== 'undefined') {
                        tinymce.triggerSave();
                    }
                });
            }

            function baseEditorConfig(textarea) {
                return {
                    target: textarea,
                    height: 360,
                    menubar: false,
                    branding: false,
                    promotion: false,
                    license_key: 'gpl',
                    plugins: 'link lists autolink code table autoresize',
                    toolbar:
                        'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link table | code removeformat',
                    relative_urls: false,
                    remove_script_host: false,
                    convert_urls: false,
                    entity_encoding: 'raw',
                    valid_elements: '*[*]',
                    extended_valid_elements:
                        'a[href|target|title|rel|class],img[src|alt|title|width|height|class|style],table[class|style|border],tr,td,th,tbody,thead',
                    autoresize_bottom_margin: 24,
                };
            }

            function initTinyForTextarea(textarea) {
                if (!textarea || textarea.getAttribute('data-tinymce-inited') === '1') {
                    return;
                }
                if (typeof tinymce === 'undefined') {
                    return;
                }
                textarea.setAttribute('data-tinymce-inited', '1');
                tinymce.init(baseEditorConfig(textarea));
            }

            document.querySelectorAll('textarea.js-notification-email-html').forEach(function (textarea) {
                var panel = textarea.closest('.accordion-collapse');
                if (!panel) {
                    initTinyForTextarea(textarea);
                    return;
                }
                if (panel.classList.contains('show')) {
                    initTinyForTextarea(textarea);
                } else {
                    panel.addEventListener(
                        'shown.bs.collapse',
                        function () {
                            initTinyForTextarea(textarea);
                        },
                        { once: true }
                    );
                }
            });
        })();
    </script>
@endpush
