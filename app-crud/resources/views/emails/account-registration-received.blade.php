@component('mail::message')
    # New Account Registration Submission!

    A new account registration submission by **"{{ $user->name }}"** has been received, please **Verify**

    **Details:**
    * **Name:** {{ $user->name }}
    * **Email:** {{ $user->email }}
    * **Role:** {{ $user->role }}
    * **Verification Status:** {{ $user->verificationStatus }}

    Regards,
    Shorefield Sdn Bhd
@endcomponent
