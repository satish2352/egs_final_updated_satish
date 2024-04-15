<?php

namespace App\Constants;

return [
    define('LABOUR_DEFAULT_START', 1),
    define('LABOUR_DEFAULT_LENGTH', 2),

    define('GRAM_DOCUMENT_DEFAULT_START', 1),
    define('GRAM_DOCUMENT_DEFAULT_LENGTH', 2),

    'USER_PROFILE_ADD'	         => "/all_web_data/images/userProfile/",
    'USER_PROFILE_DELETE'	         => '/all_web_data/images/userProfile/',
    'USER_PROFILE_VIEW'	         => env("FILE_VIEW").'/all_web_data/images/userProfile/',

    'USER_LABOUR_ADD'	         => "/all_web_data/images/labour/",
    'USER_LABOUR_DELETE'	         => '/all_web_data/images/labour/',
    'USER_LABOUR_VIEW'	         => env("FILE_VIEW").'/all_web_data/images/labour/', 
    
    'GRAM_PANCHAYAT_DOC_ADD'	         => "/all_web_data/documents/GramPanchayatDoc/",
    'GRAM_PANCHAYAT_DOC_DELETE'	         => '/all_web_data/documents/GramPanchayatDoc/',
    'GRAM_PANCHAYAT_DOC_VIEW'	         => env("FILE_VIEW").'/all_web_data/documents/GramPanchayatDoc/', 
];