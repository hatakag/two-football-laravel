<?php

return [
    'role' => [
        'user' => 'ROLE_USER',
        'admin' => 'ROLE_ADMIN',
    ],

    'pusher' => [
        'BET_CHANNEL' => 'BET_RESULT_CHANNEL',
        'BET_EVENT_PREFIX' => 'bet_',
        'COMMENT_CHANNEL' => 'COMMENT_CHANNEL',
        'COMMENT_EVENT_PREFIX' => 'comment_',
    ],

    'error_response' => [
        'SIGNUP_EXISTING_USER_ERROR' => [
            'status' => false,
            'message'=> 'Username/Phone number/Email is used already',
            'code' => 101,
        ],

        'FAIL_LOGIN' => [
            'status' => false,
            'message' => 'Username/password is wrong',
            'code' => 201,
        ],

        'MISSING_AUTHORIZATION_FIELD' => [
            'status' => false,
            'message' => 'Header is missing the authorization field',
            'code' => 301,
        ],

        'BAD_AUTHORIZATION' => [
            'status' => false,
            'message' => 'Value of the authorization field is not in the correct form',
            'code' => 302,
        ],

        'FORBIDDEN_RESPONSE' => [
            'status' => false,
            'message' => 'Request is forbidden',
            'code' => 303,
        ],

        'EXPIRED_TOKEN' => [
            'status' => false,
            'message' => 'Expired access token',
            'code' => 304,
        ],

        'INVALID_TOKEN' => [
            'status' => false,
            'message' => 'Invalid access token',
            'code' => 305,
        ],

        'BLACKLISTED_TOKEN' => [
            'status' => false,
            'message' => 'Blacklisted access token',
            'code' => 306,
        ],

        'TOKEN_NOT_PROVIDED' => [
            'status' => false,
            'message' => 'Token not provided',
            'code' => 307,
        ],

        'USER_NOT_FOUND' => [
            'status' => false,
            'message' => 'User not found',
            'code' => 401,
        ],

        'BAD_CARD_REQUEST' => [
            'status' => false,
            'message' => 'Payload consists an invalid card code / password.',
            'code' => 501,
        ],

        'BAD_BET_REQUEST' => [
            'status' => false,
            'message' => 'Bet type >=> [ Bet amount > 0',
            'code'=> 601,
        ],

        'MATCH_NOT_FOUND' => [
            'status' => false,
            'message' => 'Match not found',
            'code' => 602,
        ],

        'MATCH_FINISHED' => [
            'status' => false,
            'message' => 'Match is finished',
            'code' => 603,
        ],

        'MATCH_LIVE' => [
            'status' => false,
            'message' => 'Match is live, you can not bet',
            'code' => 604,
        ],

        'INVALID_BET_AMOUNT' => [
            'status' => false,
            'message' => 'Bet amount must be lower than or equal to the balance of account',
            'code' => 605,
        ],

        'BET_ALREADY' => [
            'status' => false,
            'message' => 'Bet this type already',
            'code' => 606,
        ],

        'BAD_COMMENT' => [
            'status' => false,
            'message' => 'Comment must contain 1-300 character',
            'code' => 607,
        ],
    ]
]
?>