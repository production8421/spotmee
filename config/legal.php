<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Host waiver PDF documents
    |--------------------------------------------------------------------------
    */
    'host_document_directory' => 'documents/legal/host',

    'host_waiver_pdfs' => [
        'nda' => [
            'label' => 'Standard Non-disclosure Agreement',
            'filename' => env('LEGAL_HOST_NDA_PDF', 'host-nda.pdf'),
        ],
        'contractor' => [
            'label' => 'Independent Contractor Agreement',
            'filename' => env('LEGAL_HOST_CONTRACTOR_PDF', 'host-independent-contractor-agreement.pdf'),
        ],
    ],

    'host_waiver_path_columns' => [
        'nda' => 'legal_host_nda_pdf_path',
        'contractor' => 'legal_host_contractor_pdf_path',
    ],

    'host_waiver_upload_fields' => [
        'nda' => [
            'file' => 'legal_host_nda_pdf',
            'remove' => 'remove_legal_host_nda_pdf',
        ],
        'contractor' => [
            'file' => 'legal_host_contractor_pdf',
            'remove' => 'remove_legal_host_contractor_pdf',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User waiver PDF documents
    |--------------------------------------------------------------------------
    */
    'user_document_directory' => 'documents/legal/user',

    'user_waiver_pdfs' => [
        'nda' => [
            'label' => 'Standard Non-disclosure Agreement',
            'filename' => env('LEGAL_USER_NDA_PDF', 'user-nda.pdf'),
        ],
        'non_compete' => [
            'label' => 'Non-compete Agreement',
            'filename' => env('LEGAL_USER_NON_COMPETE_PDF', 'user-non-compete.pdf'),
        ],
    ],

    'user_waiver_path_columns' => [
        'nda' => 'legal_user_nda_pdf_path',
        'non_compete' => 'legal_user_non_compete_pdf_path',
    ],

    'user_waiver_upload_fields' => [
        'nda' => [
            'file' => 'legal_user_nda_pdf',
            'remove' => 'remove_legal_user_nda_pdf',
        ],
        'non_compete' => [
            'file' => 'legal_user_non_compete_pdf',
            'remove' => 'remove_legal_user_non_compete_pdf',
        ],
    ],

];
