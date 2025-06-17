@component('mail::message')
    # Proposal Approved!

    Dear {{ $proposal->owner->name ?? 'User' }},

    We are pleased to inform you that your proposal for **"{{ $proposal->projectTitle }}"** has been **Approved!**

    **Details:**
    * **Project Title:** {{ $proposal->projectTitle }}
    * **Project Number:** {{ $proposal->projectNumber ?? 'N/A' }}
    * **Region:** {{ $proposal->region }}
    * **Prepared By:** {{ $proposal->preparedBy }}
    * **Main Contractor:** {{ $proposal->mainContractor }}
    * **Current Status:** {{ $proposal->approvedStatus }}

    You can view the full details of your proposal by logging into the system.

    @component('mail::button', ['url' => route('proposal.view', $proposal->id)])
        View Proposal
    @endcomponent

    Thank you for your submission.

    Regards,
    {{ $proposal->mainContractor }}
@endcomponent
