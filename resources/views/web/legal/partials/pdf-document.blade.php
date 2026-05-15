@php
    use App\Support\LegalDocument;

    /** @var string $pdfKey */
    /** @var string $audience host|user */
    $audience = $audience ?? 'host';
    $doc = LegalDocument::waiverPdf($audience, $pdfKey ?? '');
    $resolved = LegalDocument::waiverPdfResolved($audience, $pdfKey ?? '');
    $adminPage = $audience === 'user'
        ? __('Waiver of Liability User')
        : __('Waiver of Liability Host');
    $publicDir = config("legal.{$audience}_document_directory", "documents/legal/{$audience}");
@endphp

@if ($doc)
    <div class="legal-pdf-block" data-legal-pdf>
        @if ($resolved)
            <div class="legal-pdf-actions">
                <a
                    href="{{ $resolved['url'] }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-outline btn-sm legal-pdf-actions__btn"
                >
                    <i class="fa-solid fa-up-right-from-square" aria-hidden="true"></i>
                    {{ __('Open PDF') }}
                </a>
                <a
                    href="{{ $resolved['url'] }}"
                    download="{{ $resolved['download_name'] }}"
                    class="btn btn-primary btn-sm legal-pdf-actions__btn"
                >
                    <i class="fa-solid fa-download" aria-hidden="true"></i>
                    {{ __('Download PDF') }}
                </a>
            </div>

            <div class="legal-pdf-viewer" role="region" aria-label="{{ $resolved['label'] }}">
                <iframe
                    src="{{ $resolved['url'] }}#view=FitH"
                    title="{{ $resolved['label'] }}"
                    class="legal-pdf-viewer__frame"
                    loading="lazy"
                ></iframe>
            </div>

            <p class="legal-pdf-fallback">
                {{ __('If the document does not display above,') }}
                <a href="{{ $resolved['url'] }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-[var(--color-primary)] hover:underline">
                    {{ __('open the PDF in a new tab') }}
                </a>
                {{ __('or use Download PDF.') }}
            </p>
        @else
            <div class="legal-pdf-missing">
                <i class="fa-solid fa-file-pdf legal-pdf-missing__icon" aria-hidden="true"></i>
                <p class="legal-pdf-missing__title">{{ __('PDF not available yet') }}</p>
                <p class="legal-pdf-missing__text">
                    {{ __('Upload this document in Admin → Frontend → :page, or place :filename in public/:dir/.', [
                        'page' => $adminPage,
                        'filename' => $doc['filename'],
                        'dir' => $publicDir,
                    ]) }}
                </p>
            </div>
        @endif
    </div>
@endif
