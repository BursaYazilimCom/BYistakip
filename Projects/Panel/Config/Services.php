<?php return
[
    /*
    |--------------------------------------------------------------------------
    | Uri
    |--------------------------------------------------------------------------
    |
    | Contains URI related settings.
    |
    | lang: Language abbreviation becomes available at URI.
    |
    */
    
    'uri' =>
    [
        'lang' => false
    ],

   /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    |
    | Contains settings related to Email library. 
    | 
    | driver: Email send drivers. [smtp, imap]
    | smtp: Send settings via SMTP.
    | general: General e-mail settings.
    |
    */

    'email' =>
    [
        'driver' => 'smtp',
        'smtp'   =>
        [
            'host'      => AyarModel::defaultAyarlar('smtpHost'),
            'user'      => AyarModel::defaultAyarlar('smtpUser'),
            'password'  => AyarModel::defaultAyarlar('smtpPass'),
            'port'      => AyarModel::defaultAyarlar('smtpPort'),
            'keepAlive' => false,
            'timeout'   => 10,
            'encode'    => AyarModel::defaultAyarlar('smtpEncode'),  # empty, tls, ssl
            'dsn'       => false,
            'auth'      => true
        ],
        'imap' => 
        [
            'host'      => 'us2.smtp.mailhostbox.com',
            'user'      => 'info@bursayazilim.com',
            'password'  => 'ztNU$fK1',
            'port'      => 25,
            'flags'     => [],
            'mailbox'   => 'INBOX'
        ],
        'general' =>
        [
            'senderMail'    => 'wexcp@bydemo.com.tr',                  # Default Sender E-mail Address.
            'senderName'    => 'Bursa Yazılım',                  # Default Sender Name.
            'priority'      => 3,                   # 1, 2, 3, 4, 5
            'charset'       => 'UTF-8',             # Charset Type
            'contentType'   => 'html',              # plain, html
            'multiPart'     => 'mixed',             # mixed, related, alternative
            'xMailer'       => 'ZN',
            'encoding'      => '8bit',              # 8bit, 7bit
            'mimeVersion'   => '1.0',               # MIME Version
            'lf'            => "\n",                # For Mail Body
            'cr'            => "\n",                # For STMP Commands
            'mailPath'      => '/usr/sbin/sendmail' # Default Mail Path
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Processor
    |--------------------------------------------------------------------------
    |
    | Contains Processor library related settings.
    |
    | driver: It is specified which function the Processor::exec() method 
    |         will use.
    |         Options: exec, shell, system, ssh
    | path: The current PHP path. Especially necessary for crontab.
    |
    */

    'processor' =>
    [
        'driver' => 'exec',      
        'path'   => '/usr/bin/php'
    ],

    /*
    |--------------------------------------------------------------------------
    | SSH
    |--------------------------------------------------------------------------
    |
    | Includes SSH connection settings.
    |
    */

    'ssh' =>
    [
        'host'          => '', 
        'user'          => '',  
        'password'      => '',  
        'port'          => 22, 
        'methods'       => [],  
        'callbacks'     => []  
    ],

    /*
    |--------------------------------------------------------------------------
    | FTP
    |--------------------------------------------------------------------------
    |
    | Includes FTP connection settings.
    |
    */

    'ftp' =>
    [
        'host'        => '',  
        'user'        => '',   
        'password'    => '',   
        'timeout'     => 90, 
        'port'        => 21, 
        'sslConnect'  => false,
        'passiveMode' => false
    ]
];
