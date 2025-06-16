@component('mail::message')
# 🎉 Registration Successful!

Hello {{ $user->name }},  
You have successfully registered in our system.

Here are your details:

- **Name:** {{ $user->name }}
- **Email:** {{ $user->email }}
- **Contact:** {{ $user->contact ?? 'N/A' }}
- **Role:** {{ $user->role ?? 'user' }}

@component('mail::button', ['url' => route('login.page')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
