<?php

return [
    'APP' => [
        'PAGE_TITLE' => 'Site Builder.',
    ],
    'DEFAULT' => [
        'ERROR' => 'Sorry! Something is wrong with this request, Please try again.',
        'HTTP_UNAUTHORIZED' => 'Unauthorized access to a restricted area!',
        'XHR_ERROR' => 'The request couldn\'t be completed! Please try again later.',
        'XHR_ERROR_401' => 'Unauthorized access to a restricted area!',
        'XHR_ERROR_403' => 'Access is forbidden to the requested URL!',
        'XHR_ERROR_404' => 'The requested URL couldn\'t be found!',
        'NORECORD' => 'No records available!',
        'NOMORERECORD' => 'No more records available!',
        'CONFIRM_DELETE' => 'Are you sure to delete the record(s)?',
        'SEARCH_PLACEHOLDER' => 'Search by ID or a list of IDs, Name and more',
        'COMMON_SEARCH_PLACEHOLDER' => 'Search by :SEARCH_NAME',
        'CONFIRM_DELETE' => '<div>This action cannot be undone.</div>',
        'DOMAIN_ERROR' => 'This Url is not valid Url.',
        'DUPLICATE_EMAIL' => 'The email you have entered is already exist.Please try again.',
        'SUBDOMAIN_SLUG' => 'Once subdomain slug is created, it canâ€™t be altered.',
        'BLANK_COUNTRY_ERROR' => 'Please select a country.',
        'FIRST_TIME_PASSWORD_RESET_ERROR' => 'For the first time use of the CRM, you must need to change your password!',
        'SECTION_IS_IN_USE' => 'You can not :ACTION this record. This record is already used in other sections.',
    ],
    'AUTH' => [
        'SUCCESS' => 'Authorized successfully.',
        'FAILURE' => 'Invalid email or password!',
        'NOT_PERMITTED' => 'You are not authorized to view that section yet!',
        'PERMITTED' => 'You have been already authorized!',
        'EXIT' => 'Logged out successfully.',
        'LOCKED' => 'Your account is locked:LOCKSTATE! Please try <a href=":LINKURL">resetting password</a>.',
        'FAILED_ATTEMPT' => 'Invalid password entered for the email! Please try again.',
        'FAILED_ATTEMPT' => 'Invalid password entered for the email! Please try <a class="reset_link" href=":LINKURL">resetting password</a>.',
        'UNAUTHENTICATE' => 'Unauthenticate',
        'TOKEN_MISMATCH' => 'Token mismatch',
        'PASSOWRD_ACTIVATE' => 'Account activated successfully.Please login...',
    ],
    'ACCOUNT_REQUEST' => [
        'SUCCESS' => 'Account request has been submitted successfully. We will review your request as early as possible and once approved, we will send you the confirmation link to the specified email.',
        'FAILURE' => 'Couldn\'t submit the request for account creation! Please try again later.',
        'EMAIL_FAILED' => 'Couldn\'t send the request notification to the specified email! Please try again later.',
        'APPROVE_USER_EXISTS' => 'A user already exists with the specified email!',
        'APPROVED' => 'Request has been approved and user account created.',
        'APPROVE_FAILED' => 'Couldn\'t update the account request now! Please try again later.',
        'ALREADY_APPROVED' => 'Account request already approved! Can\'t take any further action. Please go to the User section instead to take further action.',
        'DELETED' => 'Request has been deleted successfully.',
        'CONFIRMATION_FAILED' => 'Couldn\'t confirm your account! Invalid or expired confirmation token.'
    ],
    'RESET_REQUEST' => [
        'SUCCESS' => 'Password reset link has been sent to the specified email.',
        'FAILURE' => 'Couldn\'t generate password reset token! Please try again later.',
        'INVALID' => 'No user account exists for the specified email!',
        'EMAIL_FAILED' => 'Couldn\'t send password reset link to the specified email! Please try again later.',
        'INVALID_TOKEN' => 'Invalid or expired password reset request token! Please try resetting password again.',
    ],
    'RESET' => [
        'SUCCESS' => 'Password has been reset successfully. Redirecting to login...',
        'FAILURE' => 'Password reset couldn\'t be completed at this moment! Please try again later.',
        'INVALID' => 'Invalid inputs provided to reset password! Please try again later.',
        'OLD_MATCH' => 'You can\'t use the old password for password reset! Please try again.',
        'REPWD_NOT_MATCH' => 'Retype password doesn\'t match! Please try again.',
        'OLDPWD_NOT_MATCH_RECORD' => 'Old password doesn\'t match with our records!',
        'CNG_PWD_SUCCESS' => 'Password has been reset successfully.',
    ],
    'PASSWORD_RESET' => [
        'SUCCESS' => 'Password reset link has been sent to the specified email.',
        'INVALID_INPUTS' => 'Invalid email id provided!',
        'INVALID_EMAIL' => 'No user account exists for the specified email!',
        'UPDATE_FAILURE' => 'Couldn\'t generate password reset token! Please try again later.',
        'INVALID_TOKEN' => 'Either invalid or expired token has been used! Please try <a href=":LINKURL">resetting the password</a> again.',
        'NO_PASSWORD' => 'Invalid inputs provided!',
        'RESET_SUCCESS' => 'Your password has been updated successfully.',
        'PREV_PASSWORD_ERROR' => 'You have already used this password! Please enter a different password.',
        'PASSWORD_LENGTH_ERROR' => 'Password length should be greater than 8 chars.'
    ],
];
