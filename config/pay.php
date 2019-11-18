<?php
/**
 * Created by PhpStorm.
 * User: 0375
 * Date: 2019/11/15
 * Time: 13:39
 */
return [
    'alipay' => [
        'app_id'         => '2016101000649177',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAowr7SIRXdYmnOqTOflwJDzqkKReHvPrgTOkPkHuLVe20rg1VYCU3qeYP/RgXDOh+O/k8rdeakeKEq3Ry4lDjdupufWS8dIWS7n4CU58Nln4HYhJGKZpHzwzMq3+OXvcf75CPGgriU4cS4CgmuefSBXTOT/AsBowov+S0a7e+HuS5qGL7me35Mq/TcNOZe6lJUQ/vtQe9+LfkOSa4CDf6jVKmNa8ubJ5hWlS8wbti/s0nqYGWhmfBpd1H5f2JtzzeTfX6yB6hItYI1GQUzTaDFLYcEiBjrbSkvxPPXCrtRAARjaa1nj9tH7cEKW6CNUnmDWbZQUlaqyKbXDzy+L34HQIDAQAB',
        'private_key'    => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCLX1nFeLo4gmxDzhCSPW3IlWmjGqtlbfFqZuWxvNS4GyFBE5fil4xOwQKT2eBQQSsv7VDW9CaDIpQr15VfAedyMtuKZxbByvvYPHLdNRvlO5ftD9VvZ3dezUBY8vPqNF9/3uOgPvO9R2mMt7sR8fbV0OE5QLmeY+CK91y+lO02tT5Dc5PbPs3yItYCmgW8+ARwqOFJRdO859kjOA0opWX6o26vPdgjw83P3iBP6n+FmiolalaQc659ixivZOVsRaSMiInWlelmQxfOF4oWM+3RdfpZ1Qwew7setI4c97NXY3HkWkrEmkJvnRB5ZY/N1x+QkOSkwID5NTpVNGPqlMVtAgMBAAECggEAe3V9ikXFgCVYTkANwd2UkGh6aTdIuNLJVd3MfsOtE2FE9earVte0PFcRN3N7FtyDqzLnt2ITc3qAEs4nzT9cjHasr09eg1CSAxyZC4buLQO7Mw4hnUvkHCBpbeHZW5AFQLpqnRrmwratsy9WETFr3D/qItNJGhuTXsZ8a351G03jFjFd7E0iFMMzTOknBEF4rXWQnlIkPSz9TdvvaO8D+0eFQ9m3cCmL3wi097LW/JDYHTrdqtqPrHD0tmx8UMS+pOGR9ymudC0z+NYHNnMrHxgcxaxOBk6fTPWOSR1LR4TLKZb6cTI9NNrI6OUOB8iBwbWkhYCJcUJLWlO9GVl/aQKBgQDc47BXJKFhkdE1gu/zf8yxOkMkvuVtNDRj+r4yDWOqqs87wPqGMNYXOzpV/m5sTWQepaL6AmR3O4E7xFECdNkEbdiB2hIRI607DJqyFpDQnFLEqYIMCUsBxutJr/2oN4EipOToviMV7qeyXaiUmSefMJxZ//fnls7XZiLEvkb3twKBgQChhp5qbzep6NQMLsnmk6riZlalbOqfxlWOPthk3HzF+2K1ddI9wMt+bxWYc6ba1wW4duJu3XxYL2H9aV2oS2OPzkEEmvco57QIYpUWhp2zbUR1mEuldAn0UeZf4uKC+ezT/JJfPxL3YTw29ec7FvP8ZpcOIG91IzybO+hAqy1D+wKBgEaG2F3qjzB0+2Rniy+nBXcs0BViciR8/6FQhPu8NK9gXEyK4DKKU0EVoxmj5CPmTepPHotOyj8bm7a2htsO7d+xJujG9O/OAViSPK5R8Cj7UJ4ENUacjPtjROrBK29TUYL7PS9mzhMJoTedGd9gSztTrQg7Zy7lguNZdA53ZndfAoGAcHCalDLJh+CIw9ZKO9Utjp540H0qMoDJC9UZo9SMkvE0vGbBLLWpsmC3osFXNF2cINW5OD28ElMhnlsblEyuiOul/QO26+WnQHMHn3+kBcQZdNbISeumBkqA2NzCmzRJNSRL7DunA0fPt96j0VFgFKKBopbXn64gwVsJiQLf5sMCgYEAizMvKVhqLx5N+hrLf+ydSd8IXIotprvb+nszV8Ravt6O1F1Ug5TzHS4H+ujbIjsP3PoWikFYbarf7j1JEcbo/1zPidtD+kcYyaWVF0n4sAKYjswcUbVjy8cWK9pL0UXO1JuLJoE+p683yuFYBJ1Qe8yKl87bnz5w3dJVTQzAGIw=',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];