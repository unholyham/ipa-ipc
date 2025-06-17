@component('mail::message')
    # Proposal Rejected

    Dear {{ $proposal->owner->name ?? 'User' }},

    We regret to inform you that your proposal for **"{{ $proposal->projectTitle }}"** has been **Rejected**

    **Details:**
    * **Project Title:** {{ $proposal->projectTitle }}
    * **Project Number:** {{ $proposal->projectNumber ?? 'N/A' }}
    * **Region:** {{ $proposal->region }}
    * **Prepared By:** {{ $proposal->preparedBy }}
    * **Main Contractor:** {{ $proposal->mainContractor }}
    * **Current Status:** {{ $proposal->approvedStatus }}
    * **Remarks:** {{ $proposal->remarks }}

    You can view the full details of your proposal by logging into the system.

    @component('mail::button', ['url' => route('proposal.view', $proposal->id)])
        View Proposal
    @endcomponent

    Thank you for your submission.

    Regards,
    {{ $proposal->mainContractor }}
@endcomponent
