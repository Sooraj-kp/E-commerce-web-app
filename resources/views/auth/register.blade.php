<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,500&display=swap');

        :root {
            /* ===== Cobalt blue on white ===== */
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --brand-tint: #dbeafe;
            --brand-soft: #eff6ff;
            --brand-rgb: 37, 99, 235;
            --border: #dbe5f3;
            --ink: #0f172a;
            --muted: #64748b;
            --danger: #e11d48;
        }

        body { font-family: "DM Sans", "Segoe UI", -apple-system, Roboto, Arial, sans-serif; }

        /* Page background. Also neutralises the default Breeze guest wrapper
           (grey background, extra white card, Laravel logo) so only our card shows. */
        .min-h-screen:has(.auth-wrapper) {
            background:
                radial-gradient(900px 420px at 50% -8%, #e6efff, transparent 70%),
                #f8fafc !important;
        }

        .min-h-screen:has(.auth-wrapper) > div:first-child { display: none; }

        div:has(> .auth-wrapper) {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin-top: 0 !important;
            max-width: none !important;
            overflow: visible !important;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 520px;       
            margin: 0 auto;
            padding: 24px 20px;
            font-family: "DM Sans", "Segoe UI", -apple-system, Roboto, Arial, sans-serif;
            color: var(--ink);
        }

        .auth-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 42px 40px 32px;
            box-shadow: 0 30px 60px -30px rgba(15, 23, 42, 0.22), 0 2px 6px rgba(15, 23, 42, 0.04);
        }

        /* ---------- Brand ---------- */
        .auth-brand { text-align: center; margin-bottom: 30px; }

        .auth-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            border-radius: 17px;
            display: grid;
            place-items: center;
            color: #fff;
            background: var(--brand);
            box-shadow: 0 14px 26px -10px rgba(var(--brand-rgb), 0.65), inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .auth-logo svg { width: 28px; height: 28px; }

        .auth-brand h1 {
            margin: 0;
            font-family: "Fraunces", Georgia, serif;
            font-size: 34px;
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: -0.025em;
        }

        .auth-brand h1 em { font-style: italic; font-weight: 500; color: var(--brand); }
        .auth-brand p { margin: 10px 0 0; color: var(--muted); font-size: 16px; }

        /* ---------- Fields ---------- */
        .auth-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }

        .auth-field { margin-bottom: 20px; }

        .auth-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14.5px;
            font-weight: 600;
            color: var(--ink);
        }

        .auth-label small { font-size: 12.5px; font-weight: 500; color: #94a3b8; }

        .auth-control { position: relative; }

        .auth-control > svg.lead {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 19px;
            height: 19px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .auth-control:focus-within > svg.lead { color: var(--brand); }

        .auth-input {
            width: 100%;
            margin-top: 0 !important;
            border: 1px solid #cfd9e8 !important;
            border-radius: 12px !important;
            padding: 14px 16px 14px 46px !important;
            background: #f8fafc !important;
            color: var(--ink) !important;
            font-family: inherit !important;
            font-size: 15.5px !important;
            line-height: 1.4 !important;
            box-shadow: none !important;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .auth-input::placeholder { color: #a0aec0; }
        .auth-input:hover { border-color: #aebcd2 !important; }

        .auth-input:focus {
            border-color: var(--brand) !important;
            background: #fff !important;
            box-shadow: 0 0 0 4px rgba(var(--brand-rgb), 0.14) !important;
            outline: none !important;
        }

        .auth-input.has-toggle { padding-right: 50px !important; }

        .toggle-pass {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 9px;
            background: transparent;
            color: #94a3b8;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .toggle-pass:hover { background: var(--brand-soft); color: var(--brand); }
        .toggle-pass svg { width: 20px; height: 20px; }
        .toggle-pass .eye-off { display: none; }
        .toggle-pass.shown .eye { display: none; }
        .toggle-pass.shown .eye-off { display: block; }

        .auth-hint { margin: 7px 0 0; font-size: 13px; color: var(--muted); }

        .auth-field ul { margin: 8px 0 0; padding: 0; list-style: none; font-size: 14px; color: var(--danger); font-weight: 500; }

        /* ---------- Button ---------- */
        .auth-button {
            width: 100%;
            margin-top: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            border: none;
            border-radius: 12px;
            padding: 15px 16px;
            color: #fff;
            background: var(--brand);
            font-family: inherit;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 26px -12px rgba(var(--brand-rgb), 0.85);
            transition: background 0.2s, transform 0.2s;
        }

        .auth-button svg { width: 18px; height: 18px; transition: transform 0.2s; }
        .auth-button:hover { background: var(--brand-dark); transform: translateY(-1px); }
        .auth-button:hover svg { transform: translateX(3px); }
        .auth-button:active { transform: translateY(0); }
        .auth-button:disabled { opacity: 0.75; cursor: wait; transform: none; }

        .auth-button:focus-visible,
        .auth-switch a:focus-visible,
        .toggle-pass:focus-visible {
            outline: 3px solid rgba(var(--brand-rgb), 0.4);
            outline-offset: 2px;
        }

        .auth-switch {
            margin-top: 24px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--muted);
            font-size: 15px;
        }

        .auth-switch a { color: var(--brand); font-weight: 700; text-decoration: none; }
        .auth-switch a:hover { color: var(--brand-dark); text-decoration: underline; text-underline-offset: 3px; }

        @media (max-width: 540px) {
            .auth-wrapper { padding: 12px; }
            .auth-card { padding: 32px 22px 26px; border-radius: 18px; }
            .auth-brand h1 { font-size: 29px; }
            .auth-grid { grid-template-columns: 1fr; }
        }
        .password-strength {
    margin-top: 12px;
}

.strength-track {
    width: 100%;
    height: 7px;
    background: #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}

.strength-bar {
    width: 0%;
    height: 100%;
    background: #94a3b8;
    border-radius: 10px;
    transition: width 0.3s ease, background 0.3s ease;
}

#passwordStrengthText {
    margin-top: 7px;
    font-size: 13px;
}
    </style>

    <div class="auth-wrapper">
        <div class="auth-card">

            {{-- Brand section --}}
            <div class="auth-brand">
                <div class="auth-logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>

                <h1>Create your <em>account</em></h1>
                <p>It only takes a minute to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="auth-field">
                    <label for="name" class="auth-label">Full name</label>

                    <div class="auth-control">
                        <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>

                        <x-text-input
                            id="name"
                            class="auth-input block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Your full name"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="auth-field">
                    <label for="email" class="auth-label">Email address</label>

                    <div class="auth-control">
                        <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>

                        <x-text-input
                            id="email"
                            class="auth-input block mt-1 w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autocomplete="username"
                            placeholder="you@example.com"
                        />
                    </div>

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Phone and date of birth -->
                <div class="auth-grid">

                    <div class="auth-field">
                        <label for="phone" class="auth-label">Phone <small>Optional</small></label>

                        <div class="auth-control">
                            <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>

                            <x-text-input
                                id="phone"
                                class="auth-input block mt-1 w-full"
                                type="tel"
                                name="phone"
                                :value="old('phone')"
                                autocomplete="tel"
                                placeholder="Phone number"
                            />
                        </div>

                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <div class="auth-field">
                        <label for="date_of_birth" class="auth-label">Date of birth <small>Optional</small></label>

                        <div class="auth-control">
                            <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>

                            <x-text-input
                                id="date_of_birth"
                                class="auth-input block mt-1 w-full"
                                type="date"
                                name="date_of_birth"
                                :value="old('date_of_birth')"
                            />
                        </div>

                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                </div>

                <!-- Password -->
                <div class="auth-field">
                    <label for="password" class="auth-label">Password</label>

                    <div class="auth-control">
                        <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>

                        <x-text-input
                            id="password"
                            class="auth-input has-toggle block mt-1 w-full"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Create a password"
                        />

                        <button type="button" class="toggle-pass" data-target="password" aria-label="Show password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>

                    <div class="password-strength">
                        <div class="strength-track">
                            <div id="passwordStrengthBar" class="strength-bar"></div>
                        </div>
                        <p id="passwordStrengthText" class="auth-hint">Enter a password  to check its strength</p>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="auth-field">
                    <label for="password_confirmation" class="auth-label">Confirm password</label>

                    <div class="auth-control">
                        <svg class="lead" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>

                        <x-text-input
                            id="password_confirmation"
                            class="auth-input has-toggle block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Repeat your password"
                        />

                        <button type="button" class="toggle-pass" data-target="password_confirmation" aria-label="Show password">
                            <svg class="eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <button type="submit" class="auth-button" id="registerBtn">
                    <span>Create account</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="auth-switch">
                Already have an account?
                <a href="{{ route('login') }}">Sign in</a>
            </div>

        </div>
    </div>

    <script>
        // Show / hide password buttons
        document.querySelectorAll('.toggle-pass').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('shown', show);
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });

        // Loading state on submit

        const registerForm=document.getElementById('registerForm');
        const password=document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');


        function checkPasswordStrength(){
            const value=password.value;
            const strengthBar=document.getElementById('passwordStrengthBar');
            const strengthText=document.getElementById('passwordStrengthText');

            let score=0;
            if(value.length>=8){
                score++;
            }
            if(/[A-Z]/.test(value)){
                score++;

            }
            if(/[a-z]/.test(value)){
                score++;
            }
            if(/[0-9]/.test(value)){
                score++;
            }
            if(/[^A-Za-z0-9]/.test(value)){
                score++;
            }
            if(value.length===0){
                strengthBar.style.width='0%';
                strengthText.textContent='Enter  a Password  to check its strength';
                return;
            }
            strengthBar.style.width=(score*20)+'%';
            if(score <=2){
                strengthText.textContent='weak password';
                strengthBar.style.background='#dc2626';

            }
            else if(score <= 4){
                strengthText.textContent='medium password';
                strengthBar.style.background='#f59e0b';

            }
            else {
                strengthText.textContent='strong password';
                strengthBar.style.background='#2fdc26';
            }
            

        }
        password.addEventListener('input',checkPasswordStrength);


        function checkPassword(){
            if(confirmPassword.value === ''){
                confirmPassword.setCustomValidity('');
                return;
            }
            if(password.value !== confirmPassword.value){
                confirmPassword.setCustomValidity('Passwords Donot Match');

            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        password.addEventListener('input',checkPassword);
        confirmPassword.addEventListener('input',checkPassword);

        registerForm.addEventListener('submit',function(event){
            checkPassword();

            if(!registerForm.checkValidity()){
                event.preventDefault();
                registerForm.reportValidity();
                return;
            }
            const btn=document.getElementById('registerBtn');
            btn.disabled=true;
            btn.querySelector('span').textContent='Creating account...'
        });
    </script>
</x-guest-layout>