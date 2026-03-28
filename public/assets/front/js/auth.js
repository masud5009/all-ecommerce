(function () {
    'use strict';

    const eyeOffSVG = '<path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.3-4.38 1.651 1.651 0 000-1.185A10.004 10.004 0 009.999 3a9.956 9.956 0 00-4.744 1.194L3.28 2.22zM7.752 6.69l1.092 1.092a2.5 2.5 0 013.374 3.373l1.091 1.092a4 4 0 00-5.557-5.557z" clip-rule="evenodd"/><path d="M10.748 13.93l2.523 2.523a9.987 9.987 0 01-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 010-1.186A10.007 10.007 0 012.839 6.02L6.07 9.252a4 4 0 004.678 4.678z"/>';
    const eyeOnSVG = '<path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>';

    const successIconSVG = '<svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>';

    function getCSRFToken() {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        return tokenMeta ? tokenMeta.getAttribute('content') : '';
    }

    function safeParseJSON(text) {
        try {
            return JSON.parse(text);
        } catch (e) {
            return null;
        }
    }

    function setupPasswordToggle(buttonId, inputId, iconId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (!btn || !input || !icon) {
            return;
        }

        btn.addEventListener('click', function () {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.innerHTML = isHidden ? eyeOffSVG : eyeOnSVG;
        });
    }

    function setupPasswordStrength(inputId, labelId, barIds, confirmInputId, matchIndicatorId) {
        const input = document.getElementById(inputId);
        const strengthLabel = document.getElementById(labelId);
        const bars = barIds.map(function (id) { return document.getElementById(id); });
        const confirmInput = confirmInputId ? document.getElementById(confirmInputId) : null;
        const matchIndicator = matchIndicatorId ? document.getElementById(matchIndicatorId) : null;

        if (!input || !strengthLabel || !bars.every(function (bar) { return bar !== null; })) {
            return;
        }

        const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];
        const labelColors = ['text-red-500', 'text-orange-500', 'text-yellow-600', 'text-green-600'];

        function checkStrength(pwd) {
            let score = 0;
            if (pwd.length >= 8) score++;
            if (/[A-Z]/.test(pwd)) score++;
            if (/[0-9]/.test(pwd)) score++;
            if (/[^A-Za-z0-9]/.test(pwd)) score++;
            return score;
        }

        function renderMatchIndicator() {
            if (!confirmInput || !matchIndicator) {
                return;
            }

            if (!confirmInput.value) {
                matchIndicator.classList.add('hidden');
                return;
            }

            matchIndicator.classList.remove('hidden');
            if (input.value === confirmInput.value) {
                matchIndicator.textContent = 'Passwords match';
                matchIndicator.className = 'mt-1.5 text-xs text-green-600';
            } else {
                matchIndicator.textContent = 'Passwords do not match';
                matchIndicator.className = 'mt-1.5 text-xs text-red-500';
            }
        }

        input.addEventListener('input', function () {
            const score = checkStrength(this.value);

            bars.forEach(function (bar, i) {
                bar.className = 'h-1 flex-1 rounded-full transition-all';
                bar.classList.add(i < score ? colors[score - 1] : 'bg-slate-200');
            });

            strengthLabel.textContent = this.value.length > 0 ? labels[score - 1] || '' : '';
            strengthLabel.className = 'mt-1 text-xs ' + (score > 0 ? labelColors[score - 1] : 'text-slate-400');
            renderMatchIndicator();
        });

        if (confirmInput) {
            confirmInput.addEventListener('input', renderMatchIndicator);
        }
    }

    function clearFormErrors(form) {
        form.querySelectorAll('[data-error-for]').forEach(function (el) {
            el.remove();
        });

        form.querySelectorAll('input, textarea, select').forEach(function (input) {
            input.classList.remove('border-red-400', 'bg-red-50');
            if (!input.classList.contains('border-slate-200')) {
                input.classList.add('border-slate-200');
            }
            if (!input.classList.contains('bg-slate-50')) {
                input.classList.add('bg-slate-50');
            }
        });
    }

    function renderFieldErrors(form, errors) {
        Object.keys(errors || {}).forEach(function (field) {
            const input = form.querySelector('[name="' + field + '"]');

            if (!input) {
                return;
            }

            input.classList.remove('border-slate-200', 'bg-slate-50');
            input.classList.add('border-red-400', 'bg-red-50');

            const message = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
            const errorEl = document.createElement('p');
            errorEl.className = 'mt-1.5 text-xs text-red-600';
            errorEl.setAttribute('data-error-for', field);
            errorEl.textContent = message;

            const block = input.closest('.mb-4, .mb-5, .mb-6') || input.parentElement;
            if (block) {
                block.appendChild(errorEl);
            }
        });
    }

    function setLoadingState(config, isLoading) {
        const submitButton = document.getElementById(config.submitBtnId);
        if (!submitButton) {
            return;
        }

        submitButton.disabled = isLoading;

        if (config.spinnerId) {
            const spinner = document.getElementById(config.spinnerId);
            if (spinner) {
                spinner.classList.toggle('hidden', !isLoading);
            }
        }

        if (config.buttonTextId) {
            const textNode = document.getElementById(config.buttonTextId);
            if (textNode) {
                textNode.textContent = isLoading ? config.loadingText : config.defaultButtonText;
            }
        } else {
            submitButton.classList.toggle('opacity-70', isLoading);
            submitButton.classList.toggle('cursor-not-allowed', isLoading);
            submitButton.textContent = isLoading ? config.loadingText : config.defaultButtonText;
        }
    }

    function showMessage(box, message, asHtml) {
        if (!box) {
            return;
        }

        box.classList.remove('hidden');
        box.classList.add('flex');

        if (asHtml) {
            box.innerHTML = message;
        } else {
            box.textContent = message;
        }
    }

    function hideMessage(box, preserveContent) {
        if (!box) {
            return;
        }

        box.classList.add('hidden');
        box.classList.remove('flex');

        if (!preserveContent) {
            box.textContent = '';
        }
    }

    function revealMessageBox(box) {
        if (!box) {
            return;
        }

        box.classList.remove('hidden');
        box.classList.add('flex');
    }

    function initAjaxForm(config) {
        const form = document.getElementById(config.formId);
        if (!form) {
            return;
        }

        const successBox = config.successBoxId ? document.getElementById(config.successBoxId) : null;
        const errorBox = config.errorBoxId ? document.getElementById(config.errorBoxId) : null;
        const errorTextNode = config.errorTextId ? document.getElementById(config.errorTextId) : null;

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            clearFormErrors(form);
            hideMessage(successBox, false);
            hideMessage(errorBox, Boolean(errorTextNode));

            if (errorTextNode) {
                errorTextNode.textContent = '';
            }

            setLoadingState(config, true);

            try {
                const response = await fetch(form.action, {
                    method: form.method || 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': getCSRFToken(),
                    },
                    body: new FormData(form),
                });

                const text = await response.text();
                const data = safeParseJSON(text) || {};

                if (response.ok && data.success) {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                        return;
                    }

                    if (successBox) {
                        showMessage(successBox, successIconSVG + '<span>' + (data.message || config.successMessage) + '</span>', true);
                    }

                    if (config.resetOnSuccess) {
                        form.reset();
                    }

                    return;
                }

                if (response.status === 422 && data.errors) {
                    renderFieldErrors(form, data.errors);
                    return;
                }

                const fallbackMessage = data.message || config.errorMessage;
                if (errorTextNode) {
                    errorTextNode.textContent = fallbackMessage;
                    revealMessageBox(errorBox);
                } else {
                    showMessage(errorBox, fallbackMessage);
                }
            } catch (error) {
                if (errorTextNode) {
                    errorTextNode.textContent = config.networkErrorMessage;
                    revealMessageBox(errorBox);
                } else {
                    showMessage(errorBox, config.networkErrorMessage);
                }
            } finally {
                setLoadingState(config, false);
            }
        });
    }

    // Shared visibility toggles
    setupPasswordToggle('toggle-password', 'password', 'eye-icon');
    setupPasswordToggle('toggle-new-password', 'new_password', 'eye-icon-1');
    setupPasswordToggle('toggle-confirm-password', 'new_password_confirmation', 'eye-icon-2');

    // Shared strength meters
    setupPasswordStrength('password', 'strength-label', ['bar-1', 'bar-2', 'bar-3', 'bar-4']);
    setupPasswordStrength('new_password', 'strength-label', ['bar-1', 'bar-2', 'bar-3', 'bar-4'], 'new_password_confirmation', 'match-indicator');

    // Shared ajax submit engine for auth pages
    initAjaxForm({
        formId: 'register-form',
        submitBtnId: 'register-submit-btn',
        defaultButtonText: 'Create account',
        loadingText: 'Creating...',
        successBoxId: 'register-success',
        errorBoxId: 'register-general-error',
        successMessage: 'Registration successful.',
        errorMessage: 'Unable to create account right now.',
        networkErrorMessage: 'Unable to submit form right now.',
        resetOnSuccess: true,
    });

    initAjaxForm({
        formId: 'login-form',
        submitBtnId: 'login-btn',
        buttonTextId: 'login-btn-text',
        spinnerId: 'login-spinner',
        defaultButtonText: 'Sign in',
        loadingText: 'Signing in...',
        errorBoxId: 'ajax-error',
        errorTextId: 'ajax-error-msg',
        successMessage: 'Login successful.',
        errorMessage: 'Login failed. Please try again.',
        networkErrorMessage: 'Something went wrong. Please try again.',
        resetOnSuccess: false,
    });

    initAjaxForm({
        formId: 'reset-password-form',
        submitBtnId: 'reset-submit-btn',
        defaultButtonText: 'Reset password',
        loadingText: 'Resetting...',
        successBoxId: 'reset-success',
        errorBoxId: 'reset-general-error',
        successMessage: 'Password reset successful.',
        errorMessage: 'Unable to reset password right now.',
        networkErrorMessage: 'Something went wrong. Please try again.',
        resetOnSuccess: true,
    });
})();
