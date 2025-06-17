@component('mail::message')
    # Account Registration Rejected

    Dear {{ $user->name ?? 'User' }},

    We regret to inform you that your account registration for **"{{ $user->name }}"** has been **Rejected**

    **Details:**
    * **Name:** {{ $user->name }}
    * **Email:** {{ $user->email }}
    * **Role:** {{ $user->role }}
    * **Verification Status:** {{ $user->verificationStatus }}
    * **Remarks:** {{ $user->remarks }}

    Please submit a new registration application.

    @component('mail::button', ['url' => route('register')])
        Register Account
    @endcomponent

    Thank you for your registration.

    Regards,
    Shorefield Sdn Bhd
@endcomponent
