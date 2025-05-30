@component('mail::message')
# Account Registration Approved!

Dear {{ $user->name ?? 'User' }},

We are pleased to inform you that your account registration for **"{{ $user->name }}"** has been **Approved!**

**Details:**
* **Name:** {{ $user->name }}
* **Email:** {{ $user->email }}
* **Role:** {{ $user->role }}
* **Verification Status:** {{ $user->verificationStatus }}

You may now log in into system.

@component('mail::button', ['url' => route('login')])
Login
@endcomponent

Thank you for your registration.

Regards,
Shorefield Sdn Bhd
@endcomponent